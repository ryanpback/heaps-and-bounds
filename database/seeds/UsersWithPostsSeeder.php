<?php

use Illuminate\Database\Seeder;
use App\Traits\FactoryTraits;

class UsersWithPostsSeeder extends Seeder
{
    use FactoryTraits;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        return $this->createUsersWithPosts(50, 7);
    }
}
