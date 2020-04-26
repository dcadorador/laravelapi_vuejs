<?php
namespace App\Services\Platforms\DEAR\CustomFunctions;

use App\Models\DebugLogs;
use GuzzleHttp\Client;

trait integration_4_custom_functions
{
    /**
     * This function is to set the machship customer reference
     * with format OrderNumber + '-' + FulfillmentNumber
     * @return string|null
     */
    public function integration_4_getCustomerRef($data)
    {
        $current_record = $this->getCurrentRecord();
        $raw_record_data = $current_record->source_data;

        if (isset($raw_record_data['SaleList']) &&
            isset($raw_record_data['Fulfilments']) &&
            isset($raw_record_data['SaleList']['OrderNumber']) &&
            isset($raw_record_data['Fulfilments']['FulfillmentNumber'])
        ) {
            return $raw_record_data['SaleList']['OrderNumber'] . '-' . $raw_record_data['Fulfilments']['FulfillmentNumber'];
        }

        return $data;
    }

    public function integration_4_preUpdateSourceData()
    {
        $record = $this->getCurrentRecord();
        $source_raw = $record->source_data;
        $consignment = $this->getData()['consignment'];

        $data = [
            'order_number' => $source_raw['SaleList']['OrderNumber'],
            'fulfilment_number' => $source_raw['Fulfilments']['FulfillmentNumber'],
            'carrier' => $consignment['carrierName'],
            'service_level' => $consignment['carrierService']['name'],
            'consignment_id' => $consignment['carrierConsignmentId'],
            'tracking_url' => 'https://live.machship.com/tracking/#/consignments/' . $consignment['trackingPageAccessToken'],
            'tracking_number' => $consignment['carrierConsignmentId'],
            'eta' => $consignment['eta'],
            'despatchDateLocal' => $consignment['despatchDateLocal'],
        ];

        // send request to Blue Mountain hook
        $client = new Client(['base_uri' => 'https://bmco.growthpath.com.au/cached_dear/' ]);
        $response = $client->post('machship_dispatch_webhook1', [
           'headers' => [
               'Content-Type' => 'application/json'
            ],
           'query' => [
                    'access_token' => '1256100134'
               ],
           'json' => $data
        ]);

        $response = json_decode($response->getBody()->getContents(), true);
        $this->setCustomFunctionResult($response);

        $this->createSyncLog(DebugLogs::STEP_WF_5 . ' - ' . self::UPDATE_HOOK, $data, $response);
    }
}
