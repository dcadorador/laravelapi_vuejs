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

class IntegrationDELFSeeder extends Seeder
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
        User::where('name', DatabaseSeeder::DELF . ' Admin')->delete();
        Account::where('client_name', DatabaseSeeder::DELF)->delete();
        Integration::where('id', 2)->delete();
        IntegrationKey::where('integration_id', 2)->delete();
        IntegrationMeta::where('integration_id', 2)->delete();
        IntegrationSourceFilter::where('integration_id', 2)->delete();
        FieldMapper::where('integration_id', 2)->delete();
        // ValueLookups::where('integration_id', 2)->delete();
        MachshipStatusMapping::where('integration_id', 2)->delete();

        // create a user for DELF
        $user = new User();
        $user->addRole('Admin');

        $user->name = DatabaseSeeder::DELF . ' Admin';
        $user->email = "john+test2@fusedsoftware.com";
        $user->password = Hash::make('1234qwer');
        $user->save();

        // create local account for DELF
        $account_data = [
            'client_name' => DatabaseSeeder::DELF,
            'client_notes' => 'DB Seed Insertion',
            'user_id' => $user->id,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        $account = Account::create($account_data);

        $integration_type = IntegrationType::where('name', 'LIKE', 'Netsuite')->first();

        $integration_data = [
            'id' => 2,
            'label' => DatabaseSeeder::DELF,
            'account_id' => $account->id,
            'integration_type_id' => $integration_type->id,
            'integration_status' => DatabaseSeeder::ACTIVE,
            'frequency_mins' => 10,
            'last_run' => null,
            'master_consignment_type' => Integration::CONSIGNMENT_TYPE_PENDING,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $integration = Integration::create($integration_data);

        \Log::info('Done creating Integration: ' . json_encode($integration));

        // machship status insertion based on use case for DELF
        $statuses = [
            'Pending' => 27
        ];

        foreach ($statuses as $status => $id) {
            $machship_status_data = [
                'integration_id' => $integration->id,
                'machship_status_id' => $id,
                'machship_status' => $status,
                'record_status' => 'COMPLETED',
                'update_source' => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            MachshipStatusMapping::create($machship_status_data);
        }

        // Create default maps and configurations here
        // ==============================================================================
        $maps = [
            [
                'machship_field' => 'despatchDateTimeLocal',
                'direction' => 'to_machship',
                'map_type' => 'skip',
                'source_field' => '',
                'data_conversion_type' => 'function',
                'data_conversion_value' => 'getDateTimeByAus',
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
                'source_field' => 'shippingAddress.city'
            ],
            [
                'machship_field' => 'toLocation.postcode',
                'direction' => 'to_machship',
                'map_type' => 'direct',
                'source_field' => 'shippingAddress.zip'
            ],
            [
                'machship_field' => 'toName',
                'direction' => 'to_machship',
                'map_type' => 'direct',
                'source_field' => 'shippingAddress.addressee'
            ],
            [
                'machship_field' => 'fromName',
                'direction' => 'to_machship',
                'map_type' => 'skip',
                'data_conversion_type' => 'constant',
                'data_conversion_value' => 'Delf Architectural'
            ],
            [
                'machship_field' => 'fromContact',
                'direction' => 'to_machship',
                'map_type' => 'skip',
                'data_conversion_type' => 'constant',
                'data_conversion_value' => 'Delf Architectural'
            ],
            [
                'machship_field' => 'fromEmail',
                'direction' => 'to_machship',
                'map_type' => 'skip',
                'data_conversion_type' => 'constant',
                'data_conversion_value' => 'sales@delfarchitectural.com.au'
            ],
            [
                'machship_field' => 'fromPhone',
                'direction' => 'to_machship',
                'map_type' => 'skip',
                'source_field' => 'constant',
                'data_conversion_type' => '+611300362643',
            ],
            [
                'machship_field' => 'fromAddressLine1',
                'direction' => 'to_machship',
                'map_type' => 'skip',
                'data_conversion_type' => 'constant',
                'data_conversion_value' => '20-24 Bessemer Drive'
            ],
            [
                'machship_field' => 'fromLocation.suburb',
                'direction' => 'to_machship',
                'map_type' => 'skip',
                'data_conversion_type' => 'constant',
                'data_conversion_value' => 'Dandenong South'
            ],
            [
                'machship_field' => 'fromLocation.postcode',
                'direction' => 'to_machship',
                'map_type' => 'skip',
                'data_conversion_type' => 'constant',
                'data_conversion_value' => 3175
            ],
            [
                'machship_field' => 'customerReference2',
                'direction' => 'to_machship',
                'map_type' => 'direct',
                'source_field' => 'otherRefNum',
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
            'key_data' => 'KoEWxdCqEku-vyIzu6ih8A'
        ]);


        // Default meta
        // ==============================================================================

        $demeta = [
            'NETSUITE_ENDPOINT' => '2019_1',
            'NETSUITE_ACCOUNT' => '4291715',
            'NETSUITE_CONSUMER_KEY' => 'aba4372ff0332cacc2bda47fa0df612f84d25059a232fa5492b44520a3504068',
            'NETSUITE_CONSUMER_SECRET' => '271933f871e98995e07819131d3280e800313e827aea34b186cdaad73be63c1d',
            'NETSUITE_TOKEN' => '0cde6929870c5a3101389f6fa8f8de4843e46a55471bd5d41862b502908b1b50',
            'NETSUITE_TOKEN_SECRET' => '07d5381ea4ff9be5e3b53cbf801f3fc6bf4a1736b1d4ddb763403c38086a0447',
            'NETSUITE_WEBSERVICES_HOST' => 'https://4291715.suitetalk.api.netsuite.com',
            'NETSUITE_APP_ID' => '5F3D61CE-49C0-45E4-9970-AAACBB27AFCD',
            'NETSUITE_OBJECT_TYPE' => NetSuiteConstantHelper::NETSUITE_SALES_ORDER,
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
            'filter_3' => [
                'field' => 'location',
                'operator' => 'anyOf',
                'search_value' => '5',
            ],
            'filter_4' => [
                'field' => 'customFieldList',
                'custom_fields' => json_encode([
                    [
                        'method' => 'searchMultiSelectCustomField',
                        'operator' => 'anyOf',
                        'internal_id' => '881',
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
