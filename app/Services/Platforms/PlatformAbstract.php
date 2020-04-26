<?php
namespace App\Services\Platforms;

use App\Libraries\Machship\Machship;
use App\Models\Integration;
use App\Models\IntegrationSyncs;
use App\Models\IntegrationRecords;
use App\Models\SyncLogs;
use App\Repositories\SyncLogsRepository;
use Carbon\Carbon;
use Log;
use Closure;
use Symfony\Component\HttpFoundation\ParameterBag;
use App\Services\MachshipCacheService;

abstract class PlatformAbstract
{

    protected $integration;
    protected $integration_sync;
    protected $integration_id = 0;
    protected $machship = null;
    protected $data; // source data
    protected $items; // processed items from source data
    protected $machship_payload = null;
    protected $machship_cache_service = null;
    protected $current_record;

    public function __construct()
    {
        // TODO something here
    }

    /**
     * @param Integration $integration
     */
    public function setIntegration(Integration $integration)
    {
        $this->integration = $integration;
        $this->integration_id = $integration->id;
    }

    /**
     * Sets the integration sync currently processed
     * @param      \App\Models\IntegrationSyncs  $integration_sync  The integration sync model
     */
    public function setIntegrationSync(IntegrationSyncs $integration_sync)
    {
        $this->integration_sync = $integration_sync;
    }

    /**
     * Gets the integration identifier.
     * @return     integer  The integration identifier.
     */
    public function getIntegrationId()
    {
        return $this->integration_id;
    }

    /**
     * @param Machship $machship
     */
    public function setMachship(Machship $machship)
    {
        $this->machship = $machship;
    }

    /**
     * Sets the machship cache.
     * @param      MachshipCacheService  $machship_cache_service  The machship cache service
     */
    public function setMachshipCache(MachshipCacheService $machship_cache_service)
    {
        $this->machship_cache_service = $machship_cache_service;
    }

    /**
     * Sets the machship payload so it can be use later
     * @param      array  $machship_payload  The machship payload
     */
    public function setMachshipPayload(array $machship_payload)
    {
        $this->machship_payload = $machship_payload;
    }

    /*
     * @param $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param $result
     */
    public function setCustomFunctionResult($result)
    {
        $this->custom_function_result = $result;
    }

    /**
     * @return mixed
     */
    public function getCustomFunctionResult()
    {
        return $this->custom_function_result;
    }

    /**
     * @return mixed
     */
    public function getCurrentRecord()
    {
        return $this->current_record;
    }

    /**
     * @param mixed $current_record
     */
    public function setCurrentRecord($current_record)
    {
        $this->current_record = $current_record;
    }

    /**
     * This function is used to call a protected functions from abstract
     * to have a pre and post call of function in particular integration id
     * @param string $name The function name
     * @param array $arguments The function arguments
     *
     * @return     any     depends on the specific function called
     * @throws \Exception
     */
    public function __call($name, $arguments)
    {
        // Log::info('__call execute : ' . $name . ' testing : ' . json_encode($arguments));

        // get integration id
        $id = $this->integration_id;

        // sets pre and post function name
        $pname = ucfirst($name);
        $pre = "integration_{$id}_pre$pname";
        $post = "integration_{$id}_post$pname";

        // Execute pre function here if exist
        if (method_exists($this, $pre)) {
            // Log::info("[PLATFORM][PRE] of $pre");
            $this->{$pre}();
        }

        if (method_exists($this, $name)) {
            $from = Closure::fromCallable(array($this, $name));
            $func = Closure::bind($from, $this);
            $result = $func(...$arguments);
        } else {
            throw new \Exception('Function ' . $name . ' does not exist in the integrated platform!');
        }

        // Execute post function here
        // Execute pre function here if exist
        if (method_exists($this, $post)) {
            // Log::info("[PLATFORM][PRE] of $post");
            $post_result = $this->{$post}(...$arguments);

            // if a result got a return from the post
            if (is_null($post_result) === false) {
                // override result
                $result = $post_result;
            }
        }

        return $result;
    }

    /*
     * Override init func for each integration platform will
     * have its own interpretation of its configuration
     * @param array $config [Optional] configurations
     */
    abstract protected function init(Array $config = []);

    /**
     * Override this for each integration platform will
     * have its own source id pattern
     * @param $start - date start
     * @param $end - date end
     * @return array
     */
    abstract protected function getByDateRange($start, $end);


    /**
     * Override this for each integration platform will
     * have its own way of find by id api call
     * @param $id
     * @param array $params - this can be any additional parameters native for the platform
     * @return array
     */
    abstract protected function findBySourceId($id, $params = []);

    /**
     * Get integrations data that will be process
     * @return Array of objects data
     */
    abstract protected function get();

    /**
     * Override this for each integration platform will
     * have its own source id pattern
     * @param Array $data the data from integration platform function get
     * @return Integer source id
     */
    abstract protected function getSourceId($data);

    /**
     * Override mapping functions so each integration platform will have their way
     * interpreting customfield
     * @param string $source_field The source field
     * @param array $source_data
     */
    abstract public function getCustomfield(string $source_field, array $source_data);


    /**
     * Gets the order items.
     * @param array $source_data The source data
     * @return     array   item orders
     */
    abstract protected function getItems(array $source_data);

    /**
     * Override this so each integration platform will
     * have its own default mapper fields
     * @return Array maps
     */
    abstract public static function defaultMaps();

    /**
     * Override this so each integration platform will
     * have its own default configuration meta keys
     * @return Array meta
     */
    abstract public static function defaultMeta();

    /**
     * Override this so each integration platform has
     * its own default source fields
     */
    abstract public static function defaultSourceFields();

    /**
     * Override this so each integration platform
     * has its own way of updating their source integration data
     * Parameter Bag is used as a standard for data formatting
     * @param $source
     * @param $parameters
     * @param null $consignment
     */
    abstract protected function updateSourceData($source, $parameters, $consignment = null);

    /**
     * Gets the consignment status in integration
     * @return String consignment type
     */
    protected function getConsignmentType()
    {
        if ($this->integration) {
            return $this->integration->master_consignment_type;
        }

        return '';
    }

    /**
     * @param null $status
     * @return string|null
     */
    protected function getIntegrationRecordStatus($status = null)
    {
        if ($status) {
            return $status;
        }

        return IntegrationRecords::RECORD_STATUS_PENDING_MACHSHIP;
    }

    /**
     * Gets the default integration record status after synchronize to machship.
     * @return     String  The integration record status.
     */
    protected function getIntegrationRecordStatusAfterSyncToMachship()
    {
        return IntegrationRecords::RECORD_STATUS_PENDING_UPDATE;
    }

    /**
     * @return string
     */
    protected function getIntegrationRecordProcessAfter()
    {
        return Carbon::now()->subMinute()->toDateTimeString();
    }

    // This is a shortcut function will write a debug log
    private function writeDebugLog($step, $title, $data)
    {
        $this->debugLog($step, $title, [
            "integration_id" => $this->integration->id,
            "integration_sync_id" => $this->integration_sync->id,
            "data" => $data
        ]);
    }

    // General pre-process function that executes first in main sync process in Process class
    public function preProcess()
    {
        // Nothing to do by default
    }

    // General post-process function that executes last in main sync process in Process class
    public function postProcess()
    {
        // Nothing to do by default
    }

    /**
     * @param $step
     * @param $payload
     * @param $result
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Support\Collection|mixed
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function createSyncLog($step, $payload, $result)
    {
        return SyncLogs::create([
            'integration_id' => $this->integration->id,
            'integration_record_id' => $this->current_record->id,
            'integration_type' => $this->integration->integrationType->name,
            'step' => $step,
            'data' => $payload,
            'result' => $result,
        ]);
    }
}
