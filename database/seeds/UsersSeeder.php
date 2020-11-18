<?php

use Illuminate\Database\Seeder;
use App\User;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new User();
        $user->name = 'user';
        $user->email = 'user@user.com';
        $user->password = Hash::make(123045);
        $user->level = 'user';
        $user->phone = '0321456987';
        $user->save();

        $user = new User();
        $user->name = 'mall manager';
        $user->email = 'manager@manager.com';
        $user->password = Hash::make(123045);
        $user->level = 'mall';
        $user->phone = '0321456987';
        $user->save();

        $user = new User();
        $user->name = 'mall manager';
        $user->email = 'manager1@manager.com';
        $user->password = Hash::make(123045);
        $user->level = 'mall';
        $user->phone = '0321456987';
        $user->save();

        $user = new User();
        $user->name = 'company';
        $user->email = 'company@company.com';
        $user->password = Hash::make(123045);
        $user->level = 'company';
        $user->phone = '0321456987';
        $user->save();
    }
}
