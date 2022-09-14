<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SurveyFormSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i=0; $i<50; $i++)
        {
            DB::table('survey_forms')->insert([
                'form_name' => "Form".$i+1,
                'prod_ref' => rand(1,50),
                'city' => "city".$i+1,
                'comp_id' => rand(1, 20),
                'status' => 1
            ]);
        }
    }
}
