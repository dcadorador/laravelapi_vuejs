<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIntegrationSourceFilterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('integration_source_filters', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('integration_id');
            $table->string('filter_key')->nullable();
            $table->longtext('filter_value')->nullable();
            $table->integer('integration_source_filter_id')->nullable();
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
        Schema::dropIfExists('integration_source_filters');
    }
}
