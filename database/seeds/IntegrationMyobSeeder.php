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
use App\Models\ValueLookups;
use App\Repositories\IntegrationRepository;
use App\Libraries\Netsuite\NetsuiteHelpers\NetSuiteConstantHelper;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class IntegrationMyobSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $integrationId = 5;
        // for testing purposes, we need to delete any existing data
        // user, accounts, integrations, integration_key, integration_meta, field_mapper, source filter, value_lookups
        User::where('name', DatabaseSeeder::DOMAIN_APPLIANCES . ' Admin')->delete();
        Account::where('client_name', DatabaseSeeder::DOMAIN_APPLIANCES)->delete();
        Integration::where('id', $integrationId)->delete();
        IntegrationKey::where('integration_id', $integrationId)->delete();
        IntegrationMeta::where('integration_id', $integrationId)->delete();
        IntegrationSourceFilter::where('integration_id', $integrationId)->delete();
        FieldMapper::where('integration_id', $integrationId)->delete();
        MachshipStatusMapping::where('integration_id', $integrationId)->delete();
        ValueLookups::where('integration_id', $integrationId)->delete();

        $user = new User();
        $user->addRole('Admin');

        $user->name = DatabaseSeeder::DOMAIN_APPLIANCES . ' Admin';
        $user->email = "john+test5@fusedsoftware.com";
        $user->password = Hash::make('1234qwer');
        $user->save();

        // create local account for DOMAIN
        $existing_account = Account::where('client_name', DatabaseSeeder::DOMAIN_APPLIANCES)->first();

        if ($existing_account) {
            $existing_account->delete();
        }

        $account_data = [
            'client_name' => DatabaseSeeder::DOMAIN_APPLIANCES,
            'client_notes' => 'DB Seed Insertion',
            'user_id' => $user->id,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        $account = Account::create($account_data);

        $integration_type = IntegrationType::where('name', 'LIKE', 'MYOB')->first();

        $integration_data = [
            'id' => $integrationId,
            'label' => DatabaseSeeder::DOMAIN_APPLIANCES,
            'account_id' => $account->id,
            'integration_type_id' => $integration_type->id,
            'integration_status' => DatabaseSeeder::ACTIVE,
            'frequency_mins' => 15,
            'last_run' => null,
            'master_consignment_type' => Integration::CONSIGNMENT_TYPE_PENDING,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $integration = Integration::create($integration_data);

        \Log::info('Done creating Integration: ' . json_encode($integration));

        // machship status insertion based on use case for MYOB
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
                'update_source' => 1, // todo: verify if the we have to update source data
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            MachshipStatusMapping::create($machship_status_data);
        }

        // create default maps and configurations here
        // ==============================================================================
        $maps = [
            [
                'machship_field' => 'companyId',
                'direction' => 'to_machship',
                'map_type' => 'skip',
                'data_conversion_type' => 'constant',
                'data_conversion_value' => 4451
            ],
            [
                'machship_field' => 'fromCompanyLocationId',
                'direction' => 'to_machship',
                'map_type' => 'direct',
                'source_field' => 'WarehouseID.value',
                'data_conversion_type' => 'lookup'
            ],
            [
                'machship_field' => 'toContact',
                'direction' => 'to_machship',
                'map_type' => 'skip',
                // 'source_field' => 'ShippingSettings.ShipToContact.Attention.value',
                'data_conversion_type' => 'custom_function',
                // getCustomerRef will response SalesId - TaskId
                'data_conversion_value' => 'getContactRef',
            ],
            [
                'machship_field' => 'toName',
                'direction' => 'to_machship',
                'map_type' => 'skip',
                // 'source_field' => 'ShippingSettings.ShipToContact.BusinessName.value',
                'data_conversion_type' => 'custom_function',
                'data_conversion_value' => 'getContactRef',
            ],
            [
                'machship_field' => 'toAddressLine1',
                'direction' => 'to_machship',
                'map_type' => 'direct',
                'source_field' => 'ShippingSettings.ShipToAddress.AddressLine1.value',
            ],
            [
                'machship_field' => 'toAddressLine2',
                'direction' => 'to_machship',
                'map_type' => 'direct',
                'source_field' => 'ShippingSettings.ShipToAddress.AddressLine2.value',
            ],
            [
                'machship_field' => 'toLocation.suburb',
                'direction' => 'to_machship',
                'map_type' => 'direct',
                'source_field' => 'ShippingSettings.ShipToAddress.City.value',
            ],
            [
                'machship_field' => 'toLocation.postcode',
                'direction' => 'to_machship',
                'map_type' => 'direct',
                'source_field' => 'ShippingSettings.ShipToAddress.PostalCode.value',
            ],
            [
                'machship_field' => 'customerReference2',
                'direction' => 'to_machship',
                'map_type' => 'direct',
                'source_field' => 'ShipmentNbr.value',
            ],
            [
                'machship_field' => 'customerReference',
                'direction' => 'to_machship',
                'map_type' => 'direct',
                'source_field' => 'Details.0.OrderNbr.value'
            ],
            [
                'machship_field' => 'items.name',
                'direction' => 'to_machship',
                'map_type' => 'direct_simple',
                'source_field' => 'Details',
                'data_conversion_type' => 'function',
                'data_conversion_value' => 'concat|InventoryID.value,Description.value|:'
            ],
            [
                'machship_field' => 'items.sku',
                'direction' => 'to_machship',
                'map_type' => 'direct',
                'source_field' => 'Details.InventoryID.value'
            ],
            [
                'machship_field' => 'items.quantity',
                'direction' => 'to_machship',
                'map_type' => 'direct',
                'source_field' => 'Details.ShippedQty.value'
            ],
            [
                'machship_field' => 'toPhone',
                'direction' => 'to_machship',
                'map_type' => 'direct',
                'source_field' => 'ShippingSettings.ShipToContact.Phone1.value',
            ],
            [
                'machship_field' => 'toEmail',
                'direction' => 'to_machship',
                'map_type' => 'direct',
                'source_field' => 'ShippingSettings.ShipToContact.Email.value',
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


        // default keys token
        // ==============================================================================

        $token = \App::environment('production') ? '9tmUK3tlvkigXWWMypZ-Aw' : 'dTSt1xpIukOPqR_WlMKPPw' ; // todo: no machship token yet
        IntegrationKey::create([
            'integration_id' => $integration->id,
            'key_type' => 'machship_token',
            'key_data' => $token
        ]);


        // default meta
        // ==============================================================================

        $demeta = \App::environment('production') ?
            [
                'MYOB_URL' => 'https://domainappliances.myobadvanced.com/entity/Default/',
                'MYOB_VERSION' => '18.200.001',
                'MYOB_USER' => 'fusedsoftware',
                'MYOB_PASSWORD' => 'D0m@in2020',
            ] :
            [
                'MYOB_URL' => 'https://fusedsoftware-demo.myobadvanced.com/entity/Default/',
                'MYOB_VERSION' => '18.200.001',
                'MYOB_USER' => 'apiuser',
                'MYOB_PASSWORD' => 'p@sswd123',
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

        \Log::info('Done creating metas: ' . count($demeta));
        $filters = [
            [
                'integration_id' => $integration->id,
                'filter_key' => '$filter',
                'filter_value' => "WarehouseID eq 'MELBOURNE' and ShipVia eq 'MACHSHIP'",
            ],
            [
                'integration_id' => $integration->id,
                'filter_key' => '$expand',
                'filter_value' => "ShippingSettings,ShippingSettings/ShipToAddress,ShippingSettings/ShipToContact,Details",
            ],
            [
                'integration_id' => $integration->id,
                'filter_key' => '$select',
                'filter_value' => "Status,ShipmentNbr,CustomerID,ShipVia,LocationID,WarehouseID,ShippingSettings,Details",
            ]
        ];

        IntegrationSourceFilter::insert($filters);
        \Log::info('Done creating filters: ' . count($filters));

        // iterate default lookups to save
        // ==============================================================================
        $lookups = [
            /***********FROM COMPANY LOCATION ID*************/
            [
                'integration_id' => $integration->id,
                'machship_field' => 'fromCompanyLocationId',
                'from_value' => 'MELBOURNE',
                'to_value' => 988122
            ],
        ];

        ValueLookups::insert($lookups);
        \Log::info('Done creating lookups: ' . count($lookups));
    }
}
