<?php
namespace App\Services\Platforms\Netsuite;

trait NetsuiteDefaultMaps
{

    // Set default mapping fields for this integration
    public static function defaultMaps()
    {
        return [
            [
                'machship_field' => 'sendingTrackingEmail',
                'direction' => 'to_machship',
                'map_type' => 'skip',
                'source_field' => '',
                'data_conversion_type' => 'constant',
                'data_conversion_value' => 'true',
            ],
            [
                'machship_field' => 'dgsDeclaration',
                'direction' => 'to_machship',
                'map_type' => 'customfield',
                'source_field' => 'custbody_contains_dgs'
            ],
            [
                'machship_field' => 'companyId',
                'direction' => 'to_machship',
                'map_type' => 'skip',
                'source_field' => '',
                'data_conversion_type' => 'constant',
                'data_conversion_value' => 3856
            ],
            [
                'machship_field' => 'despatchDateTimeUtc',
                'direction' => 'to_machship',
                'map_type' => 'skip',
                'source_field' => '',
                'data_conversion_type' => 'function',
                'data_conversion_value' => 'getDateTimeUTC'
            ],
            [
                'machship_field' => 'despatchDateTimeLocal',
                'direction' => 'to_machship',
                'map_type' => 'skip',
                'source_field' => '',
                'data_conversion_type' => 'function',
                'data_conversion_value' => 'getDateTimeByAus'
            ],
            [
                'machship_field' => 'customerReference',
                'direction' => 'to_machship',
                'map_type' => 'direct',
                'source_field' => 'tranId',
            ],
            [
                'machship_field' => 'carrierId',
                'direction' => 'to_machship',
                'map_type' => 'direct',
                'source_field' => 'shipMethod.name',
                'data_conversion_type' => 'lookup'
            ],
            [
                'machship_field' => 'carrierAccountId',
                'direction' => 'to_machship',
                'map_type' => 'direct',
                'source_field' => 'shipMethod.name',
                'data_conversion_type' => 'lookup',
            ],
            [
                'machship_field' => 'fromCompanyLocationId',
                'direction' => 'to_machship',
                'map_type' => 'skip',
                'data_conversion_type' => 'constant',
                'data_conversion_value' => 814878
            ],
            [
                'machship_field' => 'toName',
                'direction' => 'to_machship',
                'map_type' => 'direct',
                'source_field' => 'shippingAddress.addressee'
            ],
            [
                'machship_field' => 'toEmail',
                'direction' => 'to_machship',
                'map_type' => 'customfield',
                'source_field' => 'custbody_despatch_notif_email'
            ],
            [
                'machship_field' => 'toAddressLine1',
                'direction' => 'to_machship',
                'map_type' => 'direct',
                'source_field' => 'shippingAddress.addr1'
            ],
            [
                'machship_field' => 'toAddressLine2',
                'direction' => 'to_machship',
                'map_type' => 'direct',
                'source_field' => 'shippingAddress.addr2'
            ],
            [
                'machship_field' => 'toLocation.suburb',
                'direction' => 'to_machship',
                'map_type' => 'direct',
                'source_field' => 'shippingAddress.city'
            ],
            [
                'machship_field' => 'toLocation.postcode',
                'direction' => 'to_machship',
                'map_type' => 'direct',
                'source_field' => 'shippingAddress.zip'
            ],
            [
                'machship_field' => 'receiverAccountCode',
                'direction' => 'to_machship',
                'map_type' => 'customfield',
                'source_field' => 'custbody_freight_account_number',
            ],
            [
                'machship_field' => 'carrierServiceId',
                'direction'     => 'to_machship',
                'map_type' => 'direct',
                'source_field' => 'shipMethod.name',
                'data_conversion_type' => 'lookup',
            ],
            [
                'machship_field' => 'toLocationId',
                'direction'     => 'to_machship',
                'map_type'      => 'direct_simple',
                'source_field'  => 'shippingAddress',
                'data_conversion_type' => 'function',
                'data_conversion_value' => 'getLocationId'
            ],
        ];
    }
}
