<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class EmployeesSeeder extends Seeder
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
            DB::table('users')->insert([
                "id" => $i+2,
                'name' => "testuser".$i+1,
                'email' => "test".($i+1)."@gmail.com",
                'password' => Hash::make('123456'),
                'emp_code' => 1111+$i,
                'phone_no' => rand(1111111111, 9999999999),
                'is_admin' => 0,
                'status' => 1
            ]);
        }
    }   
}
