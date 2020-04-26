<?php

use Illuminate\Database\Seeder;

use App\Models\IntegrationType;

class IntegrationTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        IntegrationType::truncate();

        $data = [
            ['id' => 1, 'name' => 'Netsuite', 'directory' => '\\App\\Services\\Platforms\\Netsuite'],
            ['id' => 2, 'name' => 'Pronto', 'directory' => '\\App\\Services\\Platforms\\Pronto'],
            ['id' => 3, 'name' => 'Myob', 'directory' => '\\App\\Services\\Platforms\\Myob'],
            ['id' => 4, 'name' => 'Ebay', 'directory' => '\\App\\Services\\Platforms\\Ebay']
        ];

        IntegrationType::insert($data);
    }
}
