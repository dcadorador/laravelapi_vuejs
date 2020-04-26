<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Transformers\IntegrationTransformer;
use App\Transformers\IntegrationDetailsTransformer;
use App\Repositories\IntegrationRepository;
use App\Repositories\FieldMapperRepository;
use App\Repositories\IntegrationMetaRepository;
use App\Repositories\Criterias\IntegrationCriteria;
use App\Http\Controllers\ApiBaseController;
use App\Services\ValueLookupService;

class IntegrationController extends ApiBaseController
{

    public function __construct(
        IntegrationTransformer $integrationTransformer,
        IntegrationRepository $integrationRepository
    ) {
        $this->repository = $integrationRepository;
        $this->transformer = $integrationTransformer;

        $this->validations = [
            'label' => 'required',
            'account_id' => 'required|exists:accounts,id',
            'integration_type_id' => 'required|exists:integration_types,id',
            'frequency_mins' => 'required|numeric'
        ];
    }

    public function index(Request $request)
    {
        $this->repository->pushCriteria(new IntegrationCriteria($request));
        return parent::index($request);
    }

    public function store(Request $request)
    {

        if (isset($this->validations)) {
            $request->validate($this->validations);
        }

        $result = $this->repository->create($request->all());


        // we need to create a default maps for the integration
        $type = $result->integrationType;
        $maps = $type->getDefaultMaps();
        $metas = $type->getDefaultMeta();

        $fmRepo = new FieldMapperRepository(app());

        // iterate default maps to save
        foreach ($maps as $map) {
            $data = [
                'integration_id' => $result->id,
                'data_direction' => $map['direction'],
                'machship_field' => $map['machship_field'],
                'map_type' => isset($map['map_type']) ? $map['map_type'] : null,
                'source_field' => isset($map['source_field']) ? $map['source_field'] : null,
                'data_conversion_type' => isset($map['data_conversion_type']) ? $map['data_conversion_type'] : null,
                'data_conversion_value' => isset($map['data_conversion_value']) ? $map['data_conversion_value'] : null,
            ];
            $fmRepo->create($data);
        }

        $imRepo = new IntegrationMetaRepository(app());
        // iterate default meta to save
        foreach ($metas as $meta) {
            $data = [
                'integration_id' => $result->id,
                'meta_key' => $meta['meta_key'],
                'meta_value' => $meta['meta_value']
            ];
            $imRepo->create($data);
        }

        return $this->success($result);
    }


    public function show($id)
    {
        // use transformer to view detail integration
        $this->transformer = new IntegrationDetailsTransformer;
        return parent::show($id);
    }

    public function activate(Request $request)
    {
        $data = $request->all();
        $integration = $this->repository->find($data['integration_id']);
        $integration->integration_status = $data['integration_status'];
        $integration->save();
        return ['true'];
    }


    /**
     * Gets the default field maps by integration id
     * 1. Determine which integration type (Netsuite, MYOB, Pronto)
     * 2. Get default maps
     * @param      integer  $id     The identifier
     */
    public function getDefaultMaps(Request $request, $id)
    {
        $integration = $this->repository->find($id);

        $type = $integration->integrationType;
        return $type->getDefaultMaps();
    }

    public function testValueLookup(Request $request, $id)
    {
        $integration = $this->repository->find($id);
        $valueLookup = $integration->valueLookup;
        $service = new ValueLookupService($valueLookup);
        return $service->search('carrierAccountId', 'test');
    }
}
