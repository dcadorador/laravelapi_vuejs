<?php

namespace App\Process;

use Symfony\Component\HttpFoundation\ParameterBag;
use Carbon\Carbon;
use App\Repositories\IntegrationRepository;
use App\Repositories\IntegrationSyncsRepository;
use App\Traits\LoggerTrait;
use App\Models\IntegrationSyncs;

/**
 * This class describes a check due integration sync.
 * WF-1 Check Due Sync and Create Sync Jobs
 * 1. This will be scheduled via cron
 * 2. scan integration and its last integration sync
 * 3. find sync dues thru sync's last_period and integration frequency
 * 4. any return result will create integration sync
 * 5. then proceed to sync processing
 */
class CheckDueSync
{
    use LoggerTrait;

    // due integrations
    private $dues;
    private $syncs;
    private $repository;

    const TAG = '[CHECKDUE][SYNC]';

    public function __construct()
    {
        // $this->infoLog('init');

        $this->repository = new IntegrationRepository(app());
        $this->getDueSync();
        $this->createSync();
        $this->processSync();
    }

    /**
     * Gets the due integrations from last integration syncs.
     */
    private function getDueSync()
    {
        $this->dues = $this->repository->findWithSyncLastActive();
    }

    /**
     * Creates an integration sync
     */
    private function createSync()
    {
        if (!empty($this->dues)) {
            $integration_sync_repository = new IntegrationSyncsRepository(app());
            foreach ($this->dues as $due) {
                $params = new ParameterBag();
                $params->set('integration_id', $due->id);

                // NOTE: We need to determine period start HERE!
                $period_start = new \DateTime($due->last_sync_end_period);
                // apply offset
                $period_start->modify('-' . $due->offset . ' minutes');
                $params->set('period_start', $period_start);
                $params->set('period_end', Carbon::now()->toDateTimeString());
                $params->set('sync_status', IntegrationSyncs::SYNC_STATUS_PENDING);
                $this->syncs[] = $integration_sync_repository->create($params->all());
            }
            $this->infoLog('created sync');
        }
    }

    /**
     * Split process each syncs
     */
    private function processSync()
    {
        if (!empty($this->syncs)) {
            $this->infoLog('process sync start');
            foreach ($this->syncs as $sync) {
                // set last run
                $integration = $sync->integration;
                $integration->last_run = date('Y-m-d H:i:s');
                $integration->save();
                // send process
                $process = new Process($integration);
                $process->mainSyncProcess($sync);
            }
            $this->infoLog('process sync end');
        }
    }
}
