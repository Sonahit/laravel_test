<?php

use App\Models\Billed_Meals;
use App\Models\Billed_Meals_Info;
use App\Models\Billed_Meals_Prices;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BilledMealsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $rows = ['nomenclature', 'iata_code', 'type', 'class', 'name' ];
        $select = Billed_Meals::withoutGlobalScope('january_business')
                    ->select($rows)
                    ->groupBy('nomenclature');
        Billed_Meals_Info::insertUsing($rows, $select);
        // DB::table('test_billed_meals_info')->insertUsing($rows, $select);
        $rows = ['billed_meals_id', 'delivery_number', 'qty', 'price_per_one', 'total', 'total_novat_discounted'];
        $select = Billed_Meals::withoutGlobalScope('january_business')
                    ->select(
                        ['id', 'delivery_number', 'qty', 'price_per_one', 'total', 'total_novat_discounted']
                    );
        Billed_Meals_Prices::insertUsing($rows, $select);
        // DB::table('test_billed_meals_prices')->insertUsing($rows, $select);
    }
}
