<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Transformers\MachshipStatusMappingTransformer;
use App\Http\Controllers\ApiBaseController;
use App\Repositories\MachshipStatusMappingRepository;
use App\Repositories\Criterias\MachshipStatusMappingCriteria;
use App\Models\IntegrationRecords;

class MachshipStatusMappingController extends ApiBaseController
{
    protected $repository;

    public function __construct(
        MachshipStatusMappingTransformer $transformer,
        MachshipStatusMappingRepository $repository
    ) {
        $this->repository = $repository;
        $this->transformer = $transformer;
    }

    public function index(Request $request)
    {
        $this->repository->pushCriteria(new MachshipStatusMappingCriteria($request));
        return parent::index($request);
    }

    public function getOptions()
    {
        $record_status = [
            IntegrationRecords::RECORD_STATUS_PENDING,
            IntegrationRecords::RECORD_STATUS_PENDING_MACHSHIP,
            IntegrationRecords::RECORD_STATUS_ERROR,
            IntegrationRecords::RECORD_STATUS_PROCESSED,
            IntegrationRecords::RECORD_STATUS_RE_PROCESS,
            IntegrationRecords::RECORD_STATUS_PROCESSING,
            IntegrationRecords::RECORD_STATUS_MAPPING_ERROR,
            IntegrationRecords::RECORD_STATUS_SKIPPED,
            IntegrationRecords::RECORD_STATUS_MACHSHIP_ERROR,
            IntegrationRecords::RECORD_STATUS_PENDING_UPDATE,
            IntegrationRecords::RECORD_STATUS_COMPLETED,
        ];

        return response()->json([
            'record_status' => $record_status
        ]);
    }

    public function bulkStore(Request $request)
    {
        $all = $request->all();
        foreach ($all as $row) {
            $this->repository->update($row, $row['id']);
        }
        return response()->json(['status' => true]);
    }

}
