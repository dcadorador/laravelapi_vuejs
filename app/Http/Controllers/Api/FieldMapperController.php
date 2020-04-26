<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Transformers\FieldMapperTransformer;
use App\Transformers\IntegrationDetailsTransformer;
use App\Repositories\FieldMapperRepository;
use App\Repositories\IntegrationRepository;
use App\Repositories\ValueLookupRepository;
use App\Http\Controllers\ApiBaseController;
use App\Models\FieldMapper;
use App\Helpers\Helper;
use App\Libraries\Machship\Machship;
use Illuminate\Support\Facades\Cache;
use App\Services\MachshipCacheService;

class FieldMapperController extends ApiBaseController
{

    private $integrationRepository;

    public function __construct(
        FieldMapperTransformer $fieldMapperTransformer,
        FieldMapperRepository $fieldMapperRepository,
        IntegrationRepository $integrationRepository
    ) {
        $this->repository = $fieldMapperRepository;
        $this->transformer = $fieldMapperTransformer;
        $this->integrationRepository = $integrationRepository;

        $this->validations = [
            'integration_id' => 'required|exists:integrations,id',
            'data_direction' => 'required',
            'machship_field' => 'required',
            'map_type' => 'required',
            'source_field' => 'required'
        ];
    }


    public function bulkStore(Request $request)
    {
        $all = $request->all();
        $integration_id = 0;
        $direction = "";
        foreach ($all as $row) {
            $checker = [];
            
            if (isset($row['id'])) {
                $this->repository->update($row, $row['id']);
            } else {
                $this->repository->create($row);
            }
            $integration_id = $row['integration_id'];
            $direction = $row['data_direction'];
        }

        if ($direction === 'to_machship') {
            return $this->repository->findyByIntegIdToMachship($integration_id);
        } else {
            return $this->repository->findyByIntegIdFromMachship($integration_id);
        }
    }

    public function bulkRemove(Request $request)
    {
        $ids = $request->all();
        $this->repository->removeByIds($ids);
        return response()->json(['status' => true, 'ids' => $ids]);
    }

    public function getOptions(Request $request, $id)
    {
        $integration = $this->integrationRepository->find($id);
        if (!$integration) {
            return $this->error(['message' => 'Cannot find integration']);
        } else {
            $data = [];
            $data['machship_fields'] = Helper::MACSHIP_FIELDS;
            $data['source_fields'] = $integration->integrationType->getDefaultSourceField();
            $data['options'] = $this->repository->getOptions();
            return $data;
        }
    }

    public function saveCarrierService(Request $request)
    {
        $request->validate([
            'integration_id' => 'required|exists:integrations,id',
            'carrier_id' => 'required|numeric',
            'carrier_name' => 'required|string',
            'carrier_service_name' => 'required|string',
            'carrier_service_id' => 'required|numeric'
        ]);

        $input = $request->all();

        $value_lookups_repo = new ValueLookupRepository(app());

        // insert value lookup for carrierId machship field
        $value_lookups_repo->insert([
            'integration_id' => $input['integration_id'],
            'machship_field' => 'carrierId',
            'from_value' => $input['carrier_name'],
            'to_value' => $input['carrier_id'],
            'to_label' => $input['carrier_name']
        ]);

        // insert value carrier service machship
        $value_lookups_repo->insert([
            'integration_id' => $input['integration_id'],
            'machship_field' => 'carrierServiceId',
            'from_value' => $input['carrier_name'],
            'to_value' => $input['carrier_service_id'],
            'to_label' => $input['carrier_service_name']
        ]);

        // insert value carrier Account lookup
        $integration = $this->integrationRepository->find($input['integration_id']);
        if (!empty($integration)) {
            $token = $integration->getMachshipTokenKey();
            $machship = new Machship($token);
            $accounts = $machship->getCarrierAccounts($input['carrier_id']);
            \Log::info('accounts : ' . json_encode($accounts));
            if ($accounts && !is_null($accounts->object) && count($accounts->object)) {
                $value_lookups_repo->insert([
                    'integration_id' => $input['integration_id'],
                    'machship_field' => 'carrierAccountId',
                    'from_value' => $input['carrier_name'],
                    'to_value' => $accounts->object[0]->id,
                ]);
            }
        }


        return ['status' => true];
    }

    public function deleteCarrierService(Request $request, $id)
    {
        // delete carrier services on the value lookup
        $delete = ['carrierId', 'carrierServiceId', 'carrierAccountId'];

        $integration = $this->integrationRepository->find($id);
        if (!empty($integration)) {
            $lookups = $integration->valueLookup;
            foreach ($lookups as $lookup) {
                if (in_array($lookup->machship_field, $delete)) {
                    $lookup->delete();
                }
            }
            return ['status' => true];
        }

        return ['status' => false];
    }

    public function getCarrierMapServices(Request $request, $id)
    {
        $integrations = $this->integrationRepository->find($id);
        if (empty($integrations)) {
            return ['status' => false];
        }

        $value_lookups = $integrations->valueLookup;

        if (empty($value_lookups)) {
            return ['status' => false];
        }

        $result = [];
        foreach ($value_lookups as $value) {
            if ($value['machship_field'] === 'carrierId') {
                $result[$value['from_value']]['carrier'] = $value['from_value'];
            } elseif ($value['machship_field'] === 'carrierServiceId') {
                $result[$value['from_value']]['service'] = $value['to_label'];
            }
        }

        return ['status' => true, 'result' => $result];
    }

    public function getCarrierOptions(Request $request, $id)
    {
        $integration = $this->integrationRepository->find($id);
        if (!empty($integration)) {
            $token = $integration->getMachshipTokenKey();
            $machship = new Machship($token);
            $ms_cache_service = new MachshipCacheService($machship, $integration);
            $carriers = $ms_cache_service->getCarriers();

            return isset($carriers->object) ? $carriers->object : ['status' => false] ;
        }

        return ['status' => false];
    }

    public function getCarrierServicesOptions(Request $request)
    {

        $request->validate([
            'integration_id' => 'required|exists:integrations,id',
            'carrier_id' => 'required|numeric',
        ]);

        $integration_id = $request->input('integration_id');
        $carrier_id = $request->input('carrier_id');
        $integration = $this->integrationRepository->find($integration_id);

        if (!empty($integration)) {
            $token = $integration->getMachshipTokenKey();
            $machship = new Machship($token);
            $carrier_services = $machship->getCarrierServices($carrier_id);

            return $carrier_services->object;
        }

        return ['status' => false];
    }

    public function reset(Request $request, $id)
    {
        $integration = $this->integrationRepository->find($id);

        if (!$integration) {
            return $this->error(['message' => 'Cannot find integration']);
        } else {
            $type = $integration->integrationType;
            $maps = $type->getDefaultMaps();

            // needs to delete existing
            $this->repository->deleteWhere([
                'integration_id' => $integration->id
            ]);

            // iterate default maps to save
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

                $this->repository->create($data);
            }

            $this->transformer = new IntegrationDetailsTransformer();
            $result = $this->integrationRepository->find($integration->id);
            return $this->success($result);
        }
    }

    public function getToMachships(Request $request, $id)
    {
        $results = $this->repository->findyByIntegIdToMachship($id);
        return $results;
    }

    public function getFromMachships(Request $request, $id)
    {
        $results = $this->repository->findyByIntegIdFromMachship($id);
        return $results;
    }
}
