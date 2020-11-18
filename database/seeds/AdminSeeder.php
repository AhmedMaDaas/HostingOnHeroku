<?php

use Illuminate\Database\Seeder;

use App\Admin;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
    	Admin::create([
    		'name' => 'Ahmed',
    		'email' => 'ahmed@admin.com',
    		'password' => Hash::make(123045)
    	]);
    }
}
