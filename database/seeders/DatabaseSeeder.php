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
       DB::table('users')->insert(
        'firstname' => 'Admin',
        'lastname' => 'Jetfarms',
        'name' => 'Admin Jetfarms',
        'email' => 'jetfarmsAdmin@gmail.com',
        'password' => bcrypt('jetfarms2019'),
        'expires' => true,
        'expired' => true
       )
    }
}
