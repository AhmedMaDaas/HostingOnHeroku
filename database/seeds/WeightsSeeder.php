<?php

use Illuminate\Database\Seeder;

use App\Weight;

class WeightsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $weight = new Weight();
        $weight->name_en = 'kilo gram';
        $weight->name_ar = 'كيلو غرام';
        $weight->owner = 'admin';
        $weight->save();

        $weight = new Weight();
        $weight->name_en = 'gram';
        $weight->name_ar = 'غرام';
        $weight->owner = 'admin';
        $weight->save();
    }
}
