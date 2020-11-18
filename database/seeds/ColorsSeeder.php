<?php

use Illuminate\Database\Seeder;
use App\Color;

class ColorsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $color = new Color();
        $color->name_en = 'red';
        $color->name_ar = 'أحمر';
        $color->color = '#f40101';
        $color->owner = 'admin';
        $color->save();

        $color = new Color();
        $color->name_en = 'black';
        $color->name_ar = 'أسود';
        $color->color = '#050505';
        $color->owner = 'admin';
        $color->save();

        $color = new Color();
        $color->name_en = 'green';
        $color->name_ar = 'أخضر';
        $color->color = '#00fa2a';
        $color->owner = 'admin';
        $color->save();
    }
}
