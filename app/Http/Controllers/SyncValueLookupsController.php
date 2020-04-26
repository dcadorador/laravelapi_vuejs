<?php

namespace App\Http\Controllers;

use App\Models\ValueLookups;
use App\Libraries\Machship\Machship;
use App\Services\MachshipCacheService;
use App\Repositories\IntegrationRepository;
use App\Repositories\ValuelookupRepository;
use Illuminate\Http\Request;

class SyncValueLookupsController extends Controller
{

    protected $integrationRepository;
    protected $valueLookupRepository;

    public function __construct(IntegrationRepository $integrationRepository, ValueLookupRepository $valueLookupRepository)
    {
        $this->integrationRepository = $integrationRepository;
        $this->valueLookupRepository = $valueLookupRepository;
    }

    public function syncCompanyLocation(Request $request, $id)
    {

        $integration = $this->integrationRepository->find($id);
        $token = $integration->getMachshipTokenKey();
        $machship = new Machship($token);
        $ms_cache_service = new MachshipCacheService($machship, $integration);
        $result = $ms_cache_service->getCompanyLocations();
        $ms_locations = empty($result->object) ? [] : $result->object;


        $data = [];
        $this->valueLookupRepository->where([
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
            $this->valueLookupRepository->insert($data);
        }

        dd($ms_locations);
    }
}
