<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i=0; $i<20; $i++)
        {
            DB::table('companies')->insert([
                'comp_name' => "Comp".$i+1,
                'comp_care_no' => $randnum = rand(1111111111,9999999999),
                'comp_addr' => "comp_addr".$i+1,
                'status' => 1
            ]);
        }
    }
}
