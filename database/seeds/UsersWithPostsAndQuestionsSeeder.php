<?php

use Illuminate\Database\Seeder;
use App\Traits\FactoryTraits;

class UsersWithPostsAndQuestionsSeeder extends Seeder
{
    use FactoryTraits;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->createUsersWithPostsAndQuestions(10, 6, 20);
    }
}
