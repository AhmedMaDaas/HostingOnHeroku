<?php

use Illuminate\Database\Seeder;
use App\City;

class CitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $city = new City();
        $city->name_en = 'Damascus';
        $city->name_ar = 'دمشق';
        $city->country_id = 1;
        $city->save();

        $city = new City();
        $city->name_en = 'Alcairo';
        $city->name_ar = 'القاهرة';
        $city->country_id = 2;
        $city->save();
    }
}
