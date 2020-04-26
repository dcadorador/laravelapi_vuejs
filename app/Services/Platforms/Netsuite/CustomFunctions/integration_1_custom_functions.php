<?php
namespace App\Services\Platforms\Netsuite\CustomFunctions;

use App\Libraries\Netsuite\NetsuiteHelpers\NetSuiteConstantHelper;
use App\Libraries\Netsuite\NetsuiteHelpers\NetSuiteDataHelper;
use App\Libraries\Netsuite\NetsuiteHelpers\NetsuiteItemFulfillmentHelper;
use App\Models\DebugLogs;
use App\Models\IntegrationRecords;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use App\Helpers\ArrayHelper;
use Symfony\Component\HttpFoundation\ParameterBag;
use Carbon\Carbon;
use App\Jobs\CheckConsignmentLinks;

trait integration_1_custom_functions
{

    public function integration_1_postGetItems(array $source_data)
    {
        $items = [];

        $ms_company_items = $this->machship_cache_service->getCompanyItems();

        // get custom fields
        $cfs = data_get($source_data, 'customFieldList.customField');

        // find customfields with "custbody_box" pattern
        $netsuite_items = ArrayHelper::where($cfs, function ($value, $key) {
            return preg_match('/custbody_box_/', $value['scriptId']);
        });

        // iterate netsuite items here
        foreach ($netsuite_items as $item) {
            $index   = substr($item['scriptId'], strlen('custbody_box_'), 1);
            $pattern = preg_split("/(custbody_box_+[0-9]+_)/i", $item['scriptId']);
            $field   = $pattern[1];

            // for type there is a further process here
            if ($field === 'type') {
                if ($ms_company_items) {
                    $name = $item['value']['name'];
                    // look for the company items from machship
                    $company_item = ArrayHelper::where($ms_company_items->object, function ($value, $key) use ($name) {
                        $text = strtolower($value->name);
                        $search = strtolower($name);

                        return (strpos($text, $search) !== false);
                    });

                    // once we found an existing item we match the data
                    if (!empty($company_item)) {
                        $items[$index - 1]['itemType'] = head($company_item)->itemType;
                        $items[$index - 1]['machship_id'] = head($company_item)->id;
                        $items[$index - 1]['sku'] = head($company_item)->sku;
                        $items[$index - 1]['name'] = head($company_item)->name;
                        continue;
                    }

                    $this->infoLog("[ORDER_ITEMS] Item not found in machship : $name");
                }

            // quantity
            } elseif ($field === 'qty') {
                $items[$index - 1]['quantity'] = $item['value'];

            // default
            } else {
                $items[$index - 1][$field] = $item['value'];
            }
        }

        return $items;
    }

    /**
     * @return array
     */
    public function integration_1_preUpdateSourceData()
    {
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
         *          'update_type' => 'CUSTOM FIELD',
         *          'parameter'   => Symfony\Component\HttpFoundation\ParameterBag $parameterBag
         *      ],
         *    ];
         */

        // initialize the NETSUITE Service data
        $data = [];

        // initialize the record, source_integration_data, $status
        $record = $this->getCurrentRecord();
        $status = $this->getData()['status'];

        // initialize the parameters for the data
        $params = new ParameterBag();
        $params->set('internal_id', $record->source_id);

        // check if status is for delivery
        if (stripos($status['name'], 'Delivery') !== false
        ) {
            // create the status update data for custom fields
            $listOrRecordRef = NetSuiteDataHelper::listOrRecordRefField(3);
            $custom_fields[] = NetSuiteDataHelper::selectCustomFieldRef($listOrRecordRef, 'custbody_machship_status');
            $params->set('custom_fields', $custom_fields);
            $status_array = array(
                'update_type' => 'CUSTOM FIELD',
                'parameter' => $params
            );
            array_push($data, $status_array);

            // create the update the shipment date for the item fulfillment
            $new_params = new ParameterBag();
            $new_params->set(NetsuiteItemFulfillmentHelper::COLUMN_INTERNAL_ID, $record->source_id);
            $new_params->set(NetsuiteItemFulfillmentHelper::COLUMN_SHIPPED_DATE, Carbon::now()->setTimezone('UTC')->format('Y-m-d\TH:i:s\Z'));
            $date_array = array(
                'update_type' => 'FIELD',
                'parameter' => $new_params
            );
            array_push($data, $date_array);
        }

        if (stripos($status['name'], 'Completed') !== false
        ) {
            // create the status update data for custom fields
            $listOrRecordRef = NetSuiteDataHelper::listOrRecordRefField(4);
            $custom_fields[] = NetSuiteDataHelper::selectCustomFieldRef($listOrRecordRef, 'custbody_machship_status');
            $params->set('custom_fields', $custom_fields);
            $status_array =  array(
                'update_type' => 'CUSTOM FIELD',
                'parameter' => $params
            );
            array_push($data, $status_array);
        }

        $this->setCustomFunctionResult($data);
    }

    /**
     * Custom function for ACL get consignment type
     * It depends if its dangerous goods
     * @return     Optional  returns consignment type or null
     */
    public function integration_1_postGetConsignmentType()
    {
        if (empty($this->machship_payload) || !isset($this->machship_payload['dgsDeclaration'])) {
            return;
        }

        if ($this->machship_payload['dgsDeclaration']) {
            return IntegrationRecords::RECORD_CONSIGNMENT_TYPE_PENDING;
        } else {
            return IntegrationRecords::RECORD_CONSIGNMENT_TYPE_MANIFEST;
        }
    }

    /**
     * Custom function for ACL which will run the check required fields to eliminate fulfillment's without custom boxes
     */
    public function integration_1_postGetIntegrationRecordStatus()
    {
        $record_status = IntegrationRecords::RECORD_STATUS_PENDING_MACHSHIP;

        // get current integration record
        $record = $this->getCurrentRecord();
        $record_raw = $record->source_data;

        // check the send to machship custom field
        $send_to_machship = ArrayHelper::getAssociativeArraySet($record_raw['customFieldList']['customField'], NetSuiteConstantHelper::NETSUITE_CUSTOM_FIELD_COLUMN_SCRIPT_ID, NetSuiteConstantHelper::CUSTOM_ACL_FIELD_DO_NOT_SEND_TO_MACSHIP);
        if ($send_to_machship) {
            $value = json_encode($send_to_machship['value']);
            if ($value == false) {
                $this->debugLog(DebugLogs::STEP_WF_3, 'SKIPPED', [
                    'integration_id' => $this->integration_id,
                    'integration_sync_id' => $record->integration_sync_id,
                    'data' => 'Integration record ' . $record->id . ' MARKED as SKIPPED, custom field SEND TO MACHSHIP is FALSE.'
                ]);
                $record_status = IntegrationRecords::RECORD_STATUS_SKIPPED;
            }
        }

        // check if the current record has custom boxes in the custom fields
        $custom_boxes = ArrayHelper::getAssociativeArraySet($record_raw['customFieldList']['customField'], NetSuiteConstantHelper::NETSUITE_CUSTOM_FIELD_COLUMN_SCRIPT_ID, '_box_', true);
        if (!$custom_boxes) {
            $this->debugLog(DebugLogs::STEP_WF_3, 'SKIPPED', [
                'integration_id' => $this->integration_id,
                'integration_sync_id' => $record->integration_sync_id,
                'data' => 'Integration record ' . $record->id . ' MARKED as SKIPPED, no custom boxes FOUND.'
            ]);
            $record_status = IntegrationRecords::RECORD_STATUS_SKIPPED;
        }

        return $record_status;
    }

    /**
     * Custom function for ACL which will run the check for the LINK CONSIGNMENTS
     */
    public function integration_1_postGetIntegrationRecordProcessAfter()
    {
        $time_after = Carbon::now()->subMinute()->toDateTimeString();

        // get current integration record
        $record = $this->getCurrentRecord();
        $record_raw = $record->source_data;

        $shipped_with = ArrayHelper::getAssociativeArraySet($record_raw['customFieldList']['customField'], NetSuiteConstantHelper::NETSUITE_CUSTOM_FIELD_COLUMN_SCRIPT_ID, NetSuiteConstantHelper::CUSTOM_ACL_FIELD_SHIPPING_WITH);
        if ($shipped_with) {
            // todo: determine if this is ok with 5 minutes deferred time processing
            $time_after = Carbon::now()->addMinutes(5)->toDateTimeString();
            // dispatch consignment link job
            CheckConsignmentLinks::dispatchNow($record, $shipped_with);
        }

        return $time_after;
    }


    /**
     * This custom function is used in data conversion service
     * to get the location id via netsuite's data location city and zip
     * @param      array          $location  The location
     * @return     integer        location id
     */
    public function integration_1_getLocationId($location)
    {

        if (empty($this->machship_cache_service)) {
            $this->errorLog('[getLocationId]', 'No machship cache service');
            return;
        }

        // Validate location params
        if (isset($location) &&
            isset($location['city']) &&
            isset($location['zip'])
        ) {
            // checks integration available
            if ($this->integration) {
                $ms_locations = $this->machship_cache_service->getLocations();

                if ($ms_locations) {
                    // try to find location
                    $locations = Arr::where($ms_locations->object, function ($value, $key) use ($location) {

                        $suburb = isset($value->suburb) ? strtolower($value->suburb) : '';
                        $pcode = isset($value->postcode) ? strtolower($value->postcode) : '';

                        return $suburb == strtolower($location['city']) && $pcode == strtolower($location['zip']);
                    });

                    // return first found location id
                    if ($locations) {
                        return head($locations)->id;
                    }
                }
            }

            return 0;

        // otherwise return empty
        } else {
            $this->errorLog('[getLocationId]', 'empty get location id');
            return '';
        }
    }

    /**
     * This function will get the related Sales order reference
     * number via netsuite sales order type
     * @param      integer  $so_id  Sales order identifier
     * @return     integer  other reference number of netsuite's sales order
     */
    public function integration_1_getRelatedSalesOrder($so_id)
    {
        $type = $this->getNetsuiteObjectType(NetSuiteConstantHelper::NETSUITE_SALES_ORDER);
        $so = $this->netsuite->setObject($type);
        $netsuite_so = $so->find($so_id);
        if ($netsuite_so) {
            return $netsuite_so->otherRefNum;
        } else {
            $this->errorLog('[getRelatedSalesOrder]', 'empty get related sales order');
            return '';
        }
    }

    /**
     * This custom function will get id from company location
     * @param      array  $ifl_value  ifl custom value
     * @return       ( description_of_the_return_value )
     */
    public function integration_1_getFromCompanyLocationId($ifl_value)
    {

        $value_lookups = $this->integration->valueLookup;
        if (empty($value_lookups)) {
            $this->errorLog('[getFromCompanyLocationId]', 'no value lookups');
            return;
        }

        if (empty($ifl_value) || empty($ifl_value['name'])) {
            $this->errorLog('[getFromCompanyLocationId]', 'ifl value is empty');
        }

        // determine from value
        $from_value = $ifl_value['name'];

        foreach ($value_lookups as $lookups) {
            if ($lookups['machship_field'] == 'fromCompanyLocationId' &&
                strtolower($lookups['from_value']) == strtolower($from_value)
            ) {
                return $lookups['to_value'];
            }
        }
    }
}
