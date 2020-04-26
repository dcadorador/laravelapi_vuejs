<?php

namespace App\Helpers;

class Helper
{
    const NETSUITE_ENDPOINT_META = 'NETSUITE_ENDPOINT';
    const NETSUITE_ACCOUNT_META = 'NETSUITE_ACCOUNT';
    const NETSUITE_CONSUMER_KEY_META = 'NETSUITE_CONSUMER_KEY';
    const NETSUITE_CONSUMER_SECRET_META = 'NETSUITE_CONSUMER_SECRET';
    const NETSUITE_TOKEN_META = 'NETSUITE_TOKEN';
    const NETSUITE_TOKEN_SECRET_META = 'NETSUITE_TOKEN_SECRET';
    const NETSUITE_WEBSERVICES_HOST_META = 'NETSUITE_WEBSERVICES_HOST';
    const NETSUITE_APP_ID_META = 'NETSUITE_APP_ID';
    const NETSUITE_OBJECT_TYPE_META = 'NETSUITE_OBJECT_TYPE';

    const MACSHIP_FIELDS = [
        // TO MACHSHIP
        'sendingTrackingEmail',
        'dgsDeclaration',
        'items.companyItemId',
        'items.itemType',
        'items.name',
        'items.sku',
        'items.quantity',
        'items.height',
        'items.weight',
        'items.length',
        'items.width',
        'companyId',
        'despatchDateTimeUtc',
        'despatchDateTimeLocal',
        'customerReference',
        'customerReference2',
        'carrierId',
        'carrierServiceId',
        'subServiceId',
        'carrierAccountId',
        'companyCarrierAccountId',
        'defaultRouteSelection',
        'fromCompanyLocationId',
        'fromName',
        'fromAbbreviation',
        'fromContact',
        'fromPhone',
        'fromEmail',
        'fromAddressLine1',
        'fromAddressLine2',
        'fromLocationId',
        'fromLocation.suburb',
        'fromLocation.postcode',
        'toCompanyLocationId',
        'toName',
        'toAbbreviation',
        'toContact',
        'toPhone',
        'toEmail',
        'toAddressLine1',
        'toAddressLine2',
        'toLocationId',
        'toLocation.suburb',
        'toLocation.postcode',
        'specialInstructions',
        'questionIds',
        'receiverAccountCode',
        'receiverAccountId',

        // FROM MACHSHIP
        'consignmentNumber'
    ];

    /**
     * Get tracking status from consignment
     *
     * @param $machship_consignment
     * @return null
     */
    public static function getTrackingStatus($machship_consignment)
    {
        $tracking_status = null;

        if ($machship_consignment->object) {
            if (property_exists($machship_consignment->object, 'consignmentTrackingStatus')) {
                $tracking_status = $machship_consignment->object->consignmentTrackingStatus;
            } elseif (property_exists($machship_consignment->object, 'status')) {
                $tracking_status = $machship_consignment->object->status;
            }
        }
        return $tracking_status;
    }

    /**
     * @param $object
     * @param $key
     * @return null
     */
    public static function getValueOrNull($object, $key)
    {
        return isset($object->{$key}) ? $object->{$key} : null;
    }
}
