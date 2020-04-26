<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Transformers\SyncLogsTransformer;
use App\Http\Controllers\ApiBaseController;
use App\Repositories\SyncLogsRepository;

class SyncLogsController extends ApiBaseController
{
    private $repository;

    public function __construct(
        SyncLogsTransformer $transformer,
        SyncLogsRepository $repository
    ) {
        $this->repository = $repository;
        $this->transformer = $transformer;
    }
}
