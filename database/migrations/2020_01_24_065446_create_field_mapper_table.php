<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFieldMapperTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('field_mapper', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('integration_id');
            $table->string('data_direction');
            $table->string('machship_field');
            $table->string('map_type')->nullable();
            $table->string('source_field')->nullable();
            $table->string('data_conversion_type')->nullable();
            $table->string('data_conversion_value')->nullable();
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
        Schema::dropIfExists('field_mapper');
    }
}
