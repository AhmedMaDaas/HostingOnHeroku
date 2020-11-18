<?php

use Illuminate\Database\Seeder;
use App\Manufacturer;

class ManufactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $manufacturer = new Manufacturer();
        $manufacturer->name_en = 'syria manufacturer';
        $manufacturer->name_ar = 'شركة مصنعة سورية';
        $manufacturer->mobile = '0321456987';
        $manufacturer->email = 'manufacturer@manufacturer.com';
        $manufacturer->contact_name = 'contact name';
        $manufacturer->owner = 'admin';
        $manufacturer->save();

        $manufacturer = new Manufacturer();
        $manufacturer->name_en = 'egypt manufacturer';
        $manufacturer->name_ar = 'شركة مصنعة مصرية';
        $manufacturer->mobile = '0321456987';
        $manufacturer->email = 'manufacturer@manufacturer.com';
        $manufacturer->contact_name = 'contact name';
        $manufacturer->owner = 'admin';
        $manufacturer->save();
    }
}
