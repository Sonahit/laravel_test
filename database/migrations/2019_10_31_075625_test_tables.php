<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TestTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('test_billed_meals_info') || Schema::hasTable('test_billed_meals_info')) $this->down();
        Schema::create('test_billed_meals_info', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->char('name', 150);
            $table->char('nomenclature', 30)->nullable();
            $table->char('iata_code', 10)->nullable();
            $table->char('type', 20)->nullable();
            $table->char('class', 20)->nullable();
        });
        Schema::create('test_billed_meals_prices', function (Blueprint $table) {
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
        Schema::dropIfExists('test_billed_meals_info');
        Schema::dropIfExists('test_billed_meals_prices');
    }
}
