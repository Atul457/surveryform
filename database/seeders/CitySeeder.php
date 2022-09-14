<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $cities = [ "Mumbai", "Delhi", "Kolkata", "Chennai", "Bengaluru", "Hyderabad", "Ahmadabad", "Pune", "Surat", "Jaipur", "Kanpur", "Lucknow", "Nagpur", "Ghaziabad", "Indore", "Coimbatore", "Kochi", "Patna", "Kozhikode", "Bhopal", "Thrissur", "Vadodara", "Agra", "Malappuram", "Thiruvananthapuram", "Kannur", "Ludhiana", "Nashik", "Vijayawada", "Madurai", "Varanasi", "Meerut", "Faridabad", "Rajkot", "Jamshedpur", "Srinagar", "Jabalpur", "Asansol", "Vasai Virar City", "Allahabad", "Dhanbad", "Aurangabad", "Amritsar", "Jodhpur", "Ranchi", "Raipur", "Kollam", "Gwalior", "Durg-Bhilainagar", "Chandigarh", "Kota", "Tiruchirappalli"];

        $i = 1;
        foreach ($cities as $city) {
            DB::table('cities')->insert([
                "id" => $i,
                'city_name' => $city,
            ]);
            $i++;
        }

    }
}
