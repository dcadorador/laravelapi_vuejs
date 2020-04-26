<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;

use App\Models\Integration;
use App\Models\IntegrationSyncs;
use App\Models\IntegrationSourceFilter;
use Illuminate\Support\Facades\DB;

class IntegrationRepository extends BaseRepository
{
    public function model()
    {
        return Integration::class;
    }

    protected $fieldSearchable = [
        'label' => 'like',
        'integrationType.name' => 'like',
        'integration_status',
        'account.client_name' => 'like'
    ];

    public function boot()
    {
        $this->pushCriteria(app('Prettus\Repository\Criteria\RequestCriteria'));
    }

    public function latest($limit)
    {
        return $this->model->orderBy('id', 'desc')->take($limit)->get();
    }

    public function resetFilters($id)
    {
        return true;
        // $integration = $this->find($id);
        // $filters = $integration->integrationSourceFilters;
        // if (!empty($filters)) {
        //     foreach ($filters as $filter) {
        //         $filter->delete();
        //     }
        // }

        // $default_filters = $integration->integrationType->getDefaultFilter();
        // foreach ($default_filters as $key => $filter) {
        //     if (is_array($filter)) {
        //         $parentId = null;
        //         foreach ($filter as $optionKey => $optionValue) {
        //             $data = [
        //                 'integration_id' => $id,
        //                 'filter_key' => $optionKey,
        //                 'filter_value' => $optionValue,
        //                 'integration_source_filter_id' => $parentId
        //             ];

        //             $sourceFilter = IntegrationSourceFilter::create($data);
        //             if (empty($parentId)) {
        //                 $parentId = $sourceFilter->id;
        //             }
        //         }
        //     } else {
        //         $data = [
        //             'integration_id' => $id,
        //             'filter_key' => $key,
        //             'filter_value' => $filter
        //         ];
        //         IntegrationSourceFilter::create($data);
        //     }
        // }
    }

    public function findActives()
    {
        return $this->model->where('integration_status', Integration::STATUS_ACTIVE)->get();
    }

    public function findWithSyncLastActive()
    {
        $dues = [];
        $integrations = $this->model->where('integration_status', 'active')->get();
        foreach ($integrations as $integration) {
            $last_syncs = $integration->IntegrationSyncs()->latest()->first();
            // for empty syncs
            if (empty($last_syncs)) {
                $dues[] = $integration;
                continue;
            }

            // check the last sync status
            if ($last_syncs->sync_status == IntegrationSyncs::SYNC_STATUS_PENDING ||
                $last_syncs->sync_status == IntegrationSyncs::SYNC_STATUS_PROCESSING
            ) {
                // \Log::info('last sync is still pending or processing');
                continue;
            }

            // if period end is null
            if (empty($last_syncs->period_end)) {
                $dues[] = $integration;
            }

            // checks last sync and frequency
            $now = new \DateTime();
            $last = new \DateTime($last_syncs->period_end);
            $last->modify('+' . $integration->frequency_mins  . ' minutes');
            if ($last <= $now) {
                // This is for computing period start sync
                $integration->last_sync_end_period = $last_syncs->period_end;
                $dues[] = $integration;
            }
        }

        return $dues;
    }
}
