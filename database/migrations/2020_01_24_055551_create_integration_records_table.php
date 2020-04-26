<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIntegrationRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('integration_records', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('integration_id');
            $table->integer('integration_sync_id');
            $table->string('source_id')->nullable();
            $table->longText('source_data')->nullable();
            $table->string('machship_id')->nullable();
            $table->string('machship_consignment_status', 100)->nullable();
            $table->longText('machship_payload')->nullable();
            $table->string('record_status', 100)->nullable();
            $table->string('consignment_type', 100)->nullable();
            $table->dateTime('process_after')->nullable();
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
        Schema::dropIfExists('integration_records');
    }
}
