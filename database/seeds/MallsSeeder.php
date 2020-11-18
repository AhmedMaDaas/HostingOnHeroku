<?php

use Illuminate\Database\Seeder;
use App\Mall;

class MallsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $mall = new Mall();
        $mall->name_en = 'syria mall 1';
        $mall->name_ar = 'متجر سوريا 1';
        $mall->mobile = '0321456987';
        $mall->email = 'mall@mall.com';
        $mall->contact_name = 'contact name';
        $mall->country_id = 1;
        $mall->user_id = 2;
        $mall->save();

        $mall = new Mall();
        $mall->name_en = 'Egypt mall 1';
        $mall->name_ar = 'متجر مصر 1';
        $mall->mobile = '0321456987';
        $mall->email = 'mall@mall.com';
        $mall->contact_name = 'contact name';
        $mall->country_id = 2;
        $mall->user_id = 3;
        $mall->save();
    }
}
