<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateBilledMeals extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('billed_meals', function (Blueprint $table) {
            $table->index('iata_code');
            $table->index('flight_load_id');
            $table->index(['flight_id', 'flight_date']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('billed_meals', function (Blueprint $table) {
            $table->dropIndex('iata_code');
            $table->dropIdnex('flight_load_id');
            $table->dropIndex(['flight_id', 'flight_date']);
        });
    }
}
