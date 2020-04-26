<?php
namespace App\Services\Platforms\Netsuite;

use App\Services\Platforms\Netsuite\CustomFunctions\integration_3_custom_functions;

use App\Services\Platforms\Netsuite\CustomFunctions\integration_2_custom_functions;

use App\Services\Platforms\Netsuite\CustomFunctions\integration_1_custom_functions;
use App\Models\DebugLogs;
use App\Models\IntegrationRecords;
use App\Libraries\Machship\Machship;
use App\Libraries\Netsuite\Netsuite;
use App\Libraries\Netsuite\NetsuiteHelpers\NetSuiteDataHelper;
use App\Libraries\Netsuite\NetsuiteHelpers\NetSuiteConstantHelper;
use App\Libraries\Netsuite\NetsuiteHelpers\NetsuiteItemFulfillmentHelper;
use App\Libraries\Netsuite\NetSuiteObjects\ItemFulfillmentObject;
use App\Services\Platforms\PlatformAbstract;
use League\Fractal\Resource\Item;
use Symfony\Component\HttpFoundation\ParameterBag;
use App\Helpers\ArrayHelper;
use App\Helpers\Helper;
use App\Libraries\Netsuite\NetSuiteObjects\CustomerObject;
use App\Libraries\Netsuite\NetSuiteObjects\SalesOrderObject;
use App\Traits\LoggerTrait;

/**
 * This class describes a netsuite service.
 * Note this extends to PlatformAbstract
 * Must implement all abstract functions
 */
class NetsuiteService extends PlatformAbstract
{
    use integration_3_custom_functions;
    use integration_2_custom_functions;
    use integration_1_custom_functions;

    use NetsuiteDefaultMaps;
    use NetsuiteSourceFields;
    use NetsuiteDefaultFilters;
    use NetsuiteDefaultMeta;
    use NetsuiteTests;
    use LoggerTrait;

    /**
     * @var
     */
    protected $netsuite;

    /**
     * @var
     */
    protected $data;

    /**
     * @var
     */
    protected $custom_function_result;

    /**
     * @var Netsuite
     */
    private $netsuite_object;


    protected $netsuite_query = [];

    const TAG = "[NETSUITE_SERVICE]";


    /**
     * NetsuiteService constructor.
     */
    public function __construct()
    {
        // TODO
        parent::__construct();
        $this->infoLog('init');
    }

    // Override init func for each integration platform will have its own interpretation of its configuration
    protected function init(Array $config = [])
    {
        if (empty($config)) {
            $this->errorLog('config is empty');
            return;
        }

        $nsConfig = [
            'endpoint'       => $config['NETSUITE_ENDPOINT'],
            'host'           => $config['NETSUITE_WEBSERVICES_HOST'],
            'account'        => $config['NETSUITE_ACCOUNT'],
            'consumerKey'    => $config['NETSUITE_CONSUMER_KEY'],
            'consumerSecret' => $config['NETSUITE_CONSUMER_SECRET'],
            'token'          => $config['NETSUITE_TOKEN'],
            'tokenSecret'    => $config['NETSUITE_TOKEN_SECRET'],
            'app_id'         => $config['NETSUITE_APP_ID'],
        ];

        try {
            $this->netsuite = new Netsuite($nsConfig);
            //$this->netsuite_object = $this->netsuite->setObject(new ItemFulfillmentObject());
            $this->netsuite_object = $this->netsuite->setObject($this->setNetsuiteObjectFromMeta());
            $this->infoLog('object set success');
        } catch (\Exception $e) {
            // TODO email here
            $this->errorLog('configs', $config);
            $this->errorLog('fail during initialization', $e->getMessage());
        }
    }

    // This function will prepare netsuite's source filtering for its query builder
    private function preGet()
    {
        // fetch filters here
        $filters = $this->integration->integrationSourceFilters;
        $this->netsuite_query = [];
        // we need to group filters for netsuite
        $ns_filter = [];
        foreach ($filters as $filter) {
            if (empty($filter->integration_source_filter_id)) {
                $ns_filter[$filter->id][] = $filter;
            } else {
                $parent_id = $filter->integration_source_filter_id;
                $ns_filter[$parent_id][] = $filter;
            }
        }

        // iterate each filters
        foreach ($ns_filter as $filter_options) {
            $query = [
                'field' => '',
                'operator' => '',
                'search_value' => '',
                'record_reference' => null
            ];
            // needs to iterate option key-value
            foreach ($filter_options as $filter_option) {
                $query[$filter_option->filter_key] = $filter_option->filter_value;
            }
            $this->netsuite_query[] = $query;
        }

        // add period start for date created filter query
        $period_start = is_string($this->integration_sync->period_start) ? $this->integration_sync->period_start : $this->integration_sync->period_start->format('Y-m-d H:i:s');
        $this->netsuite_query[] = [
            'field' => 'dateCreated',
            'operator' => 'after',
            'search_value' => $period_start,
            'record_reference' => null
        ];
    }

    /**
     * @return Array|null Platform Source data
     */
    protected function get()
    {
        $this->preGet();
        $result = $this->netsuite_object->get($this->netsuite_query);

        // we need to store this
        $this->data = $result;

        if (is_array($result)) {
            return $result;
        }

        $this->errorLog('get error', $result);
        return '';
    }

    /**
     * This function to set the netsuite object based on meta and not hard-coded
     * @return CustomerObject|ItemFulfillmentObject|SalesOrderObject
     */
    public function setNetsuiteObjectFromMeta()
    {
        $metas = collect($this->integration->integrationMeta()->get());
        $object_meta = $metas->where('meta_key', Helper::NETSUITE_OBJECT_TYPE_META)->first();

        return $this->getNetsuiteObjectType($object_meta->meta_value);
    }

    /**
     * This will return this class with a new netsuite object set manually
     * @param $object
     * @return $this
     */
    public function setNewNetsuiteObject($object)
    {
        $netsuite_object = $this->getNetsuiteObjectType($object);
        $this->netsuite_object->setObject($netsuite_object);
        return $this;
    }

    /**
     * @param $id
     * @param array $params - additional parameters
     * @return array | object
     */
    protected function findBySourceId($id, $params = [])
    {
        return $this->netsuite_object->find($id);
    }

    /**
     * @param $start
     * @param $end
     * @return array
     */
    protected function getByDateRange($start, $end)
    {
        $this->preGet();
        return $this->netsuite_object->findByDateRange($start, $end, $this->netsuite_query);
    }

    /**
     * @param $object_type
     * @return CustomerObject|ItemFulfillmentObject|SalesOrderObject
     */
    public function getNetsuiteObjectType($object_type)
    {

        switch ($object_type) {
            case NetSuiteConstantHelper::NETSUITE_SALES_ORDER:
                $object = new SalesOrderObject();
                break;
            case NetSuiteConstantHelper::NETSUITE_CUSTOMER:
                $object = new CustomerObject();
                break;
            case NetSuiteConstantHelper::NETSUITE_ITEM_FULFILLMENT:
            default:
                $object = new ItemFulfillmentObject();
                break;
        }

        return $object;
    }

    /**
     * Override this for each integration platform will have its own source id pattern
     * @param Array $data the data from integration platform function get
     * @return Integer source id
     */
    protected function getSourceId($data)
    {
        return isset($data['internalId']) ? $data['internalId'] : null;
    }

    public function getCustomfield(string $source_field, array $source_data)
    {
        $field = "customFieldList.customField.$source_field";
        $mapped = ArrayHelper::customFieldMapper($field, $source_data);
        return is_array($mapped) ? $mapped['value'] : '';
    }

    /**
     * Let Netsuite handle its own fetch items
     *
     * @param array $source_data The source data
     * @param object|null $ms_company_items
     * @return array
     */
    protected function getItems(array $source_data)
    {
        $this->items = ArrayHelper::arrayMapper('itemList.item', $source_data);
        return $this->items;
    }

    /**
     * NOTE: This is how a NETSUITE update parameters should be FORMATTED, if there is a need to update source INTEGRATION DATA
     *       A custom function should be created to handle the formatting, getting the values from the FIELD MAPPER and/or VALUE LOOKUP TABLE.
     *
     *       Possible values for update_type:
     *           - FIELD : which would indicate the updating data would be the OBJECT (SalesOrder, ItemFulfillment) FIELD
     *           - CUSTOM FIELD : which would indicate the updating data would be a CUSTOM FIELD
     *
     *       $parameterBag should be an instance of the ParameterBag class since it is the standard of our Netsuite Library, the query function should be handled by the custom functions
     *
     *    Update queries should be built like:
     *    $parameters = [
     *      [
     *          'update_type' => 'FIELD',
     *          'parameter'   => Symfony\Component\HttpFoundation\ParameterBag $parameterBag
     *      ],
     *      [
     *          'update_type' => 'FIELD',
     *          'parameter'   => Symfony\Component\HttpFoundation\ParameterBag $parameterBag
     *      ],
     *    ];
     * @param $source
     * @param $parameters
     * @param null $response
     * @return null
     */
    protected function updateSourceData($source, $parameters, $response = null)
    {
        $parameters = $this->getCustomFunctionResult();

        if (!count($parameters)) {
            $this->errorLog(DebugLogs::STEP_WF_5, 'No Parameters found.');
            return null;
        }

        foreach ($parameters as $parameter) {
            try {
                switch ($parameter['update_type']) {
                    case 'FIELD':
                        // $result = $this->netsuite_object->update($parameter['parameter']);
                        $this->infoLog('Netsuite Object Field Update Result for Integration Record: '. $source->id . ' - ' . json_encode($result));
                        break;
                    case 'CUSTOM FIELD':
                        // $result = $this->netsuite_object->updateCustomFields($parameter['parameter']);
                        $this->infoLog('Netsuite Custom Field Update Result for Integration Record: '. $source->id . ' - ' . json_encode($result));
                        break;
                }
            } catch (\Exception $e) {
                $this->infoLog('Netsuite Source Update Result for Integration Record: '. $source->id . ' - Error - ' . json_encode($result));
                continue;
            }
        }

        return null;
    }

    /**
     * @param $id
     * @return Object | null
     */
    public function findByTransaction($id)
    {
        return $this->netsuite_object->findByTransaction($id);
    }
}
