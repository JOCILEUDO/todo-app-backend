<?php

use Illuminate\Database\Seeder;
use App\User;

class CreateUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'admin',
            'email' => 'jocileudo@notridan.com',
            'login' => 'admin',
            'type' => 'admin',
            'password' => '123456',
            'four_key' => '1234',
            'profile_image' => 'avatar.png'
        ]);
    }
}
