<?php

namespace App\Process;

use App\Libraries\Machship\Machship;
use App\Models\Integration;
use App\Models\IntegrationRecords;
use App\Models\IntegrationSyncs;
use App\Models\DebugLogs;
use App\Repositories\IntegrationRecordsRepository;
use Carbon\Carbon;
use Exception;
use Symfony\Component\HttpFoundation\ParameterBag;
use App\Services\FieldMapService;
use App\Services\DataConversionService;
use App\Services\MachshipCacheService;
use App\Traits\LoggerTrait;
use App\Traits\ErrorNotificationTrait;
use App\Jobs\ProcessSyncToMachship;

class Process
{

    use LoggerTrait;
    use ErrorNotificationTrait;

    private $integration;
    private $integration_sync;
    private $platform;
    private $integration_field_mappers;
    private $integration_value_lookups;
    private $integration_records_repo;
    private $machship;
    private $data_conversion_service;
    private $field_map_service;
    private $machship_cache_service;
    private $step;

    const TAG = '[PROCESS]';

    /**
     * Process constructor
     * @param Integration $integration
     */
    public function __construct(
        Integration $integration
    ) {
        $this->step = DebugLogs::STEP_WF_2;
        $this->integration = $integration;
        $this->integration_field_mappers = $this->integration->fieldMapper;
        $this->integration_value_lookups = $this->integration->valueLookup;

        $this->integration_records_repo = new IntegrationRecordsRepository(app());
        $this->setSourceIntegrationConfig();
        $this->setMachshipConfig();
    }

    // Sets configurations and services of the source integration
    private function setSourceIntegrationConfig()
    {

        try {
            $params = $this->integration->getIntegrationMeta();
            // platform initiate
            $this->platform = $this->integration->integrationType->getPlatformClass();
            $this->platform->setIntegration($this->integration);
            $this->platform->init($params);
            // services initiate
            $this->field_map_service = new FieldMapService($this->platform);
            $this->data_conversion_service = new DataConversionService($this->integration_value_lookups, $this->platform);
        } catch (Exception $e) {
            $this->processError($e);
        }
    }

    // Sets machship configurations
    private function setMachshipConfig()
    {
        try {
            $id = $this->integration->id;
            $token = $this->integration->getMachshipTokenKey();

            // token must not be empty
            if (empty($token)) {
                return;
            }

            $this->machship = new Machship($token);
            $this->machship_cache_service = new MachshipCacheService($this->machship, $this->integration);
            $this->platform->setMachship($this->machship); // added this for Machship available to the platform
            $this->platform->setMachshipCache($this->machship_cache_service);
        } catch (Exception $e) {
            $this->processError($e);
        }
    }

    public function getPlatform()
    {
        return $this->platform;
    }

    /**
     * This function will act as the main syncing process which performs the following:
     * Create integration sync and records
     * WF-2 : Sync Job - Get Data
     * WF-3 : Sync Job - Process Data
     * WF-4 : Sync Job - Send to Machship
     * @param IntegrationSyncs $integration_sync
     */
    public function mainSyncProcess(IntegrationSyncs $integration_sync)
    {

        $this->integration_sync = $integration_sync;
        $this->platform->setIntegrationSync($integration_sync);
        $this->platform->preProcess();

        // TODO Add Validation here to check if all necessary data has been initialized
        $source_data = [];
        try {
            // WF - 2 Get Source Data
            $this->step = DebugLogs::STEP_WF_2;
            $source_data = $this->syncGetData();

            // WF - 3 Process data
            $this->step = DebugLogs::STEP_WF_3;
            $this->syncProcessData($source_data);

            // WF - 4 Send To Machship
            $this->step = DebugLogs::STEP_WF_4;
            ProcessSyncToMachship::dispatch($this->integration_sync);
        } catch (Exception $e) {
            // Handle exception here
            $this->processError($e);
            return;
        }

        $this->platform->postProcess();
    }


    /**
     * WF-2 Get data from integration's source
     * 1. [Optional] Execute pre get data if exist
     * 2. Gets data from source
     * 3. [Optional] Exceute post get data if exist
     * 4. Log data source block to debugLog
     * 5. Update integration sync status
     * @return     Array       Integration's source data
     */
    private function syncGetData()
    {
        // validate integration sync here
        if (!$this->integration_sync) {
            $this->infoLog('no integration sync');
            return;
        }

        // - Perform PRE get data per integration here if there is one custom_function
        // - The query builder will build specific parameters that will be appended in the source integration query.
        // - Get data source from platform
        $source_data = $this->platform->get();
        // $source_data = $this->platform->getTest();
        // - Perform POST get data per integration after when custom_function exist

        // - no result
        if (empty($source_data)) {
            // update integration sync data, period end, record count, and set status to COMPLETED, and exits sync process.
            $this->integration_sync->records_found = 0;
            $this->integration_sync->sync_status = IntegrationSyncs::SYNC_STATUS_COMPLETED;
            $this->integration_sync->save();
            $this->infoLog('integration data is empty');
            return;
        }

        // Log source block and update integration sync record numbers
        $total = count($source_data);
        $this->integration_sync->records_found = $total;
        $this->integration_sync->save();

        $this->writeDebugLog('INFO', $source_data);


        // update the sync process
        $this->integration_sync->sync_status = IntegrationSyncs::SYNC_STATUS_PROCESSING;
        $this->integration_sync->save();

        return $source_data;
    }

    /**
     * WF - 3 Processing of source data
     * 1. Splitting of source data
     * 2. Deduplicating, to avoid duplication of data
     * 3. Create integration record
     * 4. Process Record and data
     * 4.1. Execute Field mapping and conversion of data
     * 4.2. Get consignment type
     * 4.3. Update record's data and status
     * @param Array $source_data The source data
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function syncProcessData($source_data)
    {
        if (empty($source_data)) {
            $this->infoLog('no source data', $source_data);
            return;
        }

        // 1. Split source of data
        foreach ($source_data as $data) {
            // make sure to convert to array
            $data = json_decode(json_encode($data), true);

            // sets platform source data
            $this->platform->setData($data);

            // Step 2. dedup - checks for duplications
            $source_id = $this->platform->getSourceId($data);
            if ($this->integration_records_repo->dedup($this->integration->id, $source_id)) {
                // stops when it duplicates
                // log and note as skipped in debugLogs and skip record.
                $this->writeDebugLog('Skipped', "Source id $source_id is duplicate: " . json_encode($data));
                continue;
            }

            //create integration record
            $record = $this->createIntegrationRecord($this->integration_sync->id, $source_id, $data);

            if (!$record) {
                $this->writeDebugLog('Failed to create integration record', 'record is empty');
                continue;
            }


            // continue processing the record and source data
            $this->processRecordAndData($record, $data);
        }
    }


    /**
     * This is a part of WF-3 where it will final prrocess its record and data
     * - Execute Field mapping and conversion of data
     * - Get consignment type
     * - Update record's data and status
     *
     * @param      \App\Models\IntegrationRecords  $record  The record
     * @param      Array                         $data    The data
     */
    public function processRecordAndData(IntegrationRecords $record, $data)
    {
        // set this platform current integration record, for custom function use
        $this->platform->setCurrentRecord($record);

        // execute field mapping and data conversion here
        $machship_data = $this->executeMapper($data);

        if (is_null($machship_data)) {
            $this->writeDebugLog('Error in mapping conversion and lookups', 'machship_data is null');
            $record->record_status = IntegrationRecords::RECORD_STATUS_ERROR;
            $record->save();

            // TODO: should create an email notification to FusedSoftware admin
            return;
        }

        // set platform's machship payload
        $this->platform->setMachshipPayload($machship_data);


        // Get consignment type
        $consignment_type = $this->platform->getConsignmentType();

        // get integration record status
        $record_status = $this->platform->getIntegrationRecordStatus();

        // get integration process after
        // todo: this should be PRE custom function in WF - 4
        $process_after = $this->platform->getIntegrationRecordProcessAfter();

        $record->consignment_type = $consignment_type;
        $record->record_status = $record_status;
        $record->machship_payload = $machship_data;
        $record->process_after = $process_after;
        $record->save();
    }

    /**
     * executes field mapping and data conversion here
     * 1. Fetch field mappers
     * 2. Separate mapfields with items and without
     * 3. Loop mapfields that is not for “items_” and do normal mapping and conversion
     * 4. Get items from platform // from an abstract function, this would prepare us where to locate the item to be mapped
     * 5. Now, If there is mapfields for items, proceed to #6, otherwise just save result from #4
     * 6. iterate each items
     * 7. Reset map_service data from #4 per item
     * 8. Loop mapfields from fields with “items_”
     * 9. execute mapping or/and conversion for items
     * @param      array  $data   The source data
     * @return     array   mapped and converted machship data
     */
    private function executeMapper($data)
    {
        $machship_data = [];
        $item_mappers = [];
        $this->field_map_service->setSourceData($data);

        // step.3 Loop mapfields
        foreach ($this->integration_field_mappers as $field) {
            // we need to bypass every empty mappers
            if (empty($field['map_type']) &&
                empty($field['source_field']) &&
                empty($field['data_conversion_type']) &&
                empty($field['data_conversion_value'])
            ) {
                continue;
            }

            $machship_field = $field['machship_field'];
            // separate mapfields with items here
            if (strpos($machship_field, 'items.') !== false) {
                $item_mappers[] = $field;
                continue;
            }

            $mapped_data = $this->executeMapping($field);

            // proper format machship fields
            if (strpos($machship_field, ".") !== false) {
                $keys = explode(".", $machship_field);
                $machship_data[$keys[0]][$keys[1]] = $mapped_data;
                continue;
            }

            $machship_data[$machship_field] = $mapped_data;
        }

        // step.4 get items from platform
        $items = $this->platform->getItems($data);

        // step.5 check if there is mapper for items
        if (!empty($item_mappers) && !empty($items)) {
            // step.6 iterate items
            foreach ($items as $index => $item) {
                // step.7 Reset map_service per item
                $this->field_map_service->setSourceData($item);
                // step.8 - step.9 execute mapping and conversion per each item
                $item_data = [];
                foreach ($item_mappers as $field) {
                    $machship_field = $field['machship_field'];
                    $mapped_data = $this->executeMapping($field);

                    $keys = explode(".", $machship_field);

                    // Validate keys
                    if (count($keys) <= 1) {
                        continue;
                    }

                    // special cases when we specify mapping in items
                    // if (is_numeric($keys[1])) {
                    //     $machship_data[$keys[0]][$keys[1]] = [
                    //         $keys[2] => $mapped_data
                    //     ];
                    //     continue;
                    // }

                    // pattern goes like ['items'][0]['sku']
                    $item_data[$keys[1]] = $mapped_data;
                }

                $machship_data['items'][$index] = $item_data;
            }

        // otherwise
        } elseif (!empty($items)) {
            $machship_data['items'] = $items;
        }

        return $machship_data;
    }

    /**
     * Execute mapping and conversion of each field
     * 1. execute field map service mapped by fields
     * 2. execute conversion of data
     * @param      Array   $field   From FieldMapper
     * @return     Array.  converted and mapped fields
     */
    private function executeMapping($field)
    {
        // step.1 execute field mapper
        $mapped_field = $this->field_map_service->getMappedByField($field);

        // step.2 execute conversion data
        $converted_field = $this->data_conversion_service->getConversionData($field, $mapped_field);

        return $converted_field;
    }

    /**
     * Creates an integration record.
     * @param integer $sync_id The synchronize identifier
     * @param integer $source_id The source identifier
     * @param array $data The data
     * @return     object  newly created integratio nrecord
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    private function createIntegrationRecord($sync_id, $source_id, $data)
    {
        $integration_record = new ParameterBag();
        $integration_record->set('integration_id', $this->integration->id);
        $integration_record->set('integration_sync_id', $sync_id);
        $integration_record->set('source_id', $source_id);
        $integration_record->set('source_data', $data);
        $integration_record->set('record_status', IntegrationRecords::RECORD_STATUS_PENDING);

        return $this->integration_records_repo->create($integration_record->all());
    }

    /**
     * Writes a debug log shortcut.
     * @param      String  $message  The message
     * @param      Any     $data     The data
     */
    private function writeDebugLog($message, $data)
    {
        $title = $this->step;
        $data = is_string($data) ? $data : json_encode($data);
        $this->debugLog($title, $message, [
            'integration_id' => $this->integration->id,
            'integration_sync_id' => empty($this->integration_sync) ? null : $this->integration_sync->id,
            'data' => $data
        ]);
    }


    /**
     * Handling an error when error occured
     * write to debug log
     * send error notification
     * udpate integration status
     * @param      Exception  $e      exception error
     */
    private function processError(Exception $e)
    {
        $this->errorLog('Error Exception', $e);
        $this->writeDebugLog('Error', $e->getMessage());
        $this->sendErrorNotification($e);

        // update status if there is an active integration sync
        if ($this->integration_sync) {
            $this->integration_sync->sync_status = IntegrationSyncs::SYNC_STATUS_ERROR;
            $this->integration_sync->save();

            if (method_exists($this->platform, "onLogout")) {
                $this->platform->onLogout();
            }
        }
    }

    /********************************************************************** WORK FLOW 5 FUNCTIONS **********************************************************************/

    /**
     * @param $id
     * @return mixed
     */
    public function getMachshipConsignmentById($id)
    {
        return $this->machship->getConsignment($id);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getMachshipConsignmentByPendingId($id)
    {
        return $this->machship->getConsignmentByPendingConsignmentId($id);
    }

    /**
     * @param $record
     * @param $machship_status
     * @param null $response
     * @return null | String
     */
    public function updateSourceIntegrationData($record, $machship_status, $response = null)
    {
        // get the fields only for source updates
        $integration_source_fields = $this->integration->fieldMapper()->where('data_direction', '=', 'to_source')->get();

        // map/convert the data accordingly the data
        $parameter = [];
        if (!$integration_source_fields->isEmpty()) {
            foreach ($integration_source_fields as $field) {
                $mapped_field = $this->field_map_service->getMappedByField($field);
                $parameter[$field['source_field']] = $this->data_conversion_service->getConversionData($field, $mapped_field);
            }
        }

        // set the data for QUERY BUILDER that will be used in PRE updateSourceData
        $data = [
            'parameter' => $parameter,
            'status' => json_decode(json_encode($machship_status), true),
            'consignment' => $response ? json_decode(json_encode($response), true) : null,
        ];

        $this->platform->setData($data);

        // set the current integration record that will be used in PRE updateSourceData
        $this->platform->setCurrentRecord($record);

        // update the source data
        return $this->platform->updateSourceData($record, $parameter, $response);
    }
}
