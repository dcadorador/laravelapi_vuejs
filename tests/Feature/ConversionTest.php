<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Services\DataConversionService;
use App\Services\FieldMapService;
use App\Models\Integration;
use App\Models\FieldMapper;
use App\Models\ValueLookups;

class ConversionTest extends TestCase
{
    /**
     * @var $platform
     */
    protected $platform;

    /**
     * @var $integration_field_mappers
     */
    protected $integration_field_mappers;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testExample()
    {
         $this->assertTrue(true);
    }

    public function testIntegrationConvertion()
    {
        // $lookupValue = factory(ValueLookups::class)->make();
        // $integrations = factory(Integration::class, 1)
        //                 ->make([
        //                     'valueLookup' => $lookupValue
        //                 ]);
        // $integration= $integrations->first();

        // $fields = [
        //     [
        //         "integration_id" => $integration->id,
        //         "machship_field" => "company",
        //         "map_type" => "skip",
        //         "source_field" => "",
        //         'data_conversion_type' => 'constant',
        //         'data_conversion_value' => 'fusedsoftware'
        //     ],
        //     [
        //         "integration_id" => $integration->id,
        //         "machship_field" => "toEmail",
        //         "map_type" => "customfield",
        //         "source_field" => "custbody_despatch_notif_email"
        //     ],
        //     [
        //         "integration_id" => $integration->id,
        //         "machship_field" => "toLocation.suburb",
        //         "map_type" => "direct",
        //         "source_field" => "shippingAddress.city"
        //     ],
        //     [
        //         "integration_id" => $integration->id,
        //         "machship_field" => "items",
        //         "map_type" => "array",
        //         "source_field" => "itemList.item.*.itemName",
        //         'data_conversion_type' => 'function',
        //         'data_conversion_value' => 'testConversion'
        //     ]
        // ];
        
        // foreach ($fields as $field) {
        //     $integration_field_mappers[] = factory(FieldMapper::class)->make($field);
        // }
        
        // $params = [
        //     "NETSUITE_ENDPOINT" => "2019_1",
        //     "NETSUITE_ACCOUNT" => "4984423",
        //     "NETSUITE_CONSUMER_KEY" => "6e250419dc1e21babd5c5fdf81a44ea03fc5916a6fca0441954b6ba30869214d",
        //     "NETSUITE_CONSUMER_SECRET" => "b146ef5a3b109c7346d7c0cb9a92d1cc2a292ed046462aa60ddbd0c9abb9a167",
        //     "NETSUITE_TOKEN" => "73b9a8083c775e86eb440b05d964e5d705d8d1706ea182c8cf7ed45f7c509d66",
        //     "NETSUITE_TOKEN_SECRET" => "18c1fdbc159d91484b85b0b1e6456d3f4d234441fa1e447c80028a40eb6ea892",
        //     "NETSUITE_WEBSERVICES_HOST" => "https://4984423-sb1.app.netsuite.com/",
        //     "NETSUITE_APP_ID" => "91EBF1DB-9878-47BA-9147-C3B3C75677BB"
        // ];
        // $this->platform = new \App\Services\Platforms\Netsuite\NetsuiteService;
        // $this->platform->init($params);
        // $this->platform->setIntegration($integration);
        // $field_map_service = new FieldMapService($this->platform);
        // $data_conversion_service = new DataConversionService($integration);

        // $integration_data = $this->platform->getTest();

        // $data = json_decode(json_encode($integration_data), true);
        // $field_map_service->setSourceData($data[0]);

        // $expected_result = [
        //     "company" => "fusedsoftware",
        //     "toEmail" => "rrwlhard@bigpond.net.au",
        //     "toLocation.suburb" => "MARYBOROUGH",
        //     "items" => [
        //             0 => [
        //                 "name" => "LCH-HC-20L"
        //             ],
        //             1 => [
        //                 "name" => "XTD-SDT4830"
        //             ],
        //             2 => [
        //                 "name" => "XPP-PTRT090"
        //             ]
        //         ]
        //     ];

        // foreach ($integration_data as $data) {
        //     $machship_data = [];
        //     foreach ($integration_field_mappers as $field) {
        //         $machship_field = $field['machship_field'];
        //         $machship_data[$machship_field] = $field_map_service->getMappedByField($field);
        //         $machship_data[$machship_field] = $data_conversion_service->getConverstionData($field, $machship_data[$machship_field]);
        //     }
        //     $this->assertEquals($machship_data, $expected_result);
        // }
    }
}
