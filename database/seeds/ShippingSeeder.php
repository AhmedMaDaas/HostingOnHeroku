<?php

use Illuminate\Database\Seeder;
use App\Shipping;

class ShippingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $shipping = new Shipping();
        $shipping->name_en = 'syria shipping';
        $shipping->name_ar = 'شركة مصنعة سورية';
        $shipping->user_id = 4;
        $shipping->save();
    }
}
