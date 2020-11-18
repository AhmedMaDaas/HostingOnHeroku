<?php

use Illuminate\Database\Seeder;
use App\Country;

class CountriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $country = new Country();
        $country->name_en = 'Syria';
        $country->name_ar = 'سورا';
        $country->mob = '963';
        $country->code = 'SYR';
        $country->currency = 'SP';
        $country->save();

        $country = new Country();
        $country->name_en = 'Egypt';
        $country->name_ar = 'مصر';
        $country->mob = '986';
        $country->code = 'EG';
        $country->currency = 'G';
        $country->save();
    }
}
