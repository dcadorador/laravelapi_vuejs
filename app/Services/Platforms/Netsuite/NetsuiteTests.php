<?php
namespace App\Services\Platforms\Netsuite;

trait NetsuiteTests
{

    protected function getTest()
    {
        return [
            [
              "createdDate" => "2020-02-17T13:58:41.000-08:00",
              "lastModifiedDate" => "2020-02-17T14:00:24.000-08:00",
              "customForm" => null,
              "postingPeriod" => null,
              "entity" => [
                 "internalId" => "3107",
                 "externalId" => null,
                 "type" => null,
                 "name" => "Chrome Engineering"
              ],
              "createdFrom" => [
                 "internalId" => "1424324",
                 "externalId" => null,
                 "type" => null,
                 "name" => "Sales Order #SO36480"
              ],
              "requestedBy" => null,
              "createdFromShipGroup" => null,
              "partner" => null,
              "shippingAddress" => [
                 "internalId" => "23128",
                 "country" => null,
                 "attention" => null,
                 "addressee" => "Chrome Engineering",
                 "addrPhone" => null,
                 "addr1" => "62 Industrial Avenue",
                 "addr2" => null,
                 "addr3" => null,
                 "city" => "MARYBOROUGH",
                 "state" => "QLD",
                 "zip" => "4650",
                 "addrText" => "Chrome Engineering<br>62 Industrial Avenue<br>MARYBOROUGH QLD 4650",
                 "override" => false,
                 "customFieldList" => null,
                 "nullFieldList" => null
              ],
              "pickedDate" => null,
              "packedDate" => null,
              "shippedDate" => null,
              "shipIsResidential" => false,
              "shipAddressList" => null,
              "shipStatus" => null,
              "saturdayDeliveryUps" => null,
              "sendShipNotifyEmailUps" => null,
              "sendBackupEmailUps" => null,
              "shipNotifyEmailAddressUps" => null,
              "shipNotifyEmailAddress2Ups" => null,
              "backupEmailAddressUps" => null,
              "shipNotifyEmailMessageUps" => null,
              "thirdPartyAcctUps" => null,
              "thirdPartyZipcodeUps" => null,
              "thirdPartyCountryUps" => null,
              "thirdPartyTypeUps" => null,
              "partiesToTransactionUps" => null,
              "exportTypeUps" => null,
              "methodOfTransportUps" => null,
              "carrierIdUps" => null,
              "entryNumberUps" => null,
              "inbondCodeUps" => null,
              "isRoutedExportTransactionUps" => null,
              "licenseNumberUps" => null,
              "licenseDateUps" => null,
              "licenseExceptionUps" => null,
              "eccNumberUps" => null,
              "recipientTaxIdUps" => null,
              "blanketStartDateUps" => null,
              "blanketEndDateUps" => null,
              "shipmentWeightUps" => null,
              "saturdayDeliveryFedEx" => null,
              "saturdayPickupFedex" => null,
              "sendShipNotifyEmailFedEx" => null,
              "sendBackupEmailFedEx" => null,
              "signatureHomeDeliveryFedEx" => null,
              "shipNotifyEmailAddressFedEx" => null,
              "backupEmailAddressFedEx" => null,
              "shipDateFedEx" => null,
              "homeDeliveryTypeFedEx" => null,
              "homeDeliveryDateFedEx" => null,
              "bookingConfirmationNumFedEx" => null,
              "intlExemptionNumFedEx" => null,
              "b13aFilingOptionFedEx" => null,
              "b13aStatementDataFedEx" => null,
              "thirdPartyAcctFedEx" => null,
              "thirdPartyCountryFedEx" => null,
              "thirdPartyTypeFedEx" => null,
              "shipmentWeightFedEx" => null,
              "termsOfSaleFedEx" => null,
              "termsFreightChargeFedEx" => null,
              "termsInsuranceChargeFedEx" => null,
              "insideDeliveryFedEx" => null,
              "insidePickupFedEx" => null,
              "ancillaryEndorsementFedEx" => null,
              "holdAtLocationFedEx" => null,
              "halPhoneFedEx" => null,
              "halAddr1FedEx" => null,
              "halAddr2FedEx" => null,
              "halAddr3FedEx" => null,
              "halCityFedEx" => null,
              "halZipFedEx" => null,
              "halStateFedEx" => null,
              "halCountryFedEx" => null,
              "hazmatTypeFedEx" => null,
              "accessibilityTypeFedEx" => null,
              "isCargoAircraftOnlyFedEx" => null,
              "tranDate" => "2020-02-17T06:00:00.000-08:00",
              "tranId" => "IF44453",
              "shipMethod" => [
                 "internalId" => "34",
                 "externalId" => null,
                 "type" => null,
                 "name" => "Burnett Express"
              ],
              "generateIntegratedShipperLabel" => null,
              "shippingCost" => null,
              "handlingCost" => null,
              "memo" => null,
              "transferLocation" => null,
              "packageList" => [
                 "package" => [
                    [
                       "packageWeight" => 101.875,
                       "packageDescr" => null,
                       "packageTrackingNumber" => null
                    ]
                 ],
                 "replaceAll" => true
              ],
              "packageUpsList" => null,
              "packageUspsList" => null,
              "packageFedExList" => null,
              "itemList" => [
                 "item" => [
                    [
                       "jobName" => null,
                       "itemReceive" => true,
                       "itemName" => "LCH-HC-20L",
                       "description" => "CT Citra Grit Hand Cleaner 20L",
                       "department" => null,
                       "class" => null,
                       "location" => [
                          "internalId" => "1",
                          "externalId" => null,
                          "type" => null,
                          "name" => "BUNDABERG"
                       ],
                       "onHand" => 0,
                       "quantity" => 2,
                       "unitsDisplay" => "DRM",
                       "createPo" => null,
                       "inventoryDetail" => null,
                       "binNumbers" => null,
                       "serialNumbers" => null,
                       "poNum" => null,
                       "item" => [
                          "internalId" => "9869",
                          "externalId" => null,
                          "type" => null,
                          "name" => "LCH-HC-20L"
                       ],
                       "orderLine" => 2,
                       "quantityRemaining" => null,
                       "options" => null,
                       "shipGroup" => null,
                       "itemIsFulfilled" => null,
                       "shipAddress" => null,
                       "shipMethod" => null,
                       "customFieldList" => [
                          "customField" => [
                             [
                                "value" => "LCH-HC-20L",
                                "internalId" => "1385",
                                "scriptId" => "custcol_item_code"
                             ],
                             [
                                "value" => "https:\/\/system.netsuite.com\/core\/media\/media.nl?id=555557&c=3929178&h=5af27e554085c0503954",
                                "internalId" => "2383",
                                "scriptId" => "custcol_item_image_url"
                             ]
                          ]
                       ]
                    ],
                    [
                       "jobName" => null,
                       "itemReceive" => true,
                       "itemName" => "XTD-SDT4830",
                       "description" => "Duct Tape PVC 48mm x 30m Grey",
                       "department" => null,
                       "class" => null,
                       "location" => [
                          "internalId" => "1",
                          "externalId" => null,
                          "type" => null,
                          "name" => "BUNDABERG"
                       ],
                       "onHand" => 23,
                       "quantity" => 36,
                       "unitsDisplay" => "RL",
                       "createPo" => null,
                       "inventoryDetail" => null,
                       "binNumbers" => null,
                       "serialNumbers" => null,
                       "poNum" => null,
                       "item" => [
                          "internalId" => "7456",
                          "externalId" => null,
                          "type" => null,
                          "name" => "XTD-SDT4830"
                       ],
                       "orderLine" => 4,
                       "quantityRemaining" => null,
                       "options" => null,
                       "shipGroup" => null,
                       "itemIsFulfilled" => null,
                       "shipAddress" => null,
                       "shipMethod" => null,
                       "customFieldList" => [
                          "customField" => [
                             [
                                "value" => "XTD-SDT4830",
                                "internalId" => "1385",
                                "scriptId" => "custcol_item_code"
                             ]
                          ]
                       ]
                    ],
                    [
                       "jobName" => null,
                       "itemReceive" => true,
                       "itemName" => "XPP-PTRT090",
                       "description" => "Paper Towel Rolls Tork 90m Roll 16rolls\/ctn",
                       "department" => null,
                       "class" => null,
                       "location" => [
                          "internalId" => "1",
                          "externalId" => null,
                          "type" => null,
                          "name" => "BUNDABERG"
                       ],
                       "onHand" => 288,
                       "quantity" => 96,
                       "unitsDisplay" => "RL",
                       "createPo" => null,
                       "inventoryDetail" => null,
                       "binNumbers" => null,
                       "serialNumbers" => null,
                       "poNum" => null,
                       "item" => [
                          "internalId" => "16488",
                          "externalId" => null,
                          "type" => null,
                          "name" => "XPP-PTRT090"
                       ],
                       "orderLine" => 5,
                       "quantityRemaining" => null,
                       "options" => null,
                       "shipGroup" => null,
                       "itemIsFulfilled" => null,
                       "shipAddress" => null,
                       "shipMethod" => null,
                       "customFieldList" => [
                          "customField" => [
                             [
                                "value" => "XPP-PTRT090",
                                "internalId" => "1385",
                                "scriptId" => "custcol_item_code"
                             ]
                          ]
                       ]
                    ]
                 ],
                 "replaceAll" => true
              ],
              "accountingBookDetailList" => null,
              "customFieldList" => [
                 "customField" => [
                    [
                       "value" => [
                          "name" => "FIS [Always]",
                          "internalId" => "8",
                          "externalId" => null,
                          "typeId" => "199"
                       ],
                       "internalId" => "1551",
                       "scriptId" => "custbody12"
                    ],
                    [
                       "value" => "37575",
                       "internalId" => "2323",
                       "scriptId" => "custbody25"
                    ],
                    [
                       "value" => "BUNDABERG",
                       "internalId" => "2324",
                       "scriptId" => "custbody26"
                    ],
                    [
                       "value" => "Leianda",
                       "internalId" => "2325",
                       "scriptId" => "custbody27"
                    ],
                    [
                       "value" => [
                          "name" => "Net 30 EOM",
                          "internalId" => "7",
                          "externalId" => null,
                          "typeId" => "-199"
                       ],
                       "internalId" => "1417",
                       "scriptId" => "custbody3"
                    ],
                    [
                       "value" => false,
                       "internalId" => "1822",
                       "scriptId" => "custbody_batch_numbers_recorded"
                    ],
                    [
                       "value" => 88,
                       "internalId" => "2471",
                       "scriptId" => "custbody_box_1_height"
                    ],
                    [
                       "value" => 115,
                       "internalId" => "2469",
                       "scriptId" => "custbody_box_1_length"
                    ],
                    [
                       "value" => 1,
                       "internalId" => "2468",
                       "scriptId" => "custbody_box_1_qty"
                    ],
                    [
                       "value" => [
                          "name" => "Pallet",
                          "internalId" => "4",
                          "externalId" => null,
                          "typeId" => "359"
                       ],
                       "internalId" => "2473",
                       "scriptId" => "custbody_box_1_type"
                    ],
                    [
                       "value" => 100,
                       "internalId" => "2472",
                       "scriptId" => "custbody_box_1_weight"
                    ],
                    [
                       "value" => 112,
                       "internalId" => "2470",
                       "scriptId" => "custbody_box_1_width"
                    ],
                    [
                       "value" => false,
                       "internalId" => "3207",
                       "scriptId" => "custbody_celigo_etail_order_fulfilled"
                    ],
                    [
                       "value" => false,
                       "internalId" => "2462",
                       "scriptId" => "custbody_contains_dgs"
                    ],
                    [
                       "value" => "rrwlhard@bigpond.net.au",
                       "internalId" => "2517",
                       "scriptId" => "custbody_despatch_notif_email"
                    ],
                    [
                       "value" => false,
                       "internalId" => "2467",
                       "scriptId" => "custbody_do_not_send_machship"
                    ],
                    [
                       "value" => [
                          "name" => "BUNDABERG",
                          "internalId" => "1",
                          "externalId" => null,
                          "typeId" => "-103"
                       ],
                       "internalId" => "2502",
                       "scriptId" => "custbody_ifl"
                    ],
                    [
                       "value" => false,
                       "internalId" => "3237",
                       "scriptId" => "custbody_inv_wf_has_run"
                    ],
                    [
                       "value" => [
                          "name" => "PROCESSING",
                          "internalId" => "2",
                          "externalId" => null,
                          "typeId" => "365"
                       ],
                       "internalId" => "2545",
                       "scriptId" => "custbody_machship_status"
                    ],
                    [
                       "value" => "MS04086191",
                       "internalId" => "2565",
                       "scriptId" => "custbody_ms_tracking_number"
                    ]
                 ]
              ],
              "internalId" => "1431439",
              "externalId" => null,
              "nullFieldList" => null
            ]
        ];
    }
}
