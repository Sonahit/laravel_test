<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBilledMealsInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('billed_meals_info')) return;
        Schema::create('billed_meals_info', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->char('name', 150);
            $table->char('nomenclature', 30)->nullable();
            $table->char('iata_code', 10)->nullable();
            $table->char('type', 20)->nullable();
            $table->char('class', 20)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('billed_meals_info');
    }
}
