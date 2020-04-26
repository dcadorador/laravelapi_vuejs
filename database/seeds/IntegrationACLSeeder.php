<?php

use Illuminate\Database\Seeder;
use App\Models\Integration;
use App\Models\Account;
use Illuminate\Support\Facades\DB;
use App\Helpers\Helper;
use Illuminate\Support\Facades\Artisan;
use App\Models\IntegrationType;
use App\Models\MachshipStatusMapping;
use App\Models\FieldMapper;
use App\Models\ValueLookups;
use App\Models\IntegrationMeta;
use App\Models\IntegrationKey;
use App\Models\IntegrationSourceFilter;
use App\Repositories\IntegrationRepository;
use App\Libraries\Netsuite\NetsuiteHelpers\NetSuiteConstantHelper;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Libraries\Machship\Machship;

class IntegrationACLSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        // for testing purposes, we need to delete any existing data
        // user, accounts, integrations, integration_key, integration_meta, field_mapper, source filter, value_lookups
        User::where('name', DatabaseSeeder::ACL . ' Admin')->delete();
        Account::where('client_name', DatabaseSeeder::ACL)->delete();
        Integration::where('id', 1)->delete();
        IntegrationKey::where('integration_id', 1)->delete();
        IntegrationMeta::where('integration_id', 1)->delete();
        IntegrationSourceFilter::where('integration_id', 1)->delete();
        FieldMapper::where('integration_id', 1)->delete();
        ValueLookups::where('integration_id', 1)->delete();
        MachshipStatusMapping::where('integration_id', 1)->delete();

        // create a user for ACL
        $user = new User();
        $user->addRole('Admin');

        $user->name = DatabaseSeeder::ACL . ' Admin';
        $user->email = "john+test1@fusedsoftware.com";
        $user->password = Hash::make('1234qwer');
        $user->save();

        // create local account for ACL
        $account_data = [
            'client_name' => DatabaseSeeder::ACL,
            'client_notes' => 'DB Seed Insertion',
            'user_id' => $user->id,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        $account = Account::create($account_data);

        \Log::info('Done creating Account: ' . json_encode($account));

        $integration_type = IntegrationType::where('name', 'LIKE', 'Netsuite')->first();

        // create integration account for ACL
        $integration_data = [
            'id' => 1,
            'label' => DatabaseSeeder::ACL,
            'account_id' => $account->id,
            'integration_type_id' => $integration_type ? $integration_type->id : '1',
            'integration_status' => DatabaseSeeder::ACTIVE,
            'frequency_mins' => 60,
            'last_run' => null,
            'master_consignment_type' => Integration::CONSIGNMENT_TYPE_MANIFEST,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $integration = Integration::create($integration_data);

        \Log::info('Done creating Integration: ' . json_encode($integration));

        // machship status insertion based on use case for ACL
        $statuses = [
            'Pending' => 27,
            'Unmanifested' => 2,
            'Manifested' => 3,
            'Cancelled' => 10,
            'At Delivery' => 22,
            'Completed' => 7
        ];

        foreach ($statuses as $status => $id) {
            $machship_status_data = [
                'integration_id' => $integration->id,
                'machship_status_id' => $id,
                'machship_status' => $status,
                'record_status' => $status == 'Completed' ? 'COMPLETED' : 'PENDING_UPDATE',
                'update_source' => $status == 'Completed' || $status == 'At Delivery' ? 1 : 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            MachshipStatusMapping::create($machship_status_data);
        }

        // create default maps and configurations here
        // ==============================================================================
        $maps = [
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
                'machship_field' => 'customerReference2',
                'direction' => 'to_machship',
                'map_type' => 'direct',
                'source_field' => 'createdFrom.internalId',
                'data_conversion_type' => 'custom_function',
                'data_conversion_value' => 'getRelatedSalesOrder'
            ],
            [
                'machship_field' => 'carrierId',
                'direction' => 'to_machship',
                'map_type' => 'direct',
                'source_field' => 'shipMethod.internalId',
                'data_conversion_type' => 'lookup'
            ],
            [
                'machship_field' => 'carrierAccountId',
                'direction' => 'to_machship',
                'map_type' => 'direct',
                'source_field' => 'shipMethod.internalId',
                'data_conversion_type' => 'lookup',
            ],
            // from company location id
            [
                'machship_field' => 'fromCompanyLocationId',
                'direction' => 'to_machship',
                'map_type' => 'customfield',
                'source_field' => 'custbody_ifl',
                'data_conversion_type' => 'function',
                'data_conversion_value' => 'getFromCompanyLocationId'
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
                'source_field' => 'custbody_freight_account_number'
            ],
            [
                'machship_field' => 'carrierServiceId',
                'direction'     => 'to_machship',
                'map_type' => 'direct',
                'source_field' => 'shipMethod.internalId',
                'data_conversion_type' => 'lookup',
            ],
            [
                'machship_field' => 'toLocationId',
                'direction'     => 'to_machship',
                'map_type'      => 'direct_simple',
                'source_field'  => 'shippingAddress',
                'data_conversion_type' => 'custom_function',
                'data_conversion_value' => 'getLocationId'
            ],
        ];


        // iterate default maps to save
        // ==============================================================================

        foreach ($maps as $map) {
            $data = [
                'integration_id' => $integration->id,
                'data_direction' => $map['direction'],
                'machship_field' => $map['machship_field'],
                'map_type' => isset($map['map_type']) ? $map['map_type'] : null,
                'source_field' => isset($map['source_field']) ? $map['source_field'] : null,
                'data_conversion_type' => isset($map['data_conversion_type']) ? $map['data_conversion_type'] : null,
                'data_conversion_value' => isset($map['data_conversion_value']) ? $map['data_conversion_value'] : null,
            ];
            FieldMapper::create($data);
        }

        \Log::info('Done creating field mappers: ' . count($maps));


        // iterate default lookups to save
        // ==============================================================================
        $lookups = [
            /***********FROM COMPANY LOCATION ID*************/
            [
                'machship_field' => 'fromCompanyLocationId',
                'from_value' => 'Bundaberg',
                'to_value' => '814878'
            ],
            [
                'machship_field' => 'fromCompanyLocationId',
                'from_value' => 'Rockhampton',
                'to_value' => '814879'
            ],
            [
                'machship_field' => 'fromCompanyLocationId',
                'from_value' => 'Adelaide',
                'to_value' => '814876'
            ],
            [
                'machship_field' => 'fromCompanyLocationId',
                'from_value' => 'Brisbane',
                'to_value' => '814874'
            ],
            [
                'machship_field' => 'fromCompanyLocationId',
                'from_value' => 'Perth',
                'to_value' => '814877'
            ],
            [
                'machship_field' => 'fromCompanyLocationId',
                'from_value' => 'Sydney',
                'to_value' => '814873'
            ],
             /***********************************************/
        ];

        foreach ($lookups as $lookup) {
            $data = [
                'integration_id' => $integration->id,
                'machship_field' => $lookup['machship_field'],
                'from_value' => $lookup['from_value'],
                'from_label' => isset($lookup['from_label']) ? $lookup['from_label'] : null,
                'to_value' => isset($lookup['to_value']) ? $lookup['to_value'] : null,
                'to_label' => isset($lookup['to_label']) ? $lookup['to_label'] : null,
            ];
            ValueLookups::create($data);
        }

        \Log::info('Done creating field mappers: ' . count($maps));

        // default keys token
        // ==============================================================================

        IntegrationKey::create([
            'integration_id' => $integration->id,
            'key_type' => 'machship_token',
            'key_data' => 'OtI7ZuB2DkievBGONxnq9Q'
        ]);


        // default meta
        // ==============================================================================

        $demeta = [
            'NETSUITE_ENDPOINT' => '2019_1',
            'NETSUITE_ACCOUNT' => '3929178',
            'NETSUITE_CONSUMER_KEY' => '593f6595885eaccd23cdc6022b3a601d715fa39313b9680f243b526f1d1e0685',
            'NETSUITE_CONSUMER_SECRET' => 'a2fb03d9b9071ecda4c2609c28f6e15fe5b58b2029d315a0943eefe612a355ca',
            'NETSUITE_TOKEN' => '5ee5b18e00b2a6d772c4b255987d6d8ac1bfa78f4a5be978b426c1213786cfc1',
            'NETSUITE_TOKEN_SECRET' => 'fdda4bb07d5ba58c99c796b4d894077570353e6f16e9e2ff6f3f0a80aa4109f1',
            'NETSUITE_WEBSERVICES_HOST' => 'https://3929178.suitetalk.api.netsuite.com',
            'NETSUITE_APP_ID' => '6617FEF0-1428-424A-BEB4-4B82EE1736A0',
            'NETSUITE_OBJECT_TYPE' => NetSuiteConstantHelper::NETSUITE_ITEM_FULFILLMENT,
        ];

        // iterate default meta to save
        foreach ($demeta as $meta_key => $meta_value) {
            $data = [
                'integration_id' => $integration->id,
                'meta_key' => $meta_key,
                'meta_value' => $meta_value
            ];
            IntegrationMeta::create($data);
        }

        \Log::info('Done creating metas: ' . count($demeta));

        // default filter
        // ==============================================================================

        $dfilters = [
            'filter_1' => [
                'field' => 'type',
                'operator' => 'anyOf',
                'search_value' => '_itemFulfillment'
            ],
            // 'filter_2' => [
            //     'field' => 'dateCreated',
            //     'operator' => 'after',
            //     'search_value' => '-12 hours'
            // ],
            'filter_3' => [
                'field' => 'shipMethod',
                'operator' => 'noneOf',
                'search_value' => '7252',
            ],
            'filter_4' => [
                'field' => 'customFieldList',
                'custom_fields' => json_encode([
                    [
                        'method' => 'searchMultiSelectCustomField',
                        'operator' => 'anyOf',
                        'internal_id' => '2545',
                        'search' => [
                            'method' => 'listOrRecordRefField',
                            'search_value' => '1',
                        ]
                    ]
                ])
            ],

        ];
        foreach ($dfilters as $key => $filter) {
            if (is_array($filter)) {
                $parentId = null;
                foreach ($filter as $optionKey => $optionValue) {
                    $data = [
                        'integration_id' => $integration->id,
                        'filter_key' => $optionKey,
                        'filter_value' => $optionValue,
                        'integration_source_filter_id' => $parentId
                    ];

                    $sourceFilter = IntegrationSourceFilter::create($data);
                    if (empty($parentId)) {
                        $parentId = $sourceFilter->id;
                    }
                }
            } else {
                $data = [
                    'integration_id' => $integration->id,
                    'filter_key' => $key,
                    'filter_value' => $filter
                ];
                IntegrationSourceFilter::create($data);
            }
        }

        \Log::info('Done creating filters: ' . count($dfilters));

        // ==============================================================================

        // ValueLookups for carrier service
        // ==============================================================================
        $carriers = [
            [
                "carrier_id" => 936,
                "carrier_name" => "ACL Delivery",
                "carrier_service_id" => 5096,
                "carrier_service_name" => "General",
                "netsuite_ship_method_id" => 9552,
                "netsuite_ship_method_name" => "ACL Delivery",
            ],
            [
                "carrier_id" => 936,
                "carrier_name" => "ACL Delivery",
                "carrier_service_id" => 5096,
                "carrier_service_name" => "General",
                "netsuite_ship_method_id" => 7284,
                "netsuite_ship_method_name" => "ACL - Vernon",
            ],
            [
                "carrier_id" => 936,
                "carrier_name" => "ACL Delivery",
                "carrier_service_id" => 5096,
                "carrier_service_name" => "General",
                "netsuite_ship_method_id" => 18674,
                "netsuite_ship_method_name" => "ACL - Trevor",
            ],
            [
                "carrier_id" => 936,
                "carrier_name" => "ACL Delivery",
                "carrier_service_id" => 5096,
                "carrier_service_name" => "General",
                "netsuite_ship_method_id" => 21060,
                "netsuite_ship_method_name" => "ACL - Paul",
            ],
            [
                "carrier_id" => 936,
                "carrier_name" => "ACL Delivery",
                "carrier_service_id" => 5096,
                "carrier_service_name" => "General",
                "netsuite_ship_method_id" => 7285,
                "netsuite_ship_method_name" => "ACL - Michael",
            ],
            [
                "carrier_id" => 936,
                "carrier_name" => "ACL Delivery",
                "carrier_service_id" => 5096,
                "carrier_service_name" => "General",
                "netsuite_ship_method_id" => 11149,
                "netsuite_ship_method_name" => "ACL - Isaac",
            ],
            [
                "carrier_id" => 941,
                "carrier_name" => "Bundy Bullet",
                "carrier_service_id" => 5102,
                "carrier_service_name" => "General",
                "netsuite_ship_method_id" => 16357,
                "netsuite_ship_method_name" => "Bundy Bullet",
            ],
            [
                "carrier_id" => 1011,
                "carrier_name" => "Callide Dawson",
                "carrier_service_id" => 5589,
                "carrier_service_name" => "General",
                "netsuite_ship_method_id" => 17812,
                "netsuite_ship_method_name" => "Callide Dawson Couriers",
            ],
            [
                "carrier_id" => 938,
                "carrier_name" => "Brown's Express",
                "carrier_service_id" => 5099,
                "carrier_service_name" => "General",
                "netsuite_ship_method_id" => 10071,
                "netsuite_ship_method_name" => "Brown's Express",
            ],
            [
                "carrier_id" => 937,
                "carrier_name" => "AL-B's Courier Service",
                "carrier_service_id" => 5097,
                "carrier_service_name" => "General",
                "netsuite_ship_method_id" => 9204,
                "netsuite_ship_method_name" => "AL-B's Courier Service",
            ],
            [
                "carrier_id" => 947,
                "carrier_name" => "Agnes Water Xpress",
                "carrier_service_id" => 5110,
                "carrier_service_name" => "General",
                "netsuite_ship_method_id" => 31,
                "netsuite_ship_method_name" => "Agnes Water Xpress",
            ],
            [
                "carrier_id" => 979,
                "carrier_name" => "All Purpose",
                "carrier_service_id" => 5316,
                "carrier_service_name" => "General",
                "netsuite_ship_method_id" => 21565,
                "netsuite_ship_method_name" => "All Purpose Transport",
            ],
            [
                "carrier_id" => 513,
                "carrier_name" => "Australia Post",
                "carrier_service_id" => 3709,
                "carrier_service_name" => "PARCEL POST + SIGNATURE",
                "netsuite_ship_method_id" => 9096,
                "netsuite_ship_method_name" => "Australia Post",
            ],
            [
                "carrier_id" => 949,
                "carrier_name" => "Biloela Couriers",
                "carrier_service_id" => 5112,
                "carrier_service_name" => "General",
                "netsuite_ship_method_id" => 10191,
                "netsuite_ship_method_name" => "Biloela Couriers",
            ],
            [
                "carrier_id" => 446,
                "carrier_name" => "Toll Express",
                "carrier_service_id" => 1657,
                "carrier_service_name" => "General",
                "netsuite_ship_method_id" => 9502,
                "netsuite_ship_method_name" => "Toll NQX Transport",
            ],
            [
                "carrier_id" => 939,
                "carrier_name" => "Bundy Suparoo Express",
                "carrier_service_id" => 5100,
                "carrier_service_name" => "General",
                "netsuite_ship_method_id" => 33,
                "netsuite_ship_method_name" => "Bundy Suparoo Express",
            ],
            [
                "carrier_id" => 940,
                "carrier_name" => "Burnett Express",
                "carrier_service_id" => 5101,
                "carrier_service_name" => "General",
                "netsuite_ship_method_id" => 34,
                "netsuite_ship_method_name" => "Burnett Express",
            ],
            [
                "carrier_id" => 948,
                "carrier_name" => "Dawson Valley Couriers",
                "carrier_service_id" => 5111,
                "carrier_service_name" => "General",
                "netsuite_ship_method_id" => 16970,
                "netsuite_ship_method_name" => "Dawson Valley Couriers",
            ],
            [
                "carrier_id" => 628,
                "carrier_name" => "Fastway",
                "carrier_service_id" => 3655,
                "carrier_service_name" => "Parcel",
                "netsuite_ship_method_id" => 7297,
                "netsuite_ship_method_name" => "Fastway Couriers",
            ],
            [
                "carrier_id" => 950,
                "carrier_name" => "McKays Transport",
                "carrier_service_id" => 5113,
                "carrier_service_name" => "General",
                "netsuite_ship_method_id" => 40,
                "netsuite_ship_method_name" => "McKays Transport",
            ],
            [
                "carrier_id" => 942,
                "carrier_name" => "KB Tyre Solutions",
                "carrier_service_id" => 5103,
                "carrier_service_name" => "General",
                "netsuite_ship_method_id" => 21183,
                "netsuite_ship_method_name" => "KB Tyre Solutions",
            ],
            [
                "carrier_id" => 243,
                "carrier_name" => "Centurion Transport",
                "carrier_service_id" => 1446,
                "carrier_service_name" => "General",
                "netsuite_ship_method_id" => 19876,
                "netsuite_ship_method_name" => "Centurion Transport",
            ],
            [
                "carrier_id" => 540,
                "carrier_name" => "Followmont Transport",
                "carrier_service_id" => 3078,
                "carrier_service_name" => "General",
                "netsuite_ship_method_id" => 10078,
                "netsuite_ship_method_name" => "Followmont Transport",
            ],
            [
                "carrier_id" => 11,
                "carrier_name" => "TNT Express",
                "carrier_service_id" => 538,
                "carrier_service_name" => "Overnight Express",
                "netsuite_ship_method_id" => 7389,
                "netsuite_ship_method_name" => "TNT Overnight Express",
            ],
            [
                "carrier_id" => 11,
                "carrier_name" => "TNT Express",
                "carrier_service_id" => 540,
                "carrier_service_name" => "Road Express",
                "netsuite_ship_method_id" => 7298,
                "netsuite_ship_method_name" => "TNT Road Express",
            ],
            [
                "carrier_id" => 944,
                "carrier_name" => "Just Freight",
                "carrier_service_id" => 5107,
                "carrier_service_name" => "General",
                "netsuite_ship_method_id" => 18964,
                "netsuite_ship_method_name" => "Just Freight",
            ],
            [
                "carrier_id" => 952,
                "carrier_name" => "Zippy Couriers",
                "carrier_service_id" => 5115,
                "carrier_service_name" => "General",
                "netsuite_ship_method_id" => 9826,
                "netsuite_ship_method_name" => "Zippy Couriers",
            ],
            [
                "carrier_id" => 955,
                "carrier_name" => "W & W Freight",
                "carrier_service_id" => 5120,
                "carrier_service_name" => "General",
                "netsuite_ship_method_id" => 7251,
                "netsuite_ship_method_name" => "W & W Freight",
            ],
            [
                "carrier_id" => 673,
                "carrier_name" => "Time Express",
                "carrier_service_id" => 3699,
                "carrier_service_name" => "General",
                "netsuite_ship_method_id" => 10999,
                "netsuite_ship_method_name" => "Time Express Couriers",
            ],
            [
                "carrier_id" => 953,
                "carrier_name" => "Prozac Couriers",
                "carrier_service_id" => 5116,
                "carrier_service_name" => "General",
                "netsuite_ship_method_id" => 16683,
                "netsuite_ship_method_name" => "Prozac Couriers",
            ],
            [
                "carrier_id" => 951,
                "carrier_name" => "GPY - Brown Courier",
                "carrier_service_id" => 5114,
                "carrier_service_name" => "General",
                "netsuite_ship_method_id" => 18391,
                "netsuite_ship_method_name" => "GPY - Brown Courier",
            ],
            [
                "carrier_id" => 954,
                "carrier_name" => "Gladrock Transport",
                "carrier_service_id" => 5117,
                "carrier_service_name" => "General",
                "netsuite_ship_method_id" => 17077,
                "netsuite_ship_method_name" => "Gladrock Transport",
            ],
            [
                "carrier_id" => 946,
                "carrier_name" => "Gin Gin Road-Run-A Courier",
                "carrier_service_id" => 5109,
                "carrier_service_name" => "General",
                "netsuite_ship_method_id" => 39,
                "netsuite_ship_method_name" => "Gin Gin Road–Run–A Courier",
            ],
            [
                "carrier_id" => 945,
                "carrier_name" => "Gin Gin Carrying Co",
                "carrier_service_id" => 5108,
                "carrier_service_name" => "General",
                "netsuite_ship_method_id" => 38,
                "netsuite_ship_method_name" => "Gin Gin Carrying Co.",
            ],
            [
                "carrier_id" => 970,
                "carrier_name" => "Transit Couriers",
                "carrier_service_id" => null,
                "carrier_service_name" => null,
                "netsuite_ship_method_id" => 11256,
                "netsuite_ship_method_name" => "Transit Couriers",
            ],
            [
                "carrier_id" => 956,
                "carrier_name" => "Wide Bay Roadmaster Express",
                "carrier_service_id" => 5121,
                "carrier_service_name" => "General",
                "netsuite_ship_method_id" => 44,
                "netsuite_ship_method_name" => "Wide Bay Roadmaster Express",
            ],
            [
                "carrier_id" => 513,
                "carrier_name" => "Australia Post",
                "carrier_service_id" => 3712,
                "carrier_service_name" => "EXPRESS POST + SIGNATURE",
                "netsuite_ship_method_id" => 11311,
                "netsuite_ship_method_name" => "Express Post",
            ]
        ];

        $lookups = [];
        $token = $integration->getMachshipTokenKey();

        if (!empty($token)) {
            $machship = new Machship($token);

            foreach ($carriers as $carrier) {
                $accounts = $machship->getCarrierAccounts($carrier['carrier_id']);

                // machship field carrierServiceId
                $lookups[] = [
                    'integration_id' => $integration->id,
                    'machship_field' => 'carrierServiceId',
                    'from_value' => $carrier['netsuite_ship_method_id'],
                    'from_label' => $carrier['netsuite_ship_method_name'],
                    'to_value' => $carrier['carrier_service_id'],
                    'to_label' => $carrier['carrier_service_name']
                ];

                // machship field carrierId
                $lookups[] = [
                    'integration_id' => $integration->id,
                    'machship_field' => 'carrierId',
                    'from_value' => $carrier['netsuite_ship_method_id'],
                    'from_label' => $carrier['netsuite_ship_method_name'],
                    'to_value' => $carrier['carrier_id'],
                    'to_label' => $carrier['carrier_name']
                ];

                // machship field carrierAccountId
                if ($accounts && !is_null($accounts->object) && count($accounts->object)) {
                    $lookups[] = [
                        'integration_id' => $integration->id,
                        'machship_field' => 'carrierAccountId',
                        'from_value' => $carrier['netsuite_ship_method_id'],
                        'from_label' => $carrier['netsuite_ship_method_name'],
                        'to_value' => $accounts->object[0]->id,
                        'to_label' => ''
                    ];
                }
            }
            ValueLookups::insert($lookups);
            \Log::info('Done creating lookup values: ' . count($lookups));
        } else {
            \Log::ifno('Machship fail, rmpty token');
        }

        // create the integration custom function
        Artisan::call('make:integration-custom-function', [ 'client' => $account->id ]);
    }
}
