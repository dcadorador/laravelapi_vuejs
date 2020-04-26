<?php

namespace App\Services\Platforms\Myob;

use App\Models\DebugLogs;
use App\Services\Platforms\Myob\CustomFunctions\integration_5_custom_functions;

use App\Helpers\Helper;
use App\Models\IntegrationRecords;
use App\Services\Platforms\PlatformAbstract;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\ParameterBag;
use App\Libraries\Myob\Myob;
use App\Traits\LoggerTrait;
use Exception;

class MyobService extends PlatformAbstract
{
    use integration_5_custom_functions;

    use LoggerTrait;
    use MyobDefaultMaps;
    use MyobDefaultSourceFields;
    use MyobTests;

    private $myob;
    private $config;

    const TAG = "[MYOB_SERVICE]";
    const UPDATE_SHIPMENT = 'UPDATE_SHIPMENT';
    const UPDATE_SHIPMENT_STATUS = 'UPDATE_SHIPMENT_STATUS';

    /**
     * Platform constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
    * Override init func for each integration platform will
    * have its own interpretation of its configuration
    * @param array $config [Optional] configurations
    */
    protected function init(Array $config = [])
    {
        // Initialization
        if (empty($config)) {
            $this->errorLog('config is empty');
            return;
        }

        $myobConfig = [
            'uri'               => $config['MYOB_URL'],
            'version'           => $config['MYOB_VERSION'],
            'name'              => $config['MYOB_USER'],
            'password'          => $config['MYOB_PASSWORD']
        ];
        $this->config = $myobConfig;

        try {
            $this->myob = new Myob($myobConfig['uri'], $myobConfig['version']);
            $this->onLogin();
        } catch (Exception $e) {
            $this->errorLog('configs', $config);
            $this->errorLog('fail during initialization', $e->getMessage());
        }
    }

    /**
    * Get integrations data that will be process
    * @return Array of objects data
    */
    protected function get()
    {
        $filters = $this->integration->integrationSourceFilters;

        // Custom filter for CreatedDateTime using period_start
        $custom_filter = new \stdClass();
        $custom_filter->filter_key = '$filter_CreatedDateTime';
        $custom_filter->filter_value = "gt " . $this->integration_sync->period_start->format('Y-m-d H:i:s');

        $filters[] = $custom_filter;

        $query = $this->processFilter($filters);

        // if query is still empty then we need to stop that!
        if (empty($query)) {
            return [];
        }

        // we need to build query manually here so MYOB api works
        $query_params = [];
        foreach ($query as $query_key => $query_value) {
            $query_params[] = $query_key . "=" . urlencode($query_value);
        }

        $params = implode('&', $query_params);
        // \Log::info('params is : '. $params);

        return $this->myob->shipment()->shipmentDetails($params);
    }

    /**
     * Override this for each integration platform will
     * have its own source id pattern
     * @param Array $data the data from integration platform function get
     * @return Integer source id
     * @throws Exception
     */
    protected function getSourceId($data)
    {

        if (empty($data) || empty($data['ShipmentNbr'])) {
            throw new Exception('Could not get source id, because data is empty!');
        }

        return $data['ShipmentNbr']['value'];
    }

    /**
     * Override mapping functions so each integration platform will have their way
     * interpreting customfield
     * @param string $source_field The source field
     * @param array $source_data
     * @return string
     */
    public function getCustomfield(string $source_field, array $source_data)
    {
        return '';
    }



    /**
    * Gets the order items.
    * @param array $source_data The source data
    * @return     array   item orders
    */
    protected function getItems(array $source_data)
    {
        $this->items = $source_data['Details'];
        $details = [];
        foreach ($this->items as $item) {
            // this way, MYOB mapping will work
            $details[] = ['Details' => $item];
        }
        return $details;
    }

    /**
     * @param $id
     * @param array $params - additional parameters
     * @return array | object
     */
    public function findBySourceId($id, $params = [])
    {

        $filters = $this->integration->integrationSourceFilters;

        // append the shipment ID
        foreach ($filters as $key => $filter) {
            // remove date parameter for the shipment
            if (preg_match('/Date/', $filter->filter_key) ||
                preg_match('/\$filter/', $filter->filter_key)
            ) {
                unset($filters[$key]);
            }
        }

        // process filters
        $query = $this->processFilter($filters);

        // if query is still empty then we need to stop that!
        if (empty($query)) {
            return [];
        }

        // we need to build query manually here so MYOB api works
        $query_params = [];
        foreach ($query as $query_key => $query_value) {
            $query_params[] = $query_key . "=" . urlencode($query_value);
        }

        $final_params = implode('&', $query_params);

        return $this->myob->shipment()->shipmentByShipmentNbr($id, $final_params);
    }

    /**
     * @param $start
     * @param $end
     * @return array
     */
    protected function getByDateRange($start, $end)
    {
        $filters = $this->integration->integrationSourceFilters;

        // append the shipment ID
        foreach ($filters as $filter) {
            if (preg_match('/Date/', $filter->filter_key)) {
                $value = Carbon::parse($start)->setTimezone('UTC')->format('Y-m-d\TH:i:s.000\Z');
                $filter->filter_value = $value;
            }
        }

        // process filters
        $query = $this->processFilter($filters, true);

        // if query is still empty then we need to stop that!
        if (empty($query)) {
            return [];
        }

        // we need to build query manually here so MYOB api works
        $query_params = [];
        foreach ($query as $query_key => $query_value) {
            $query_params[] = $query_key . "=" . urlencode($query_value);
        }

        $final_params = implode('&', $query_params);
        $filtered_shipments = [];
        $shipments = $this->myob->shipment()->shipmentDetails($final_params);

        foreach ($shipments as $shipment) {
            $shipment = json_decode(json_encode($shipment), true);

            // check if the create date is within the end range
            $carbon_end = Carbon::parse($end);
            $created_date = Carbon::parse($shipment['CreatedDateTime']['value']);

            if ($created_date->gt($carbon_end)) {
                continue;
            }

            $filtered_shipments[] = $shipment;
        }

        return $filtered_shipments;
    }


    /**
    * Override this so each integration platform will
    * have its own default configuration meta keys
    * @return array
    */
    public static function defaultMeta()
    {
        return [
            'MYOB_URL' => '',
            'MYOB_VERSION' => '',
            'MYOB_USER' => '',
            'MYOB_PASSWORD' => '',
        ];
    }

    /**
     * Override this so each integration platform
     * has its own way of updating their source integration data
     * Parameter Bag is used as a standard for data formatting
     * @param $source
     * @param $parameters // this is the mapped field from the parameters
     * @param null $consignment
     * @return null
     */
    protected function updateSourceData($source, $parameters, $consignment = null)
    {

        // should pass the consignment for reference and remove unnecessary/repeated machship calls
        /*$description = '';
        $description .= Helper::getValueOrNull($consignment->companyCarrierAccount, 'name') .
            ' - ' . Helper::getValueOrNull($consignment->carrierService, 'name') . ".";

        $source_raw = $source['source_data'];
        //$shipment_number = $source_raw['ShipmentNbr']['value'];
        $details = $source_raw['Details'][0];
        $shipment_number = $source_raw['ShipmentNbr']['value'];

        // PUT https://domainappliances.myobadvanced.com/entity/Default/18.200.001/Shipment/?$expand=Packages&$filter=ShipmentNbr eq '(Shipment number from original Shipment)'
        $filter = urlencode('ShipmentNbr eq \''. $shipment_number .'\'');
        $expand = urlencode('Packages');
        $params = '$filter=' . $filter . '&$expand=' . $expand;
        $data = [
            'CustomerID' => $source_raw['CustomerID'],
            'WarehouseID' => array_key_exists('WarehouseID', $details) ? $details['WarehouseID'] : null,
            'Status' => [ 'value' => 'Confirmed' ],
            'Packages' => [
                [
                    'rowNumber' => ['value' => 1], // fixed
                    'BoxID' => ['value' => 'TRACKING'], // fixed
                    'Confirmed' => ['value' => true], // fixed
                    // 'CustomRefNbr1' => ['value' => Helper::getValueOrNull($consignment->companyCarrierAccount, 'id')], // cannote/carrier ID
                    'CustomRefNbr1' => ['value' => Helper::getValueOrNull($consignment, 'consignmentNumber') ], // cannote/carrier ID
                    'Description' => ['value' => $description ], // carrier + service
                    //'TrackingNbr' => ['value' => Helper::getValueOrNull($consignment, 'consignmentNumber') ], // MS Number
                    'TrackingNbr' => ['value' => Helper::getValueOrNull($consignment, 'carrierConsignmentId') ], // MS Number
                ]
            ]
        ];*/
        $this->authChecker();
        $result = $this->getCustomFunctionResult();
        $data = $result['data'];
        $params = $result['parameters'];
        $status_data = $result['status_data'];
        // $this->infoLog('MYOB Api Update', 'Params: '. json_encode($data) . ' ' . json_encode($params));
        $result = $this->myob->shipment()->updateShipment($data, $params);
        $this->createSyncLog(DebugLogs::STEP_WF_5 . ' - ' . self::UPDATE_SHIPMENT, $data, $result);
        // $this->infoLog('MYOB Api Update', 'Result: '. json_encode($result));
        /*$status_data = [
            'entity' => [
                'Type' => [
                    'value' => 'Shipment'
                ],
                'ShipmentNbr' => [
                    'value' => $source_raw['ShipmentNbr']['value']
                ]
            ]
        ];*/
        // $this->infoLog('MYOB Api Update Shipment Status', 'Params: '. json_encode($status_data));
        $status_result = $this->myob->shipment()->updateShipmentStatus($status_data);
        $this->createSyncLog(DebugLogs::STEP_WF_5 . ' - ' . self::UPDATE_SHIPMENT_STATUS, $status_data, $status_result);
        // $this->infoLog('MYOB Api Update Shipment Status', 'Result: '. json_encode($status_result));
        return null;
    }

    public static function defaultFilters()
    {
        return [];
    }

    // override this function to end record after sync to machship
    protected function getIntegrationRecordStatusAfterSyncToMachship()
    {
        return IntegrationRecords::RECORD_STATUS_PENDING_UPDATE;
    }

    /**
     * This is an auth checker for the MYOB service
     * @return bool|null
     */
    public function authChecker()
    {
        if (!$this->myob->checkIfLoggedIn()) {
            $this->onLogin();
            return true;
        }

        return null;
    }

    // perform login and check if configs are correct
    public function onLogin()
    {
        $name = $this->config['name'];
        $password = $this->config['password'];
        $this->myob->auth()->login($name, $password);
    }

    // perform logout
    public function onLogout()
    {
        $this->myob->auth()->logout($this->config['name'], $this->config['password']);
    }

    /**
     * @param $filters
     * @param bool $date_range
     * @return array
     */
    private function processFilter($filters, $date_range = false)
    {
        $query = [];

        foreach ($filters as $filter) {
            // group by $filter, $expand, $select
            if ($filter->filter_key === '$filter' ||
                $filter->filter_key === '$expand' ||
                $filter->filter_key === '$select'
            ) {
                $query[$filter->filter_key] = $filter->filter_value;
            } else {
                // additional conditions
                if (strpos($filter->filter_key, '$filter_') !== false) {
                    $keys = explode("_", $filter->filter_key);

                    // no need to process if this is not existing
                    if (empty($query[$keys[0]])) {
                        continue;
                    }

                    // Set filter name eg 'CreatedDateTime'
                    $new_filter = $keys[1];

                    // added date range format
                    if (strpos(strtolower($new_filter), 'date') !== false && !$date_range) {
                        $filter_condition = substr($filter->filter_value, 0, 2);
                        $filter_value = substr($filter->filter_value, 2);
                        // $date = date('Y-m-d\TH:i:s.000\Z', strtotime($filter_value));
                        $date = Carbon::parse($filter_value)->setTimezone('UTC')->format('Y-m-d\TH:i:s.000\Z');
                        // eg. CreatedDateTime gt datetimeoffset'2020-04-04T00:00:00.000'
                        $new_filter .= " $filter_condition datetimeoffset'$date'";
                    } else {
                        $date_range ?
                            $new_filter .= " gt datetimeoffset'" . $filter->filter_value ."'" :
                            $new_filter .= " " . $filter->filter_value ;
                    }

                    $query[$keys[0]] .= " and $new_filter";
                }
            }
        }

        return $query;
    }
}
