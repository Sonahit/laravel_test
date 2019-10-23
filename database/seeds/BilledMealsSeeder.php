<?php

use App\Models\Billed_Meals;
use App\Utils\Helpers\DatabaseHelper;
use Illuminate\Database\Seeder;

class BilledMealsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        info('Started seeding billed_meals_info');
        $rows = ['nomenclature', 'name', 'iata_code', 'type', 'class'];
        $billed_meals_info = Billed_Meals::withoutGlobalScope('january_business')
            ->select($rows)
            ->groupBy('nomenclature')
            ->get();
        DatabaseHelper::updateOrInsert('billed_meals_info', $billed_meals_info, $rows);
        info('Done');
        info('Started seeding billed_meals_prices');
        $rows = ['name', 'delivery_number', 'qty' ,'price_per_one', 'total', 'total_novat_discounted'];
        $billed_meals_price = Billed_Meals::withoutGlobalScope('january_business')
            ->select($rows)->groupBy('name', 'price_per_one', 'total')
            ->get();
        DatabaseHelper::updateOrInsert('billed_meals_prices', $billed_meals_price, $rows);
        info('Done');
    }
}
