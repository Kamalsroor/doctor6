<?php

use App\User;
use Illuminate\Database\Seeder;

/**
 * Class RoleUserTableSeeder
 */
class RoleUserTableSeeder extends Seeder
{
    public function run()
    {
        User::findOrFail(1)->roles()->sync(1);
    }
}
