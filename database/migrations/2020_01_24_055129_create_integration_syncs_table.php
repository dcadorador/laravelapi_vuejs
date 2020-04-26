<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIntegrationSyncsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('integration_syncs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('integration_id');
            $table->dateTime('period_start')->nullable();
            $table->dateTime('period_end')->nullable();
            $table->integer('records_found')->nullable();
            $table->string('sync_status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('integration_syncs');
    }
}
