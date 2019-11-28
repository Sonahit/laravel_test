<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateViewFlightPlanPrices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("
          CREATE OR REPLACE VIEW flight_plan_prices AS
          SELECT
            fl.flight_id,
            fl.flight_date,
            billed_meals.id as billed_meals_id,
            new_matrix_prices.*
          FROM
            flight_load AS fl
          INNER JOIN billed_meals ON
            billed_meals.flight_load_id = fl.id
          INNER JOIN new_matrix_prices ON
            billed_meals.iata_code = new_matrix_prices.iata_code
            AND	fl.business = new_matrix_prices.passenger_amount
          GROUP BY billed_meals.id, new_matrix_prices.iata_code, new_matrix_prices.passenger_amount;
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('DROP VIEW flight_plan_prices');
    }
}
