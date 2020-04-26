<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Repositories\IntegrationRepository;
use App\Services\MachshipCacheService;
use App\Libraries\Machship\Machship;
use Exception;

class MachshipCacheJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 1280;
    public $tries = 5;
    public $retryAfter = 30;

    private $integration_repository;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->integration_repository = new IntegrationRepository(app());
        $integrations = $this->integration_repository->findActives();
        foreach ($integrations as $integration) {
            $token = $integration->getMachshipTokenKey();
            if (empty($token)) {
                continue;
            }

            $machship = new Machship($token);
            $service = new MachshipCacheService($machship, $integration);
            $service->init();
        }
    }

    /**
     * The job failed to process.
     *
     * @param  Exception  $exception
     * @return void
     */
    public function failed(Exception $exception)
    {
        // TODO
    }
}
