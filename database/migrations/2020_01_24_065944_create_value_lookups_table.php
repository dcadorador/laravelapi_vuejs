<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateValueLookupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('value_lookups', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('integration_id');
            $table->string('machship_field')->nullable();
            $table->string('from_value')->nullable();
            $table->string('from_label')->nullable();
            $table->string('to_value')->nullable();
            $table->string('to_label')->nullable();
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
        Schema::dropIfExists('value_lookups');
    }
}
