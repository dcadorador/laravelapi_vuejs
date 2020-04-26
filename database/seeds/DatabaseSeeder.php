<?php

use Illuminate\Database\Seeder;
use App\Models\Integration;
use App\Models\Account;

class DatabaseSeeder extends Seeder
{
    const ACL = 'ACL';
    const DELF = 'DELF';
    const D2G = 'D2G';
    const DOMAIN_APPLIANCES = 'DOMAIN APPLIANCES';
    const DEAR = 'Dear';
    const ACTIVE = 'active';


    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);

        // clean up for the tables
        $this->accountAndIntegrationCleanup();

        // integration type seeder
        $this->call(IntegrationTypeSeeder::class);

        // use cases seeder for current clients
        //$this->call(IntegrationACLSeeder::class);
        //$this->call(IntegrationDELFSeeder::class);
        //$this->call(IntegrationD2GSeeder::class);
        //$this->call(IntegrationDearSeeder::class);
    }


    public function accountAndIntegrationCleanup()
    {
        Integration::truncate();
        Account::truncate();
    }
}
