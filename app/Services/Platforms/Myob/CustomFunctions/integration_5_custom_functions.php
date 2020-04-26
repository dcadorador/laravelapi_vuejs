<?php
namespace App\Services\Platforms\Myob\CustomFunctions;

use App\Helpers\Helper;

trait integration_5_custom_functions
{

    public function integration_5_preUpdateSourceData()
    {
        $source_raw = $this->getCurrentRecord()->source_data;
        $consignment = $this->getData()['consignment'];

        $description = '';
        $description .= $consignment['companyCarrierAccount']['name'] .
            ' - ' . $consignment['carrierService']['name'];

        $shipment_number = $source_raw['ShipmentNbr']['value'];
        $details = $source_raw['Details'][0];

        // PUT https://domainappliances.myobadvanced.com/entity/Default/18.200.001/Shipment/?$expand=Packages&$filter=ShipmentNbr eq '(Shipment number from original Shipment)'
        $filter = urlencode('ShipmentNbr eq \''. $shipment_number .'\'');
        $expand = urlencode('Packages');
        $params = '$filter=' . $filter . '&$expand=' . $expand;
        $data = [
            'CustomerID' => $source_raw['CustomerID'],
            'WarehouseID' => array_key_exists('WarehouseID', $details) ? $details['WarehouseID'] : null,
            'Status' => [ 'value' => 'Confirmed' ],
            'Packages' => [
                [
                    'rowNumber' => [
                        'value' => 1
                    ], // fixed
                    'BoxID' => [
                        'value' => 'TRACKING'
                    ], // fixed
                    'Confirmed' => [
                        'value' => true
                    ], // fixed
                    'CustomRefNbr1' => [
                        'value' => $consignment['consignmentNumber']
                    ], // cannote/carrier ID
                    'Description' => [
                        'value' => $description
                    ], // carrier + service
                    'TrackingNbr' => [
                            'value' => $consignment['carrierConsignmentId']
                    ], // MS Number
                ]
            ]
        ];

        $status_data = [
            'entity' => [
                'Type' => [
                    'value' => 'Shipment'
                ],
                'ShipmentNbr' => [
                    'value' => $shipment_number
                ]
            ]
        ];

        $result = [
            'data' => $data,
            'parameters' => $params,
            'status_data' => $status_data,
        ];
        $this->setCustomFunctionResult($result);
    }

    /**
     * @param $data
     * @return |null |string
     */
    public function integration_5_getContactRef($data)
    {
        $result = null;
        $current_record = $this->getCurrentRecord();
        $raw_record_data = $current_record->source_data;

        if (array_key_exists('value', $raw_record_data['ShippingSettings']['ShipToContact']['Attention'])) {
            $result = $raw_record_data['ShippingSettings']['ShipToContact']['Attention']['value'];
        } else {
            $result = $raw_record_data['ShippingSettings']['ShipToContact']['BusinessName']['value'];
        }

        return $result;
    }
}
