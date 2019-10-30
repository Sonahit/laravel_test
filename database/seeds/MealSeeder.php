<?php

use App\Models\New_Matrix;
use Illuminate\Database\Seeder;
use App\Utils\Helpers\DatabaseHelper;

class MealSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //TODO: simulate query without get
        $meal_infos = New_Matrix::groupBy('meal_id')
            ->get();
        DatabaseHelper::updateOrInsert('meal_info',$meal_infos,[
            'meal_id',
            'meal_type',
            'nomenclature'
        ]);
    }
}
