<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserCompanyLinks extends Seeder
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
            DB::table('user_company_links')->insert([
                'user_ref' => rand(2, 51),
                'comp_ref' => rand(1, 20)
            ]);
        }
    }
}
