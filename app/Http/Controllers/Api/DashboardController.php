<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Controllers\ApiBaseController;
use App\Repositories\UserRepository;
use App\Repositories\AccountRepository;
use App\Repositories\IntegrationRepository;

class DashboardController extends ApiBaseController
{

    private $userRepository;
    private $accountRepository;
    private $integrationRepository;

    public function __construct(
        UserRepository $userRepository,
        AccountRepository $accountRepository,
        IntegrationRepository $integrationRepository
    ) {
        $this->userRepository = $userRepository;
        $this->accountRepository = $accountRepository;
        $this->integrationRepository = $integrationRepository;
    }

    public function index(Request $request)
    {
        // fetch number of users, accounts, integrations
        $totalUsers = $this->userRepository->count();
        $totalAccounts = $this->accountRepository->count();
        $totalIntegrations = $this->integrationRepository->count(['integration_status' => 'active']);

        $lastestIntegrations = $this->integrationRepository->latest(3);

        $result = [
            'cards' => [
                [
                    'id' => 'ca1',
                    'icon' => 'fa fa-users',
                    'title' => 'Number of Users',
                    'desc' => "There are $totalUsers total users",
                    'link' => '/users'
                ],

                [
                    'id' => 'ca2',
                    'icon' => 'fa fa-user',
                    'title' => 'Number of Accounts',
                    'desc' => "There are $totalAccounts accounts",
                    'link' => '/accounts'
                ],

                [
                    'id' => 'ca3',
                    'icon' => 'fa fa-globe',
                    'title' => 'Number of Integrations',
                    'desc' => "There are $totalIntegrations active integrations",
                    'link' => '/integrations'
                ],
            ]
        ];

        $result['integrations'] = $lastestIntegrations;

        return $result;
    }
}
