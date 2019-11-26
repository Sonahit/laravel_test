<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateViewNewMatrixPrices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("CREATE VIEW new_matrix_prices AS
        SELECT
           new_matrix.iata_code AS iata_code,
           new_matrix.passenger_amount AS passenger_amount,
           SUM(new_matrix.meal_qty) AS meal_qty,
           new_matrix.nomenclature AS nomenclature,
           SUM(business_meal_prices.price * new_matrix.meal_qty) AS price
       FROM
           new_matrix
       INNER JOIN business_meal_prices ON
           business_meal_prices.nomenclature = new_matrix.nomenclature
       GROUP BY
           iata_code,
           passenger_amount;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('DROP VIEW IF EXISTS new_matrix_prices');
    }
}


