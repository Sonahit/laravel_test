<?php

use App\Models\Meal_Info;
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
        $select = New_Matrix::select('*')->groupBy('meal_id');
        Meal_Info::insertUsing('*', $select);
    }
}
