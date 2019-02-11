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
        // Create users that haven't completed their profile. Users have no username, first or last names.
        factory(App\Models\User::class, 'new', 25)->create();

        // Create users with full user profile
        factory(App\Models\User::class, 'full', 30)->create();
    }
}
