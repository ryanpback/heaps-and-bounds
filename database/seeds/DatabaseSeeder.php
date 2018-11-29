<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        /**
         * TODO safeguard what gets seeded and when/what environment
         *
         * Ex. Don't create a bunch of fake users and posts on non-local
         * environments. Do seed user role types, notification types, etc.
         */
        $this->call([
            UsersTableSeeder::class,
            UsersWithPostsSeeder::class,
        ]);
    }
}
