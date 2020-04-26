<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;

use App\Models\IntegrationRecords;

class IntegrationRecordsRepository extends BaseRepository
{
    public function model()
    {
        return IntegrationRecords::class;
    }

    protected $fieldSearchable = [
        'integration.label' => 'like',
        'integration_sync_id',
        'source_id' => 'like',
        'machship_id' => 'like',
        'machship_consignment_status',
        'record_status'
    ];

    public function boot()
    {
        $this->pushCriteria(app('Prettus\Repository\Criteria\RequestCriteria'));
    }

    public function dedup($integration_id, $source_id)
    {
        $dups = $this->where('source_id', $source_id)
                            ->where('integration_id', $integration_id)
                            ->count();
        return $dups > 0;
    }
}
