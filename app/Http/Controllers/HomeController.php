<?php

namespace App\Http\Controllers;

use App\Libraries\Dear\Dear;
use App\Libraries\Machship\Machship;
use App\Libraries\Netsuite\Netsuite;
use App\Libraries\Netsuite\NetSuiteObjects\ItemFulfillmentObject;
use App\Models\Account;
use App\Models\Integration;
use App\Models\IntegrationMeta;
use App\Models\IntegrationSyncs;
use App\Models\ValueLookups;
use App\Process\CheckDueSync;
use App\Process\UpdateSourceIntegration;
use App\Services\Platforms\Myob\MyobService;
use Illuminate\Support\Facades\Artisan;
use Netsuite\Classes\Customer;
use App\Repositories\IntegrationRepository;
use App\Services\FieldMapService;
use App\Services\DataConversionService;
use App\Services\MachshipCacheService;
use App\Services\Platforms\Netsuite\NetsuiteService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Arr;
use App\Process\Process;
use App\Libraries\Myob\Myob;

class HomeController extends Controller
{

    protected $integrationRepository;
    /**
     * @var Myob
     */
    private $myob;

    public function __construct(IntegrationRepository $integrationRepository)
    {
        //$this->myob = new Myob('https://domainappliances.myobadvanced.com/entity/Default/', '18.200.001');
        //$this->myob = new Myob('https://fusedsoftware-demo.myobadvanced.com/entity/Default/', '17.200.001');
        $this->integrationRepository = $integrationRepository;
    }

    /**
     * Where user is redirected when visiting homepage
     *
     * @return view app
     */
    public function index()
    {
        return view('app');
    }

    public function test()
    {
        $q = new ItemFulfillmentObject();
        $integration_meta = IntegrationMeta::get()->toArray();

        $config = [
            'endpoint' => '2019_1',
            'host' => 'https://3929178.suitetalk.api.netsuite.com',
            'account' => '3929178',
            'consumerKey' => '593f6595885eaccd23cdc6022b3a601d715fa39313b9680f243b526f1d1e0685',
            'consumerSecret' => 'a2fb03d9b9071ecda4c2609c28f6e15fe5b58b2029d315a0943eefe612a355ca',
            'token' => '5ee5b18e00b2a6d772c4b255987d6d8ac1bfa78f4a5be978b426c1213786cfc1',
            'tokenSecret' => 'fdda4bb07d5ba58c99c796b4d894077570353e6f16e9e2ff6f3f0a80aa4109f1',
            'app_id' => '6617FEF0-1428-424A-BEB4-4B82EE1736A0',
        ];
        $nesuite = new Netsuite($config);
        dd($nesuite->setObject(new ItemFulfillmentObject())->get($integration_meta));
        return response()->json($nesuite->setObject(new ItemFulfillmentObject())->get($integration_meta));
    }


    public function test2()
    {
        $integration = $this->integrationRepository->find(5);
        $token = $integration->getMachshipTokenKey();
        $machship = new Machship($token);
        $ms_cache_service = new MachshipCacheService($machship, $integration);
        $result = $ms_cache_service->getCompanyLocations();
        $ms_locations = empty($result->object) ? [] : $result->object;


        $data = [];
        ValueLookups::where([
            'integration_id' => $integration->id,
            'machship_field' => 'fromCompanyLocationId'
        ])->delete();

        foreach ($ms_locations as $location) {
            $data = [
                'integration_id' => $integration->id,
                'machship_field' => 'fromCompanyLocationId',
                'from_value' => $location->location->suburb,
                'from_label' => $location->location->description,
                'to_value' => $location->location->id
            ];
            ValueLookups::create($data);
        }

        dd($ms_locations);
    }

    public function test4()
    {
        $integration = $this->integrationRepository->find(5);
        $config = $integration->getIntegrationMeta();
        $filters = $integration->integrationSourceFilters;

        $this->myob = new Myob($config['MYOB_URL'], $config['MYOB_VERSION']);
        // dd($this->myob->auth()->login($config['MYOB_USER'], $config['MYOB_PASSWORD']));

        $filter = urlencode('ShipmentNbr eq \'0203161\'');
        $params = '$filter=' . $filter . '&$expand=Packages';

        $data = [
            'CustomerID' => ['value' => '014584'],
            'WarehouseID' => ['value' => 'MELBOURNE'],
            'Packages' => [
                ['rowNumber' => ['value' => 1]], // fixed
                ['BoxID' => ['value' => 'TRACKING']], // fixed
                ['Confirmed' => ['value' => true]], // fixed
                ['CustomRefNbr1' => ['value' => 'test-1']], // cannote/carrier ID
                ['Description' => ['value' => 'testing carrier and testing service']], // carrier + service
                ['TrackingNbr' => ['value' => 'MS numberr TEST']], // MS Number
            ]
        ];

        dd($this->myob->shipment()->updateShipment($data, $params));
    }

    public function test5()
    {
        $integration = $this->integrationRepository->find(1);
        $key = $integration->getMSCarriersCachedID();
        $carriers = Cache::get($key);
        // dd($carriers);

        if (!empty($carriers)) {
            $carrierId = $carriers->object[0]->id;
            $token = $integration->getMachshipTokenKey();
            $machship = new Machship($token);
            $carrierServices = $machship->getCarrierServices($carrierId);
            dd($carrierServices);
        }
    }

    public function test6()
    {
        dd(new UpdateSourceIntegration());
        //$integration = $this->integrationRepository->find(1);
        //$process = new Process($integration);
        //if ( !empty($integration->integrationSyncs[0]) ) {
        //    $process->mainSyncProcess($integration->integrationSyncs[0]);
        //} else {
        //  return 'no integration!';
        //}
    }

    public function test10()
    {
        //dd('drew');
        $array = [[
                    "value" => false,
                    "internalId" => "3237",
                    "scriptId" => "custbody_inv_wf_has_run"
                  ],
                  [
                      "value" => false,
                      "internalId" => "2462",
                      "scriptId" => "custbody_contains_dgs"
                  ],
              [
                  "value"=> [
                    "name"=> "Carton",
                      "internalId"=> "2",
                      "externalId"=> null,
                      "typeId"=> "359"
                  ],
                  "internalId"=> "2479",
                  "scriptId"=> "custbody_box_2_type"
              ],
            ];

        $result = Arr::where($array, function ($key, $value) {
            //return $key['scriptId'] == 'custbody_contains_dgs';
            return stripos($key['scriptId'], '_box_') !== false;
        });

        dd(Arr::first($result));
        ///$accounts = Account::get();
        //dd($accounts);
    }

    public function test11()
    {
        $service  = new MyobService();
        $i = Integration::find(5);
        $service->setIntegration($i);
        $service->init([
            'MYOB_URL' => 'https://domainappliances.myobadvanced.com/entity/Default/',
            'MYOB_VERSION' => '18.200.001',
            'MYOB_USER' => 'fusedsoftware',
            'MYOB_PASSWORD' => 'D0m@in2020',

        ]);
        $r = $service->findBySourceId('020579');//$service->getByDateRange('2020-04-12', '2020-04-14');
        dd($r);
        ///$u = new UpdateSourceIntegration();
        //$u->getPendingUpdateAndProcess(IntegrationSyncs::where('id', 1197)->get());
        //dd(\Storage::disk('local')->get());
        /*$i = Integration::find(5);
        $filters = $i->integrationSourceFilters;
        foreach ($filters as $filter) {
            if (preg_match('/Date/', $filter->filter_key)) {

            }
        }*/
        /*$d = [
            'Content-Type' => 'Content-Type: application/json',
            'Content-length' => 'Content-Length: 11'
        ];*/
        //$d = new CheckDueSync();
        /*$config = [
            'uri' => 'https://fusedsoftware-demo.myobadvanced.com/entity/Default/',
            'version' => '17.200.001',
            'name' => 'apiuser',
            'password' => 'p@sswd123'
        ];*/
        /*$config = [
            'uri' => 'https://domainappliances.myobadvanced.com/entity/Default/',
            'version' => '18.200.001',
            'name' => 'fusedsoftware',
            'password' => 'D0m@in2020',
        ];*/
        //$this->myob = new Myob($config['uri'], $config['version']);
        //dd($this->myob->auth()->login($config['name'], $config['password']));
        //dd($myob->auth()->logout($config['name'], $config['password']));
        //$this->myob->setUri($config['uri']);
        //$this->myob->setVersion($config['version']);
        //$credentials = array('name' => $config['name'], 'password' => $config['password']);
        // $filter = '$filter=CreatedDateTime gt datetimeoffset\'2020-04-05T00:00:00.000\' and WarehouseID eq \'MELBOURNE\'&$expand=ShippingSettings,ShippingSettings/ShipToAddress,ShippingSettings/ShipToContact,Details&$select=Status,ShipmentNbr,CustomerID,ShipVia,LocationID,WarehouseID,ShippingSettings,Details';
        /*$filter = urlencode('CreatedDateTime gt datetimeoffset\'2020-04-07T00:00:00.000\' and WarehouseID eq \'MELBOURNE\'');
        $expand = urlencode('ShippingSettings,ShippingSettings/ShipToAddress,ShippingSettings/ShipToContact,Details');
        $select = urlencode('Status,ShipmentNbr,CustomerID,ShipVia,LocationID,WarehouseID,ShippingSettings,Details');
        $query = '$filter='.$filter.'&$expand=' . $expand . '&$select='.$select;
        $r = $this->myob->shipment()->shipmentDetails($query);
        dd($r);*
        //$this->myob->login($credentials);
        //dd($this->myob->shipments());
        // Artisan::call('make:seeder', ['name' => 'sample']);
        // $dear = new Dear('0f33e4fb-5ba9-4041-b60c-4d9bbdcc7bd1', '5843512c-086e-fd81-42dd-00f9016c2271');
        //dd($dear->saleList()->all(['Status' => 'ORDERED']));
        //dd($dear->saleFulfilment()->find('ff7d1026-fceb-49f2-9d4f-4ee356568157'));
        //dd(array_filter(array_values($d)));
        //$url = 'https://domainappliances.myobadvanced.com/entity/Default/18.200.001/';
        //$variable = str_replace('/entity/', '', str_replace('https://', '', substr($url, 0, strpos($url, "Default")))). '.txt';
        //dd(storage_path() . '/' . $variable);*/
    }
}
