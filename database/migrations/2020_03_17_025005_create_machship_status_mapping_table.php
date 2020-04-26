<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMachshipStatusMappingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('machship_status_mapping', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('integration_id')->nullable();
            $table->integer('machship_status_id')->nullable();
            $table->string('machship_status')->nullable();
            $table->string('record_status')->nullable();
            $table->boolean('update_source')->nullable();
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
        Schema::dropIfExists('machship_status_mapping');
    }
}
