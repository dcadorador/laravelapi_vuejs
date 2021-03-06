<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateIntegrationTypesTableAddDisplayName extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('integration_types', function (Blueprint $table) {
            $table->string('display_name')->after('name')->nullable();
        });

        $seeder = new IntegrationTypeDisplayNameSeeder();
        $seeder->run();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('integration_types', function (Blueprint $table) {
            $table->dropColumn('display_name');
        });
    }
}
