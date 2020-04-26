<?php
namespace App\Services\Platforms\Netsuite;

use App\Libraries\Netsuite\NetsuiteHelpers\NetSuiteConstantHelper as NSHelper;
use NetSuite\Classes\TransactionSearchBasic as NSSearchBasic;

trait NetsuiteDefaultFilters
{

    public static function defaultFilters()
    {
        return [

            // ACL default filters test
            // 'filter_1' => [
            //     'field' => 'type',
            //     'operator' => 'anyOf',
            //     'search_value' => '_itemFulfillment'
            // ],
            // 'filter_2' => [
            //     'field' => 'dateCreated',
            //     'operator' => 'after',
            //     'search_value' => '-12 hours'
            // ],
            // 'filter_3' => [
            //     'field' => 'shipMethod',
            //     'operator' => 'noneOf',
            //     'search_value' => '7252',
            // ]


            // DELF default filters test
            // 'filter_1' => [
            //     'field' => 'type',
            //     'operator' => 'anyOf',
            //     'search_value' => 'salesOrder'
            // ],
            // 'filter_2' => [
            //     'field' => 'dateCreated',
            //     'operator' => 'after',
            //     'search_value' => '-12 hours'
            // ],


            // D2G default filters test
            'filter_1' => [
                'field' => 'type',
                'operator' => 'anyOf',
                'search_value' => '_itemFulfillment'
            ],
            'filter_2' => [
                'field' => 'dateCreated',
                'operator' => 'after',
                'search_value' => '-3 hours'
            ],
            // 'filter_3' => [
            //     'field' => 'customFieldList',
            //     'custom_fields' => json_encode([
            //         [
            //             'method' => 'searchMultiSelectCustomField',
            //             'operator' => 'anyOf',
            //             'internal_id' => '3589',
            //             'search' => [
            //                 'method' => 'listOrRecordRefField',
            //                 'search_value' => '13',
            //             ]
            //         ],
            //         [
            //             'method' => 'searchMultiSelectCustomField',
            //             'operator' => 'anyOf',
            //             'internal_id' => '3581',
            //             'search' => [
            //                 'method' => 'listOrRecordRefField',
            //                 'search_value' => '11',
            //             ]
            //         ]
            //     ])
            // ],
            'filter_4' => [
                'field' => 'shipMethod',
                'operator' => 'noneOf',
                'search_value' => '7252',
            ],
        ];
    }


    public static function getFilterOptions()
    {
        return [
            'field' => array_keys(NSSearchBasic::$paramtypesmap),
            'operator' => [
                NSHelper::NETSUITE_ANY_OF_OPERATOR,
                NSHelper::NETSUITE_NONE_OF_OPERATOR,
                NSHelper::NETSUITE_NOT_EMPTY_OPERATOR,
                NSHelper::NETSUITE_AFTER_OPERATOR,
                NSHelper::NETSUITE_IS_OPERATOR,
                NSHelper::NETSUITE_CONTAINS_OPERATOR,
                NSHelper::NETSUITE_EMPTY_OPERATOR,
            ],
            'method' => '',
            'search_value' => ''
        ];
    }
}
