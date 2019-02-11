<?php

use Illuminate\Database\Seeder;
use App\Traits\FactoryTraits;

class UsersWithQuestionsSeeder extends Seeder
{
    use FactoryTraits;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->createUsersWithQuestions(50, 7);
    }
}
