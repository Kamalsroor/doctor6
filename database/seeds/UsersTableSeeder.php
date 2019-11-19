<?php

use App\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        $users = [
            [
                'id'             => 1,
                'name'           => 'Admin',
                'email'          => 'admin@admin.com',
                'password'       => '$2y$10$e3qNIWFPmknL6uu0OTBq4uu.18JDidizaPDgHZ647eaqU36bfGBj6',
                'remember_token' => null,
            ],
        ];

        User::insert($users);
    }
}
