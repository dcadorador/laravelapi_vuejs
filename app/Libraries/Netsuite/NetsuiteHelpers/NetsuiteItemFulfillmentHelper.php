<?php
namespace App\Libraries\Netsuite\NetsuiteHelpers;

class NetsuiteItemFulfillmentHelper
{
    const COLUMN_CREATED_DATE  = "createdDate";
    const COLUMN_LAST_MODIFIED_DATE  = "lastModifiedDate";
    const COLUMN_CUSTOM_FORM  = "customForm";
    const COLUMN_POSTING_PERIOD  = "postingPeriod";
    const COLUMN_ENTITY  = "entity";
    const COLUMN_CREATED_FROM  = "createdFrom";
    const COLUMN_REQUESTED_BY  = "requestedBy";
    const COLUMN_CREATED_FROM_SHIP_GROUP  = "createdFromShipGroup";
    const COLUMN_PARTNER  = "partner";
    const COLUMN_SHIPPING_ADDRESS  = "shippingAddress";
    const COLUMN_PICKED_DATE  = "pickedDate";
    const COLUMN_PACKED_DATE  = "packedDate";
    const COLUMN_SHIPPED_DATE  = "shippedDate";
    const COLUMN_SHIP_IS_RESIDENTIAL  = "shipIsResidential";
    const COLUMN_SHIP_ADDRESS_LIST  = "shipAddressList";
    const COLUMN_SATURDAY_DELIVERY_UPS  = "saturdayDeliveryUps ";
    const COLUMN_SEND_SHIP_NOTIFY_EMAIL_UPS  = "sendShipNotifyEmailUps";
    const COLUMN_SEND_BACKUP_EMAIL_UPS  = "sendBackupEmailUps";
    const COLUMN_SHIP_NOTIFY_EMAIL_ADDRESS_UPS  = "shipNotifyEmailAddressUps";
    const COLUMN_SHIP_NOTIFY_EMAIL_ADDRESS2_UPS  = "shipNotifyEmailAddress2Ups";
    const COLUMN_BACKUP_EMAIL_ADDRESS_UPS  = "backupEmailAddressUps";
    const COLUMN_SHIP_NOTIFY_EMAIL_MESSAGE_UPS  = "shipNotifyEmailMessageUps";
    const COLUMN_LICENSE_NUMBER_UPS  = "licenseNumberUps";
    const COLUMN_LICENSE_DATE_UPS  = "licenseDateUps";
    const COLUMN_SHIPPING_COST  = "shippingCost";
    const COLUMN_HANDLING_COST  = "handlingCost";
    const COLUMN_MEMO  = "memo";
    const COLUMN_INTERNAL_ID  = "internalId";
    const COLUMN_EXTERNAL_ID  = "externalId";
}
