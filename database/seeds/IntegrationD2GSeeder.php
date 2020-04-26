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
use App\Models\IntegrationMeta;
use App\Models\IntegrationKey;
use App\Models\IntegrationSourceFilter;
use App\Repositories\IntegrationRepository;
use App\Libraries\Netsuite\NetsuiteHelpers\NetSuiteConstantHelper;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class IntegrationD2GSeeder extends Seeder
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
        User::where('name', DatabaseSeeder::D2G . ' Admin')->delete();
        Account::where('client_name', DatabaseSeeder::D2G)->delete();
        Integration::where('id', 3)->delete();
        IntegrationKey::where('integration_id', 3)->delete();
        IntegrationMeta::where('integration_id', 3)->delete();
        IntegrationSourceFilter::where('integration_id', 3)->delete();
        FieldMapper::where('integration_id', 3)->delete();
        // ValueLookups::where('integration_id', 3)->delete();
        MachshipStatusMapping::where('integration_id', 3)->delete();

        $user = new User();
        $user->addRole('Admin');

        $user->name = DatabaseSeeder::D2G . ' Admin';
        $user->email = "john+test3@fusedsoftware.com";
        $user->password = Hash::make('1234qwer');
        $user->save();

        // create local account for D2G
        $account_data = [
            'client_name' => DatabaseSeeder::D2G,
            'client_notes' => 'DB Seed Insertion',
            'user_id' => $user->id,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        $account = Account::create($account_data);

        $integration_type = IntegrationType::where('name', 'LIKE', 'Netsuite')->first();

        $integration_data = [
            'id' => 3,
            'label' => DatabaseSeeder::D2G,
            'account_id' => $account->id,
            'integration_type_id' => $integration_type->id,
            'integration_status' => DatabaseSeeder::ACTIVE,
            'frequency_mins' => 30,
            'last_run' => null,
            'master_consignment_type' => Integration::CONSIGNMENT_TYPE_PENDING,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $integration = Integration::create($integration_data);

        \Log::info('Done creating Integration: ' . json_encode($integration));

        // machship status insertion based on use case for D2G
        $statuses = [
            'Pending' => 27,
            'Unmanifested' => 2,
            'Manifested' => 3
        ];

        foreach ($statuses as $status => $id) {
            $machship_status_data = [
                'integration_id' => $integration->id,
                'machship_status_id' => $id,
                'machship_status' => $status,
                'record_status' => $status == 'Manifested' ? 'COMPLETED' : 'PENDING_UPDATE',
                'update_source' => $status == 'Manifested' ? 1 : 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            MachshipStatusMapping::create($machship_status_data);
        }

        // Create default maps and configurations here
        // ==============================================================================
        $maps = [
            [
                'machship_field' => 'companyId',
                'direction' => 'to_machship',
                'map_type' => 'skip',
                'source_field' => '',
                'data_conversion_type' => 'constant',
                'data_conversion_value' => 4470
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
                'machship_field' => 'toLocation.suburb',
                'direction' => 'to_machship',
                'map_type' => 'direct',
                'source_field' => 'shippingAddress.city',
            ],
            [
                'machship_field' => 'toLocation.postcode',
                'direction' => 'to_machship',
                'map_type' => 'direct',
                'source_field' => 'shippingAddress.city',
            ],
            [
                'machship_field' => 'fromCompanyLocationId',
                'direction' => 'to_machship',
                'map_type' => 'skip',
                'data_conversion_type' => 'constant',
                'data_conversion_value' => 988484
            ],
            [
                'machship_field' => 'toName',
                'direction' => 'to_machship',
                'map_type' => 'direct',
                'source_field' => 'shippingAddress.addressee'
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
                'machship_field' => 'customerReference2',
                'direction' => 'to_machship',
                'map_type' => 'direct',
                'source_field' => 'otherRefNum'
            ],
        ];


        // Iterate default maps to save
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


        // Default keys token
        // ==============================================================================

        IntegrationKey::create([
            'integration_id' => $integration->id,
            'key_type' => 'machship_token',
            'key_data' => '22vVy8GpMk28ab8WcQg0Tw'
        ]);


        // Default meta
        // ==============================================================================

        $demeta = [
            'NETSUITE_ENDPOINT' => '2019_1',
            'NETSUITE_ACCOUNT' => '4984423',
            'NETSUITE_CONSUMER_KEY' => '12245a7739775386d686a5b1e9107b3b7ae9d457a465b255e6b181ede47a6cbf',
            'NETSUITE_CONSUMER_SECRET' => '9a5dc837fe95c594bb9d968a1857864507276c68c3e77670ee5f4344a14096ef',
            'NETSUITE_TOKEN' => '5268d3407303b01c71f8b8c2b3808cf6f488823f49f66ca21eb0ec2cdcdd9ca6',
            'NETSUITE_TOKEN_SECRET' => '61deb068cdd3aa0ce3bec024bd92aceb468964ab3dad484d9bfb6320bc9b2c3a',
            'NETSUITE_WEBSERVICES_HOST' => 'https://3929178.suitetalk.api.netsuite.com',
            'NETSUITE_APP_ID' => 'E9453AA0-8EFF-4943-A145-7F8664617E33',
            'NETSUITE_OBJECT_TYPE' => NetSuiteConstantHelper::NETSUITE_ITEM_FULFILLMENT,
        ];

        // Iterate default meta to save
        foreach ($demeta as $meta_key => $meta_value) {
            $data = [
                'integration_id' => $integration->id,
                'meta_key' => $meta_key,
                'meta_value' => $meta_value
            ];
            IntegrationMeta::create($data);
        }

        \Log::info('Done creating metas: ' . count($demeta));

        // Default filter
        // ==============================================================================

        $dfilters = [
            'filter_1' => [
                'field' => 'type',
                'operator' => 'anyOf',
                'search_value' => 'salesOrder'
            ],
            // 'filter_2' => [
            //     'field' => 'dateCreated',
            //     'operator' => 'after',
            //     'search_value' => '-12 hours'
            // ],
            'filter_4' => [
                'field' => 'customFieldList',
                'custom_fields' => json_encode([
                    [
                        'method' => 'searchMultiSelectCustomField',
                        'operator' => 'anyOf',
                        'internal_id' => '3589',
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

        // create the integration custom function
        Artisan::call('make:integration-custom-function', [ 'client' => $account->id ]);
    }
}
