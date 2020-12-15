<?php

use Illuminate\Database\Seeder;
use App\User;
use Illuminate\Hashing\BcryptHasher;

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
            'password' => BcryptHasher('123456'),
            'four_key' => '1234',
            'profile_image' => 'avatar.png'
        ]);
    }
}
