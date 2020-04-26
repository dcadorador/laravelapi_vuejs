<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Illuminate\Support\Facades\Hash;
use App\Models\IntegrationSyncs;
use Carbon\Carbon;

class IntegrationSyncsRepository extends BaseRepository
{
    public function model()
    {
        return IntegrationSyncs::class;
    }

    protected $fieldSearchable = [
        'period_start' => 'like',
        'period_end' => 'like',
        'integration.label' => 'like',
        'sync_status'
    ];

    public function boot()
    {
        $this->pushCriteria(app('Prettus\Repository\Criteria\RequestCriteria'));
    }

    /**
     * @return array
     */
    public function findDueForSourceUpdate()
    {
        $syncs = $this->model->where('sync_status', IntegrationSyncs::SYNC_STATUS_COMPLETED)->get();
        $now = Carbon::now();

        $for_updates = [];
        if (!$syncs->isEmpty()) {
            foreach ($syncs as $sync) {
                $latest_sync = $this->model->where('integration_id', $sync->integration_id)->latest()->first();
                if (empty($latest_sync)) {
                    $for_updates[] = $sync;
                    continue;
                }

                $last_sync_time = Carbon::parse($sync->updated_at);
                if ($last_sync_time->addMinutes(15)->lte($now)) {
                    $for_updates[] = $sync;
                    continue;
                }
            }
        }

        return $for_updates;
    }
}
