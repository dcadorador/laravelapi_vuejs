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

class IntegrationDearSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $integrationId = 4;

        // for testing purposes, we need to delete any existing data
        // user, accounts, integrations, integration_key, integration_meta, field_mapper, source filter, value_lookups
        User::where('name', DatabaseSeeder::DEAR . ' Admin')->delete();
        Account::where('client_name', 'Blue Mountain Co')->delete();
        IntegrationType::where('name', DatabaseSeeder::DEAR)->delete();
        Integration::where('id', $integrationId)->delete();
        IntegrationKey::where('integration_id', $integrationId)->delete();
        IntegrationMeta::where('integration_id', $integrationId)->delete();
        IntegrationSourceFilter::where('integration_id', $integrationId)->delete();
        FieldMapper::where('integration_id', $integrationId)->delete();
        MachshipStatusMapping::where('integration_id', $integrationId)->delete();

        $user = new User();
        $user->addRole('Admin');

        $user->name = DatabaseSeeder::DEAR . ' Admin';
        $user->email = "john+test4@fusedsoftware.com";
        $user->password = Hash::make('1234qwer');
        $user->save();

        $account_data = [
            'client_name' => 'Blue Mountain Co',
            'client_notes' => 'DB Seed Insertion',
            'user_id' => $user->id,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        $account = Account::create($account_data);

        $integration_type = IntegrationType::create([
            'id' => 5,
            'name' => DatabaseSeeder::DEAR,
            'directory' => '\\App\\Services\\Platforms\\Dear'
        ]);

        $integration_data = [
            'id' => 4,
            'label' => DatabaseSeeder::DEAR,
            'account_id' => $account->id,
            'integration_type_id' => $integration_type->id,
            'integration_status' => DatabaseSeeder::ACTIVE,
            'frequency_mins' => 5,
            'last_run' => null,
            'master_consignment_type' => Integration::CONSIGNMENT_TYPE_PENDING,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $integration = Integration::create($integration_data);

        \Log::info('Done creating Integration: ' . json_encode($integration));

        // machship status insertion based on use case for DEAR
        $statuses = [
            'Pending' => 27,
            'Quote' => 1,
            'Manifesting' => 25,
            'Deleted' => 21,
            'Unmanifested' => 2,
            'Manifested' => 3,
            'Booked' => 4,
            'In Transit' => 5,
            'Delayed' => 6,
            'Complete' => 7,
            'Lost' => 8,
            'Damaged' => 9,
            'Cancelled' => 10,
            'On For Delivery' => 13,
            'Added To Machship' => 14,
            'Picked Up' => 15,
            'Scanned into Depot' => 16,
            'Delivery Attempted' => 17,
            'Sorted for Delivery' => 18,
            'Partial Delivery' => 19,
            'Delivery Time Scheduled' => 20,
            'At Delivery' => 22,
            'Partial On For Delivery' => 23,
            'At Pickup' => 24,
            'Tracking Expires' => 26,
        ];

        foreach ($statuses as $status => $id) {
            $machship_status_data = [
                'integration_id' => $integration->id,
                'machship_status_id' => $id,
                'machship_status' => $status,
                'record_status' => $status == 'Pending' || $status == 'Quote' || $status == 'Manifesting' || $status == 'Unmanifested' ? 'PENDING_UPDATE' : 'COMPLETED',
                'update_source' => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            MachshipStatusMapping::create($machship_status_data);
        }

        // create default maps and configurations here
        // ==============================================================================
        $maps = [
            [
                'machship_field' => 'toAddressLine1',
                'direction' => 'to_machship',
                'map_type' => 'direct',
                'source_field' => 'Fulfilments.Ship.ShippingAddress.Line1',
                'data_conversion_type' => '',
                'data_conversion_value' => '',
            ],
            [
                'machship_field' => 'toAddressLine2',
                'direction' => 'to_machship',
                'map_type' => 'direct',
                'source_field' => 'Fulfilments.Ship.ShippingAddress.Line2',
                'data_conversion_type' => '',
                'data_conversion_value' => '',
            ],
            [
                'machship_field' => 'toLocation.suburb',
                'direction' => 'to_machship',
                'map_type' => 'direct',
                'source_field' => 'Fulfilments.Ship.ShippingAddress.City',
                'data_conversion_type' => '',
                'data_conversion_value' => '',
            ],
            [
                'machship_field' => 'toLocation.postcode',
                'direction' => 'to_machship',
                'map_type' => 'direct',
                'source_field' => 'Fulfilments.Ship.ShippingAddress.Postcode',
                'data_conversion_type' => '',
                'data_conversion_value' => '',
            ],
            [
                'machship_field' => 'toName',
                'direction' => 'to_machship',
                'map_type' => 'direct',
                'source_field' => 'SaleList.Customer',
                'data_conversion_type' => '',
                'data_conversion_value' => '',
            ],
            [
                'machship_field' => 'toContact',
                'direction' => 'to_machship',
                'map_type' => 'direct',
                'source_field' => 'SaleList.Customer',
                'data_conversion_type' => '',
                'data_conversion_value' => '',
            ],
            [
                'machship_field' => 'customerReference',
                'direction' => 'to_machship',
                'map_type' => 'skip',
                'source_field' => 'SaleID',
                'data_conversion_type' => 'custom_function',
                // getCustomerRef will response SalesId - TaskId
                'data_conversion_value' => 'getCustomerRef',
            ],
            [
                'machship_field' => 'companyId',
                'direction' => 'to_machship',
                'map_type' => 'skip',
                'data_conversion_type' => 'constant',
                'data_conversion_value' => '4801'
            ],
            [
                'machship_field' => 'fromCompanyLocationId',
                'direction' => 'to_machship',
                'map_type' => 'skip',
                'data_conversion_type' => 'constant',
                'data_conversion_value' => '1023796'
            ],
            [
                'machship_field' => 'specialInstructions',
                'direction' => 'to_machship',
                'map_type' => 'direct',
                'source_field' => 'Fulfilments.Ship.ShippingNotes'
            ]
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

        // default keys token
        // ==============================================================================

        IntegrationKey::create([
            'integration_id' => $integration->id,
            'key_type' => 'machship_token',
            'key_data' => \App::environment('production') ? 'WzgG-uMppkOcmpHuvwZGUA' : 'dTSt1xpIukOPqR_WlMKPPw'
        ]);

        // default meta
        // ==============================================================================
        $demeta = (\App::environment('production')) ?
            [ 'DEAR_ACCOUNT_ID' => '87e53963-d313-4f4a-8fba-a84aec123ed1', 'DEAR_APPLICATION_KEY' => 'f9c5f4f8-8bf7-add4-3523-d08ee6121eb1' ] :
            [ 'DEAR_ACCOUNT_ID' => '0f33e4fb-5ba9-4041-b60c-4d9bbdcc7bd1', 'DEAR_APPLICATION_KEY' => '5843512c-086e-fd81-42dd-00f9016c2271' ];

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

        $filters = [
            'Status' => 'PICKED',
            'UpdatedSince' => '-120 minutes'
        ];

        foreach ($filters as $key => $value) {
            IntegrationSourceFilter::create([
                'integration_id' => $integration->id,
                'filter_key' => $key,
                'filter_value' => $value
            ]);
        }

        \Log::info('Done creating filters: ' . count($filters));

        // ==============================================================================
    }
}
