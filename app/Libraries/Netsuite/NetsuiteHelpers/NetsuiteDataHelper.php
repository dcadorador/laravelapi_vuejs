<?php

namespace App\Libraries\Netsuite\NetsuiteHelpers;

use NetSuite\Classes\Customer;
use NetSuite\Classes\CustomFieldList;
use NetSuite\Classes\ItemFulfillment;
use NetSuite\Classes\SearchCustomFieldList;
use NetSuite\Classes\SearchRequest;
use NetSuite\Classes\SearchStringField;
use NetSuite\Classes\SelectCustomFieldRef;
use NetSuite\Classes\UpdateRequest;
use Symfony\Component\HttpFoundation\ParameterBag;
use NetSuite\Classes\StringCustomFieldRef;
use NetSuite\Classes\ListOrRecordRef;
use NetSuite\Classes\CustomFieldRef;
use NetSuite\Classes\SearchDateField;
use NetSuite\Classes\SearchEnumMultiSelectField;
use NetSuite\Classes\RecordRef;
use NetSuite\Classes\SearchMultiSelectField;
use NetSuite\Classes\SearchColumnBooleanCustomField;
use NetSuite\Classes\SearchStringCustomField;
use NetSuite\Classes\SearchMultiSelectCustomField;
use NetSuite\Classes\GetRequest;
use NetSuite\Classes\SearchColumnCustomFieldList;
use NetSuite\Classes\SearchBooleanCustomField;
use NetSuite\Classes\BooleanCustomFieldRef;
use NetSuite\Classes\SalesOrder;
use App\Libraries\Netsuite\NetsuiteHelpers\NetSuiteConstantHelper;

class NetSuiteDataHelper
{


    public static function updateTrackingNumData($tracking_number)
    {
        return self::stringCustomField(
            $tracking_number,
            NetSuiteConstantHelper::CUSTOM_FIELD_TRACKING_NUMBER
        );
    }

    public static function updateConsignmentNumData($consignment_number)
    {
        return self::stringCustomField(
            $consignment_number,
            NetSuiteConstantHelper::CUSTOM_FIELD_CONSIGNMENT_NUMBER,
            NetSuiteConstantHelper::NETSUITE_CF_MACSHIP_TRACKING_NUMBER_INTERNAL_ID
        );
    }

    public static function updateFulfillmentStatusData($status)
    {
        return self::stringCustomField(
            $status,
            NetSuiteConstantHelper::CUSTOM_FIELD_CONSIGNMENT_NUMBER
        );
    }

    public static function updateMacshipStatusData($internal_id)
    {
        $listOrRecordRef = self::listOrRecordRefField($internal_id);

        return self::selectCustomFieldRef(
            $listOrRecordRef,
            NetSuiteConstantHelper::CUSTOM_FIELD_MACHSHIP_STATUS
        );
    }

    /**
     * @param $value
     * @param null $script_id
     * @param null $internal_id
     * @return StringCustomFieldRef
     */
    public static function stringCustomField($value, $script_id = null, $internal_id = null)
    {
        $stringCustomField = new StringCustomFieldRef();
        $stringCustomField->value = $value;

        if ($script_id) {
            $stringCustomField->scriptId = $script_id;
        }
        if ($internal_id) {
            $stringCustomField->internalId = $internal_id;
        }

        return $stringCustomField;
    }

    /**
     * @param $internal_id
     * @param null $name
     * @param null $type
     * @param null $external_id
     * @return RecordRef
     */
    public static function recordRef($internal_id, $name = null, $type = null, $external_id = null)
    {
        $record_ref = new RecordRef();
        $record_ref->internalId = $internal_id;

        if ($type) {
            $record_ref->type = $type;
        }
        if ($name) {
            $record_ref->name = $name;
        }
        if ($external_id) {
            $record_ref->externalId = $external_id;
        }

        return $record_ref;
    }

    /**
     * @param $internal_id
     * @param null $name
     * @param null $type_id
     * @param null $external_id
     * @return ListOrRecordRef
     */
    public static function listOrRecordRefField($internal_id, $name = null, $type_id = null, $external_id = null)
    {
        $listOrRecordRef = new ListOrRecordRef();
        $listOrRecordRef->internalId = $internal_id;

        if ($type_id) {
            $listOrRecordRef->typeId = $type_id;
        }
        if ($name) {
            $listOrRecordRef->name = $name;
        }
        if ($external_id) {
            $listOrRecordRef->externalId = $external_id;
        }

        return $listOrRecordRef;
    }

    /**
     * @param $value
     * @param $script_id
     * @return SelectCustomFieldRef
     */
    public static function selectCustomFieldRef($value, $script_id)
    {
        $selectCustomFieldRef = new SelectCustomFieldRef();
        $selectCustomFieldRef->value = $value;
        $selectCustomFieldRef->scriptId = $script_id;
        return $selectCustomFieldRef;
    }

    /**
     * @param $operator
     * @param $search_value
     * @param null $search_value2
     * @param null $pre_defined_search
     * @return SearchDateField
     */
    public static function searchDateField($operator, $search_value, $search_value2 = null, $pre_defined_search = null)
    {
        $search_date_field = new SearchDateField();
        $search_date_field->operator = $operator;
        $search_date_field->searchValue = $search_value;

        if ($search_value2) {
            $search_date_field->searchValue2 = $search_value2;
        }
        if ($pre_defined_search) {
            $search_date_field->predefinedSearchValue = $pre_defined_search;
        }

        return $search_date_field;
    }

    /**
     * @param $search
     * @param $operator
     * @return SearchEnumMultiSelectField
     */
    public static function searchEnumMultiSelectField($search, $operator)
    {
        $searchEnumMultiSelectField = new SearchEnumMultiSelectField();
        $searchEnumMultiSelectField->searchValue = $search;
        $searchEnumMultiSelectField->operator = $operator;
        return $searchEnumMultiSelectField;
    }

    /**
     * @param $search
     * @param $operator
     * @return SearchMultiSelectField
     */
    public static function searchMultiSelectField($operator, $search)
    {
        $searchMultiSelectField = new SearchMultiSelectField();
        $searchMultiSelectField->operator = $operator;
        $searchMultiSelectField->searchValue = $search;
        return $searchMultiSelectField;
    }

    /**
     * @param $search
     * @param null $script_id
     * @param null $internal_id
     * @return SearchBooleanCustomField
     */
    public static function searchBooleanCustomField($search, $script_id = null, $internal_id = null)
    {
        $searchBooleanCustomField = new SearchBooleanCustomField();
        $searchBooleanCustomField->searchValue = $search;

        if ($script_id) {
            $searchBooleanCustomField->scriptId = $script_id;
        }
        if ($internal_id) {
            $searchBooleanCustomField->internalId = $internal_id;
        }

        return $searchBooleanCustomField;
    }

    /**
     * @param $operator
     * @param null $search
     * @param null $script_id
     * @param null $internal_id
     * @return SearchStringCustomField
     */
    public static function searchStringCustomField($operator, $search = null, $script_id = null, $internal_id = null)
    {
        $searchStringCustomField = new SearchStringCustomField();
        $searchStringCustomField->operator = $operator;

        if ($search) {
            $searchStringCustomField->searchValue = $search;
        }
        if ($script_id) {
            $searchStringCustomField->scriptId = $script_id;
        }
        if ($internal_id) {
            $searchStringCustomField->internalId = $internal_id;
        }


        return $searchStringCustomField;
    }

    /**
     * @param $operator
     * @param null $search
     * @param null $script_id
     * @param null $internal_id
     * @return SearchMultiSelectCustomField
     */
    public static function searchMultiSelectCustomField(
        $operator,
        $search = null,
        $script_id = null,
        $internal_id = null
    ) {
        $searchMultiSelectCustomField = new SearchMultiSelectCustomField();
        $searchMultiSelectCustomField->operator = $operator;

        if ($search) {
            $searchMultiSelectCustomField->searchValue = $search;
        }
        if ($script_id) {
            $searchMultiSelectCustomField->scriptId = $script_id;
        }
        if ($internal_id) {
            $searchMultiSelectCustomField->internalId = $internal_id;
        }

        return $searchMultiSelectCustomField;
    }

    /**
     * @param $base_ref
     * @return GetRequest
     */
    public static function getRequest($base_ref)
    {
        $getRequest = new GetRequest();
        $getRequest->baseRef = $base_ref;
        return $getRequest;
    }

    /**
     * @param $custom_fields
     * @return SearchCustomFieldList
     */
    public static function searchColumnCustomFieldList($custom_fields)
    {
        $searchColumnCustomFieldList = new SearchCustomFieldList();
        $searchColumnCustomFieldList->customField = $custom_fields;
        return $searchColumnCustomFieldList;
    }

    /**
     * @param $search
     * @return SearchRequest
     */
    public static function searchRequest($search)
    {
        $searchRequest = new SearchRequest();
        $searchRequest->searchRecord = $search;
        return $searchRequest;
    }

    /**
     * @param $record
     * @return UpdateRequest
     */
    public static function updateRequest($record)
    {
        $updateRequest = new UpdateRequest();
        $updateRequest->record = $record;
        return $updateRequest;
    }

    /**
     * @param $custom_fields
     * @return CustomFieldList
     */
    public static function customFieldList($custom_fields)
    {
        $customFields = new CustomFieldList();
        $customFields->customField = $custom_fields;
        return $customFields;
    }

    /**
     * @param $value
     * @param null $script_id
     * @param null $internal_id
     * @return BooleanCustomFieldRef
     */
    public static function booleanCustomFieldRef($value, $script_id = null, $internal_id = null)
    {
        $booleanCustomFieldRef = new BooleanCustomFieldRef();
        $booleanCustomFieldRef->value = $value;
        if ($script_id) {
            $booleanCustomFieldRef->scriptId = $script_id;
        }
        if ($internal_id) {
            $booleanCustomFieldRef->internalId = $internal_id;
        }
        return $booleanCustomFieldRef;
    }

    /**
     * @param $object
     * @param array $data
     * @return Customer|ItemFulfillment|SalesOrder
     */
    public static function itemObject($object, $data = [])
    {
        switch ($object) {
            case NetSuiteConstantHelper::NETSUITE_SALES_ORDER:
                $netsuite_object = new SalesOrder();
                break;
            case NetSuiteConstantHelper::NETSUITE_CUSTOMER:
                $netsuite_object = new Customer();
                break;
            case NetSuiteConstantHelper::NETSUITE_ITEM_FULFILLMENT:
            default:
                $netsuite_object = new ItemFulfillment();
                break;
        }

        foreach ($data as $parameter => $value) {
            $netsuite_object->{$parameter} = $value;
        }

        return $netsuite_object;
    }

    /**
     * @param $operator
     * @param $value
     * @return SearchStringField
     */
    public static function searchStringField($operator, $value)
    {
        $searchStringField = new SearchStringField();
        $searchStringField->searchValue = $value;
        $searchStringField->operator = $operator;
        return $searchStringField;
    }
}
