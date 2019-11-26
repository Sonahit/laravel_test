<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateViewBusinessBilledMeals extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("CREATE VIEW business_billed_meals AS
            SELECT
            flight_load.flight_date,
            flight_load.flight_id,
            billed_meals.class,
            billed_meals.`type`,
            GROUP_CONCAT(
                DISTINCT billed_meals.iata_code
            ) AS iata_codes,
            SUM(billed_meals.total) AS total,
            SUM(billed_meals.total_novat_discounted) AS total_novat_discounted,
            SUM(billed_meals.qty) AS qty,
            flight_load.business AS passengers_amount
        FROM
            flight_load
        INNER JOIN flight ON
            flight_load.flight_id = flight.id
        INNER JOIN billed_meals ON
            (
                billed_meals.flight_load_id = flight_load.id
                OR billed_meals.flight_load_id IS NULL
            )
            AND billed_meals.flight_date = flight_load.flight_date
            AND billed_meals.flight_id = flight.id
            AND billed_meals.iata_code <> 'ALC'
        WHERE
            billed_meals.`type` = 'Комплект'
            AND billed_meals.class = 'Бизнес'
        GROUP BY
            flight_load.flight_id,
            flight_load.flight_date;"
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('DROP VIEW IF EXISTS business_billed_meals');
    }
}




