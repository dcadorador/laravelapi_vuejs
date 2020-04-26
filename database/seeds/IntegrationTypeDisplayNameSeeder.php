<?php

use Illuminate\Database\Seeder;
use App\Models\IntegrationType;

class IntegrationTypeDisplayNameSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        IntegrationType::get()->each(function ($type) {
            switch ($type->name) {
                case 'Netsuite':
                    $type->display_name = 'Netsuite ERP System';
                    break;
                case 'Pronto':
                    $type->display_name = 'Pronto Software';
                    break;
                case 'Myob':
                    $type->display_name = 'MYOB Advanced';
                    break;
                case 'Ebay':
                    $type->display_name = 'eBay';
                    break;
                case 'Dear':
                    $type->display_name = 'DEAR Systems';
                    break;
            }

            $type->save();
        });
    }
}
