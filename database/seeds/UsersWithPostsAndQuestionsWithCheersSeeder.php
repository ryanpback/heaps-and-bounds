<?php

use Illuminate\Database\Seeder;
use App\Traits\FactoryTraits;

class UsersWithPostsAndQuestionsWithCheersSeeder extends Seeder
{
    use FactoryTraits;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->createUsersWithPostsAndQuestionsWithCheers(7, 7, 7);
    }
}
