<?php

namespace App\Libraries\Netsuite\Traits;

use App\Libraries\Netsuite\NetsuiteHelpers\NetSuiteConstantHelper;
use App\Libraries\Netsuite\NetsuiteHelpers\NetSuiteDataHelper;
use NetSuite\Classes\TransactionSearchBasic;
use NetSuite\Classes\CustomerSearchBasic;

trait QueryBuilder
{
    /**
     * @param $search_class_type
     * @return array
     */
    public function netsuiteClassSetter($search_class_type)
    {
        $data = [];
        switch ($search_class_type) {
            case NetSuiteConstantHelper::NETSUITE_CUSTOMER:
                $data['searchDataType'] = CustomerSearchBasic::$paramtypesmap;
                $data['objectDataTypes'] = NetSuiteConstantHelper::customerDataTypes();
                break;
            case NetSuiteConstantHelper::NETSUITE_SALES_ORDER:
                $data['searchDataType'] = TransactionSearchBasic::$paramtypesmap;
                $data['objectDataTypes'] = NetSuiteConstantHelper::salesOrderDataTypes();
                break;
            case NetSuiteConstantHelper::NETSUITE_ITEM_FULFILLMENT:
            default:
                $data['searchDataType'] = TransactionSearchBasic::$paramtypesmap;
                $data['objectDataTypes'] = NetSuiteConstantHelper::itemFulfillmentDataTypes();
                break;
        }
        return $data;
    }

    /**
     * todo: update the values "netsuite_source_column" & "netsuite_source_value"
     * @param $search_class_type
     * @param $search
     * @param $params
     * @return mixed
     */
    public function transactionQueryBuilder($search_class_type, $search, $params)
    {
        // set the related data type from netsuite object
        // set the params type from netsuite class
        $data_array = $this->netsuiteClassSetter($search_class_type);
        $searchDataType = $data_array['searchDataType'];
        $objectDataTypes = $data_array['objectDataTypes'];

        if (count($params)) {
            $netsuiteDataHelper = new NetSuiteDataHelper();

            foreach ($params as $param) {
                $netsuite_source_column = $param['field'];
                // \Log::info('field is : ' . $netsuite_source_column);
                if (!array_key_exists($netsuite_source_column, $searchDataType)) {
                    continue; // if the meta key is not existing then skip the existing data
                }

                // get the related data type for searching
                $searchType = lcfirst($searchDataType[$netsuite_source_column]);

                // TODO! customize for customfield only
                // if ($netsuite_source_column === 'customFieldList') {
                //     $searchType = 'searchColumnCustomFieldList';
                // }

                // check if data type for the fulfillment object is a netsuite type
                // if the column key for the item fulfillment object, is not existing then skip the existing data
                if (!array_key_exists($netsuite_source_column, $objectDataTypes)) {
                    // gives one last check if search type exist then it needs to override search typle
                    if (method_exists($netsuiteDataHelper, $searchType)) {
                        $operator = isset($param['operator']) ? $param['operator'] : NetSuiteConstantHelper::NETSUITE_ANY_OF_OPERATOR;
                        $NSearchClass = "\\NetSuite\\Classes\\" . $searchDataType[$netsuite_source_column];
                        $searchTypeClass = new $NSearchClass();
                        $searchTypeClass->operator = $operator;
                        $searchTypeClass->searchValue = $param['search_value'];

                        // for search by dates type we need to format date
                        if ($searchDataType[$netsuite_source_column] === 'SearchDateField') {
                            $now = new \DateTime();
                            $now->modify($param['search_value']);
                            // ensure we use UTC
                            $now->setTimezone(new \DateTimeZone('UTC'));
                            // modify datetime and format to ISO 8601
                            $date = $now->format('Y-m-d\TH:i:s.000\Z');
                            $searchTypeClass->searchValue = $date; //date('Y-m-d\TH:i:s.000\Z', strtotime($param['search_value']));
                        }

                        $search->{$netsuite_source_column} = $searchTypeClass;
                    }

                    continue;
                }


                // handle custom field here
                if (isset($param['custom_fields'])) {
                    $customFields = json_decode($param['custom_fields'], true);
                    $custom_fields = [];
                    foreach ($customFields as $cf) {
                        $csearch = NetSuiteDataHelper::{$cf['search']['method']}(
                            (int) $cf['search']['search_value'],
                            isset($cf['search']['name']) ? $cf['search']['name'] : null,
                            isset($cf['search']['type_id']) ? $cf['search']['type_id'] : null,
                            isset($cf['search']['external_id']) ? $cf['search']['external_id'] : null
                        );
                        $custom_fields[] = NetSuiteDataHelper::{$cf['method']}(
                            $cf['operator'],
                            $csearch,
                            null,
                            (int) $cf['internal_id']
                        );
                    }

                    $search->customFieldList = NetSuiteDataHelper::searchColumnCustomFieldList($custom_fields);
                    continue;
                }

                $netsuiteObjectType = lcfirst($objectDataTypes[$netsuite_source_column]);

                // call the data column static function for the item fulfillment object column
                if (method_exists($netsuiteDataHelper, $netsuiteObjectType)) {
                    $objectTypeClass = call_user_func_array(
                        array($netsuiteDataHelper, $netsuiteObjectType),
                        array($param['search_value'])
                    );
                } else {
                    $objectTypeClass = $param['search_value'];
                }

                // call the search static function for the search object column
                if (method_exists($netsuiteDataHelper, $searchType)) {
                    $operator = isset($param['operator']) ? $param['operator'] : NetSuiteConstantHelper::NETSUITE_ANY_OF_OPERATOR;
                    $searchTypeClass = call_user_func_array(
                        array($netsuiteDataHelper, $searchType),
                        array($operator, $objectTypeClass)
                    );

                    $search->{$netsuite_source_column} = $searchTypeClass;
                } else {
                    continue;
                }
            }
        }

        return $search;
    }

    /***
     * todo: assuming that the $params is set as ['source_field' => 'machship_value']
     * @param $search_class_type
     * @param $params
     * @param bool $custom_fields
     * @return array
     */
    public function transactionUpdateDataBuilder($search_class_type, $param)
    {
        $update_data = [];
        $data_array = $this->netsuiteClassSetter($search_class_type);
        $objectDataTypes = $data_array['objectDataTypes'];

        // assuming that the data for the update is coming from the field mapper
        if (count($params)) {
            foreach ($params as $param) {
                if (!array_key_exists($param['source_field'], $objectDataTypes)) {
                    continue; // if the meta key is not existing then skip the existing data
                }

                $netsuiteObjectType = lcfirst($objectDataTypes[$param['source_field']]);
                $netsuiteDataHelper = new NetSuiteDataHelper();

                if (method_exists($netsuiteDataHelper, $netsuiteObjectType)) {
                    $objectTypeClass = call_user_func_array(
                        array($netsuiteDataHelper, $netsuiteObjectType),
                        array('machship_field_value')
                    );
                    $update_data[$param['source_field']] = $objectTypeClass;
                } else {
                    $update_data[$param['source_field']] = 'machship_field_value';
                }
            }
        }

        return $update_data;
    }
}
