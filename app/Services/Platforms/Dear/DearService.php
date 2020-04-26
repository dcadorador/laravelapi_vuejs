<?php

namespace App\Services\Platforms\Dear;

use App\Services\Platforms\DEAR\CustomFunctions\integration_4_custom_functions;

use App\Services\Platforms\PlatformAbstract;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\ParameterBag;
use App\Models\IntegrationRecords;
use App\Libraries\Dear\Dear;
use App\Traits\LoggerTrait;
use App\Helpers\ArrayHelper;
use Exception;

class DearService extends PlatformAbstract
{
    use integration_4_custom_functions;
    use LoggerTrait;

    private $dear;
    private $dear_sales;
    private $dear_fullfilments;

    const TAG = "[DEAR_SERVICE]";
    const UPDATE_HOOK = 'UPDATE_HOOK';


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
     * @throws Exception
     */
    protected function init(Array $config = [])
    {


        // we need to validate configuration first here
        if (empty($config) ||
            empty($config['DEAR_ACCOUNT_ID']) ||
            empty($config['DEAR_APPLICATION_KEY'])
        ) {
            throw new Exception('Invalid DEAR configurations');
        }

        $account_id = $config['DEAR_ACCOUNT_ID'];
        $app_key = $config['DEAR_APPLICATION_KEY'];

        $this->dear = new Dear($account_id, $app_key);
    }

    /**
     * Get integrations data that will be process
     * @return Array of objects data
     * @throws Exception
     */
    protected function get()
    {
        $filters = $this->integration->integrationSourceFilters;

        $query = [];
        foreach ($filters as $filter) {
            $query[$filter->filter_key] = $filter->filter_value;
        }

        // Important! now we have to apply our period start here
        $period_start = $this->integration_sync->period_start;
        // ensure we use UTC
        $period_start->setTimezone(new \DateTimeZone('UTC'));
        // modify datetime and format to ISO 8601
        $query['UpdatedSince'] = $period_start->format('c');

        // get all salelist base from the filter
        $this->dear_sales = $this->dear->saleList()->all($query);

        // we need to fetch each fullfillment here
        if (empty($this->dear_sales)) {
            $this->infoLog('No dear sales');
            return [];
        }

        foreach ($this->dear_sales as $sale) {
            // validate params
            if (empty($sale['SaleID'])) {
                $this->infoLog('Sale id is empty in dear sale : ', $sale);
                continue;
            }

            $sale_fulfilment = $this->dear->saleFulfilment()->find(
                $sale['SaleID']
            );

            foreach ($sale_fulfilment['Fulfilments'] as $fulfilment) {
                $item = [
                    'SaleList' => $sale,
                    'Fulfilments' => $fulfilment
                ];

                $this->dear_fullfilments[] = $item;
            }
        }

        return $this->dear_fullfilments;
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

        if (isset($raw_record_data['SaleList']) &&
            isset($raw_record_data['Fulfilments']) &&
            isset($raw_record_data['SaleList']['OrderNumber']) &&
            isset($raw_record_data['Fulfilments']['FulfillmentNumber'])
        ) {
            throw new Exception('No OrderNumber or FulfillmentNumber : ' . json_encode($data));
        }

        return $data['SaleList']['OrderNumber'] . '-' . $data['Fulfilments']['FulfillmentNumber'];
    }

    /**
     * Override mapping functions so each integration platform will have their way
     * interpreting customfield
     * @param string $source_field The source field
     * @param array $source_data
     * @return null
     */
    public function getCustomfield(string $source_field, array $source_data)
    {
        return null;
    }


    /**
    * Gets the order items.
    * @param array $source_data The source data
    * @param object $ms_company_items Item data from machship
    * @return     array   item orders
    */
    protected function getItems(array $source_data, object $ms_company_items = null)
    {
        return null;
    }

    /**
    * Override this so each integration platform will
    * have its own default mapper fields
    * @return Array maps
    */
    public static function defaultMaps()
    {
    }

    /**
    * Override this so each integration platform will
    * have its own default configuration meta keys
    * @return Array meta
    */
    public static function defaultMeta()
    {
        return [
            "DEAR_ACCOUNT_ID" => "",
            "DEAR_APPLICATION_KEY" => "",
        ];
    }

    /**
    * Override this so each integration platform has
    * its own default source fields
    */
    public static function defaultSourceFields()
    {
        return [
            "SaleID",
            "TaskID",
            "FulfillmentNumber",
            "LinkedInvoiceNumber",
            "FulFilmentStatus",
            "Pick.Status",
            "Pick.Lines",
            "Pick.Lines.ProductID",
            "Pick.Lines.SKU",
            "Pick.Lines.Name",
            "Pick.Lines.Location",
            "Pick.Lines.LocationID",
            "Pick.Lines.Quantity",
            "Pick.Lines.BatchSN",
            "Pick.Lines.ExpiryDate",
            "Pack",
            "Pack.Status",
            "Pack.Lines",
            "Pack.Lines.ProductID",
            "Pack.Lines.SKU",
            "Pack.Lines.Name",
            "Pack.Lines.Location",
            "Pack.Lines.LocationID",
            "Pack.Lines.Box",
            "Pack.Lines.Quantity",
            "Pack.Lines.BatchSN",
            "Pack.Lines.ExpiryDate",
            "Pack.Lines.NonInventory",
            "Pack.Lines.WarrantyRegistrationNumber",
            "Ship",
            "Ship.Status",
            "Ship.RequireBy",
            "Ship.ShippingAddress",
            "Ship.ShippingAddress.DisplayAddressLine1",
            "Ship.ShippingAddress.DisplayAddressLine2",
            "Ship.ShippingAddress.Line1",
            "Ship.ShippingAddress.Line2",
            "Ship.ShippingAddress.City",
            "Ship.ShippingAddress.State",
            "Ship.ShippingAddress.Postcode",
            "Ship.ShippingAddress.Country",
            "Ship.ShippingAddress.Company",
            "Ship.ShippingAddress.Contact",
            "Ship.ShippingAddress.ShipToOther",
            "Ship.Lines",
            "Ship.Lines.ID",
            "Ship.Lines.ShipmentDate",
            "Ship.Lines.Carrier",
            "Ship.Lines.Boxes",
            "Ship.Lines.TrackingNumber",
            "Ship.Lines.TrackingURL",
            "Ship.Lines.IsShipped",
        ];
    }

    /**
     * Override this so each integration platform
     * has its own way of updating their source integration data
     * Parameter Bag is used as a standard for data formatting
     * @param $source
     * @param $parameters
     * @param null $consignment
     */
    protected function updateSourceData($source, $parameters, $consignment = null)
    {
        //$data = $this->getCustomFunctionResult();
        //$this->infoLog('Update Source DEAR', 'Parameters for DEAR Update source: ' . json_encode($data));
        // todo: should update the source integration DEAR
    }


    // override this function to end record after sync to machship
    protected function getIntegrationRecordStatusAfterSyncToMachship()
    {
        return IntegrationRecords::RECORD_STATUS_PENDING_UPDATE;
    }

    /**
     * @param $start
     * @param $end
     * @return array
     */
    public function getByDateRange($start, $end)
    {
        $query = [];

        $filters = $this->integration->integrationSourceFilters;
        // append the shipment ID
        foreach ($filters as $key => $filter) {
            $value = $filter->filter_value;

            if (preg_match('/Updated/', $filter->filter_key) ||
                preg_match('/Created/', $filter->filter_key)
            ) {
                unset($filters[$key]);
            }

            $query[$filter->filter_key] = $value;
        }

        $query['CreatedSince'] = Carbon::parse($start)->toIso8601String();
        // dd($query);
        $this->dear_sales = $this->dear->saleList()->all($query);

        // we need to fetch each fullfillment here
        if (empty($this->dear_sales)) {
            $this->infoLog('No dear sales');
            return [];
        }

        foreach ($this->dear_sales as $sale) {
            // validate params
            if (empty($sale['SaleID'])) {
                $this->infoLog('Sale id is empty in dear sale : ', $sale);
                continue;
            }

            // check if the order date is past the end time since there is no between date
            $end_date = Carbon::parse($end);
            $updated_date = Carbon::parse($sale['Updated']);

            if ($updated_date->gte($end_date)) {
                // skip and continue;
                continue;
            }

            $sale_fulfilment = $this->dear->saleFulfilment()->find(
                $sale['SaleID']
            );

            foreach ($sale_fulfilment['Fulfilments'] as $fulfilment) {
                $item = [
                    'SaleList' => $sale,
                    'Fulfilments' => $fulfilment
                ];

                $this->dear_fullfilments[] = $item;
            }
        }

        return $this->dear_fullfilments;
    }

    /**
     * @param $id
     * @param array $params
     * @return array
     */
    public function findBySourceId($id, $params = [])
    {
        $id_split = explode("-", $id);
        $fulfilment_id = array_pop($id_split);
        $id_hash = ArrayHelper::directMapper('SaleList.SaleID', $this->data);

        // get sale
        $sale = $this->dear->sale()->find($id_hash);

        $this->dear_fullfilments['SaleList'] = $sale;
        $this->dear_fullfilments['Fulfilments'] = "";
        $index = array_search($fulfilment_id, array_column($sale['Fulfilments'], 'FulfillmentNumber'));
        if (is_numeric($index) && $index >= 0) {
            $this->dear_fullfilments['Fulfilments'] = $sale['Fulfilments'][$index];
        }

        return $this->dear_fullfilments;
    }
}
