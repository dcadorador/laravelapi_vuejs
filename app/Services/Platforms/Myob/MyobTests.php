<?php
namespace App\Services\Platforms\Myob;

trait MyobTests
{
    protected function getTest()
    {
        \Log::info('get testing here');
        return json_decode('[
            {
                "id":"3b5dcca3-0cd0-4131-8392-b9b014a318d1",
                "rowNumber":1,
                "note":"Hey Nick,\nDelivery: Friday 6/03/2020 (Morning Delivery - Around 8am)\n4x Items\nRef: 00091972\nOasis Homes and Developments Pty Ltd\nNaz - 0413 969 935\n10 Kelan St\nClyde North VIC\nComments:\nCall customer to confirm time on Friday",
                "CreatedDateTime":{"value":"2020-03-03T00:21:59.168282+00:00"},
                "CustomerID":{"value":"019288"},
                "Details":[
                    {"id":"5b2eefac-e4df-44d2-a79d-16d6f6cb1fa2","rowNumber":1,"note":"","Description":{"value":"90CM OVEN"},"ExpirationDate":{},"FreeItem":{"value":false},"InventoryID":{"value":"DEOR90A"},"LineNbr":{"value":1},"LocationID":{"value":"ROWVILLE"},"LotSerialNbr":{"value":""},"OpenQty":{"value":0.0},"OrderedQty":{"value":1.000000},"OrderLineNbr":{"value":1},"OrderNbr":{"value":"00091972"},"OrderType":{"value":"SO"},"OriginalQty":{},"ReasonCode":{},"ShippedQty":{"value":1.000000},"UOM":{"value":"EACH"},"WarehouseID":{"value":"MELBOURNE"},"custom":{},"files":[]}
                ],
                "LocationID": {
                    "value": "MAIN"
                },
                "ShipmentNbr": {
                    "value": "019390"
                },
                "ShippingSettings": {
                    "id": "b453cbd3-f3a7-4f5d-b476-9d06da016df4",
                    "rowNumber": 1,
                    "note": null,
                    "ShipToAddress": {
                        "id": "967626a9-f042-4f5a-a201-20f6436f9cdf",
                        "rowNumber": 1,
                        "note": null,
                        "AddressLine1": {
                            "value": "10 Kelan St"
                        },
                        "AddressLine2": {},
                        "City": {
                            "value": "Clyde North"
                        },
                        "Country": {
                            "value": "AU"
                        },
                        "PostalCode": {
                            "value": "3198"
                        },
                        "State": {
                            "value": "VIC"
                        },
                        "custom": {},
                        "files": []
                    },
                    "ShipToAddressOverride": {
                        "value": true
                    },
                    "ShipToContact": {
                        "id": "6be0efe8-e753-4d2a-8563-cce54cd2dc81",
                        "rowNumber": 1,
                        "note": null,
                        "Attention": {
                            "value": "Naz"
                        },
                        "BusinessName": {
                            "value": "Oasis Homes and Developments Pty Ltd"
                        },
                        "Email": {
                            "value": "oasishome48@gmail.com"
                        },
                        "Phone1": {
                            "value": "0413 969 935"
                        },
                        "custom": {},
                        "files": []
                    },
                    "ShipToContactOverride": {
                        "value": false
                    },
                    "custom": {},
                    "files": []
                },
                "ShipVia": {
                    "value": "LOCAL"
                },
                "Status": {
                    "value": "Completed"
                },
                "WarehouseID": {
                    "value": "MELBOURNE"
                },
                "custom": {},
                "files": []}]');
    }
}
