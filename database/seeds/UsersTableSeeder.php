<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (app()->environment('local')) {
            factory(\Departur\User::class)->create([
                'name'     => 'Administrator',
                'email'    => 'default@departur.se',
                'password' => bcrypt('password'),
            ]);
        }
    }
}
