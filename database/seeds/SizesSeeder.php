<?php

use Illuminate\Database\Seeder;
use App\Size;

class SizesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $size = new Size();
        $size->name_en = 'XL';
        $size->name_ar = 'XL';
        $size->is_public = 'yes';
        $size->owner = 'admin';
        $size->department_id = 1;
        $size->save();
    }
}
