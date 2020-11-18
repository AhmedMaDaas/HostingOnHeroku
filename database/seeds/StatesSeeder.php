<?php

use Illuminate\Database\Seeder;
use App\State;

class StatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $state = new State();
        $state->name_en = 'Alhamarah Street';
        $state->name_ar = 'شارع الحمرة';
        $state->country_id = 1;
        $state->city_id = 1;
        $state->save();

        $state = new State();
        $state->name_en = 'Alrasheed Street';
        $state->name_ar = 'شارع الرشيد';
        $state->country_id = 2;
        $state->city_id = 2;
        $state->save();
    }
}
