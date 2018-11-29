<?php

namespace App\Traits;

use App\Models\Post;
use App\Models\User;

trait FactoryTraits
{
    private function createUsersWithPosts($userCount = 1, $postCount = 1)
    {
        $usersWithPosts = factory(User::class, 'full', $userCount)
        ->create()
        ->each(function ($u) use ($postCount) {
            $posts = factory(Post::class, $postCount)
                ->make()
                ->each(function ($p) use ($u) {
                    $u->posts()->create(['title' => $p->title, 'post_content' => $p->post_content]);
                });
        });

        return $usersWithPosts;
    }
}
