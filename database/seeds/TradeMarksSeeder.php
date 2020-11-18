<?php

use Illuminate\Database\Seeder;

use App\TradeMark;

class TradeMarksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tradeMark = new TradeMark();
        $tradeMark->name_en = 'adidas';
        $tradeMark->name_ar = 'أديداس';
        $tradeMark->owner = 'admin';
        $tradeMark->save();

        $tradeMark = new TradeMark();
        $tradeMark->name_en = 'lacost';
        $tradeMark->name_ar = 'لاكوست';
        $tradeMark->owner = 'admin';
        $tradeMark->save();
    }
}
