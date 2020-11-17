<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
       DB::table('users')->insert([
        'firstname' => 'Admin',
        'lastname' => 'Jetfarms',
        'role' => 'admin',
        'email' => 'jetfarmsadmin@gmail.com',
        'password' => bcrypt('jetfarms2020'),
        'expires' => true,
        'expired' => true
        ]);
    }
}
