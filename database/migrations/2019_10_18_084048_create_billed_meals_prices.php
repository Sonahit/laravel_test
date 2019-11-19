<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBilledMealsPrices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('billed_meals_prices')) return;
        Schema::create('billed_meals_prices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('billed_meals_id')->unique();
            $table->integer('delivery_number');
            $table->integer('qty')->nullable();
            $table->float('price_per_one')->nullable();
            $table->float('total')->nullable();
            $table->float('total_novat_discounted')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('billed_meals_price');
    }
}
