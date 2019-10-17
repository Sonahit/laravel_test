<?php

use App\New_Matrix;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MealSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $meal_infos = New_Matrix::groupBy('meal_id')
            ->get();
        foreach ($meal_infos as $meal_info) {
            DB::table('meal_info')->updateOrInsert([
                'meal_id' => $meal_info->meal_id,
                'meal_type' => $meal_info->meal_type,
                'nomenclature' => $meal_info -> nomenclature,
            ]);
        };

        Schema::table('new_matrix', function(Blueprint $table){
            $table->dropColumn(['nomenclature', 'meal_type']);
        });
    }
}
