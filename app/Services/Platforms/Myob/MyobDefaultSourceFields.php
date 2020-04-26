<?php
namespace App\Services\Platforms\Myob;

trait MyobDefaultSourceFields
{

    /**
    * Override this so each integration platform has
    * its own default source fields
    */
    public static function defaultSourceFields()
    {
        return [
            'id',
            'note',
            'CreatedDateTime.value',
            'CustomerID.value',
            'Details',
            'Details.id',
            'Details.rowNumber',
            'Details.note',
            'Details.Description.value',
            'Details.ExpirationDate.value',
            'Details.FreeItem.value',
            'Details.InventoryID',
            'Details.LineNbr',
            'Details.LocationID',
            'Details.LotSerialNbr',
            'Details.OpenQty.value',
            'Details.OrderedQty.value',
            'Details.OrderLineNbr.value',
            'Details.OrderNbr.value',
            'Details.OrderType.value',
            'Details.OriginalQty.value',
            'Details.ReasonCode',
            'Details.ShippedQty.value',
            'Details.UOM.value',
            'Details.WarehouseID.value',
            'Details.custom',
            'Details.files',
            'LocationID.value',
            'ShipmentNbr.value',
            'ShippingSettings',
            'ShippingSettings.id',
            'ShippingSettings.rowNumber',
            'ShippingSettings.note',
            'ShippingSettings.ShipToAddress',
            'ShippingSettings.ShipToAddress.id',
            'ShippingSettings.ShipToAddress.rowNumber',
            'ShippingSettings.ShipToAddress.note',
            'ShippingSettings.ShipToAddress.AddressLine1',
            'ShippingSettings.ShipToAddress.AddressLine2',
            'ShippingSettings.ShipToAddress.City',
            'ShippingSettings.ShipToAddress.Country',
            'ShippingSettings.ShipToAddress.PostalCode',
            'ShippingSettings.ShipToAddress.State',
            'ShippingSettings.ShipToAddress.custom',
            'ShippingSettings.ShipToAddress.files',
            'ShippingSettings.ShipToAddressOverride.value',
            'ShippingSettings.ShipToContact',
            'ShippingSettings.ShipToContactOverride.value',
            'ShippingSettings.custom',
            'ShippingSettings.files',
            'ShipVia.value',
            'Status.value',
            'WarehouseID.value',
            'custom.value',
            'files.value'
        ];
    }
}
