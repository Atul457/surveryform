<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
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
            DB::table('products')->insert([
                'prod_name' => "Prod".$i+1,
                'batch_no' => rand(1111,9999),
                'city' => "city".$i+1,
                'comp_id' => rand(1, 20),
                'status' => 1
            ]);
        }
    }
}
