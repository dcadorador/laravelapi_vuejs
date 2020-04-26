<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Transformers\ValueLookupTransformer;
use App\Transformers\IntegrationDetailsTransformer;
use App\Repositories\ValueLookupRepository;
use App\Repositories\IntegrationRepository;
use App\Http\Controllers\ApiBaseController;
use Illuminate\Support\Arr;
use App\Helpers\Helper;

class ValueLookupController extends ApiBaseController
{

    private $integrationRepository;

    public function __construct(
        ValueLookupTransformer $valueLookupTransformer,
        ValueLookupRepository $valueLookupRepository,
        IntegrationRepository $integrationRepository
    ) {
        $this->repository = $valueLookupRepository;
        $this->transformer = $valueLookupTransformer;
        $this->integrationRepository = $integrationRepository;

        $this->validations = [
            'integration_id' => 'required|exists:integrations,id',
            'machship_field' => 'required',
            'from_value' => 'required',
            'to_value' => 'required'
        ];
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

    public function getOptions(Request $request, $id)
    {
        return [
            'machship_fields' => Helper::MACSHIP_FIELDS
        ];
    }

    public function bulkStore(Request $request)
    {
        $items = $request->input('items');
        $direction = $request->input('direction');
        $integration_id = 0;
        $valuelookups = Arr::collapse($items);
        foreach ($valuelookups as $row) {
            // update row if exist
            if (isset($row['id'])) {
                // delete this row when all values are empty
                if (empty($row['to_value']) &&
                    empty($row['to_label']) &&
                    empty($row['from_value']) &&
                    empty($row['from_label'])
                ) {
                    $this->repository->find($row['id'])->delete();
                    continue;
                }

                $this->repository->update($row, $row['id']);

            // otherwise lets create it!
            } else {
                // validate if these values are not empty
                if (empty($row['to_value']) &&
                    empty($row['to_label']) &&
                    empty($row['from_value']) &&
                    empty($row['from_label'])
                ) {
                    continue;
                }
                $row['data_direction'] = $direction;
                $this->repository->create($row);
            }
            $integration_id = $row['integration_id'];
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
}
