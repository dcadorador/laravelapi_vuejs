<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDataDirectionToValueLookupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('value_lookups', function (Blueprint $table) {
            $table->string('data_direction')
                ->after('integration_id')
                ->default('to_machship');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('value_lookups', function (Blueprint $table) {
            $table->dropColumn('data_direction');
        });
    }
}
