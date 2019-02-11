<?php

namespace App\Traits;

use App\Models\Post;
use App\Models\Question;
use App\Models\User;

trait FactoryTraits
{
    /**
     * Create any number of users, each who have a number of posts
     *
     * @param integer $userCount
     * @param integer $postCount
     * @return Collection User
     */
    private function createUsersWithPosts($userCount = 1, $postCount = 1)
    {
        $users = factory(User::class, 'full', $userCount)
        ->create()
        ->each(function ($u) use ($postCount) {
            factory(Post::class, $postCount)
                ->make()
                ->each(function ($p) use ($u) {
                    $u->posts()->create(['title' => $p->title, 'post_content' => $p->post_content]);
                });
        });

        return $users;
    }

    /**
     * Create any number of users, each who have a number of questions
     *
     * @param integer $userCount
     * @param integer $questionCount
     * @return Collection User
     */
    private function createUsersWithQuestions($userCount = 1, $questionCount = 1)
    {
        $users = factory(User::class, 'full', $userCount)
        ->create()
        ->each(function ($u) use ($questionCount) {
            factory(Question::class, $questionCount)
                ->make()
                ->each(function ($q) use ($u) {
                    $u->questions()->create(['title' => $q->title, 'question_content' => $q->question_content]);
                });
        });

        return $users;
    }

    /**
     * Create any number of users, each who have a number of posts and questions
     *
     * @param integer $userCount
     * @param integer $postCount
     * @param integer $questionCount
     * @return Collection User
     */
    private function createUsersWithPostsAndQuestions($userCount = 1, $postCount = 1, $questionCount = 1)
    {
        $users = factory(User::class, 'full', $userCount)
            ->create()
            ->each(function ($u) use ($postCount, $questionCount) {
                factory(Post::class, $postCount)
                    ->make()
                    ->each(function ($p) use ($u) {
                        $u->posts()->create(['title' => $p->title, 'post_content' => $p->post_content]);
                    });

                factory(Question::class, $questionCount)
                    ->make()
                    ->each(function ($q) use ($u) {
                        $u->questions()->create(['title' => $q->title, 'question_content' => $q->question_content]);
                    });
            });

        return $users;
    }
}
