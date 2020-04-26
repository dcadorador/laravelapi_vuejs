<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDebugLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('debug_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('integration_id');
            $table->bigInteger('integration_sync_id')->nullable();
            $table->bigInteger('integration_record_id')->nullable();
            $table->string('sync_step')->nullable();
            $table->longText('data')->nullable();
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
        Schema::dropIfExists('debug_logs');
    }
}
