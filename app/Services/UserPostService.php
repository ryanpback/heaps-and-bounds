<?php

namespace App\Services;

use App\Models\User;

class UserPostService
{
    /**
     * User post creation
     *
     * @param User $u
     * @param array $postData
     * @return Post/null
     */
    public function createPost(User $u, array $postData)
    {
        $post = $u->posts()->create($postData);

        return $post;
    }

    /**
     * User post update
     *
     * @param User $u
     * @param array $data
     * @return Post/null
     */
    public function updatePost(User $u, array $data)
    {
        $post = $u->getPost($data['post_id']);

        if (!is_null($post)) {
            $post->update($data);
        }

        return $post;
    }

    /**
     * User post deletion
     *
     * @param User $u
     * @param integer $postId
     * @return Collection/null
     */
    public function deletePost(User $u, int $postId)
    {
        $post = $u->getPost($postId);

        if (is_null($post)) {
            return $post;
        }

        $post->delete();

        return $u->with('posts')->get();
    }

    /**
     * Check if user has post and mark it pinned
     *
     * @param User $u
     * @param integer $postId
     * @return Collection/null
     */
    public function pinPost(User $u, int $postId)
    {
        $post = $u->getPost($postId);

        if (!is_null($post)) {
            $post = $u->pin($post);
        }

        return $post;
    }

    /**
     * Check if user has post and un-pin it
     *
     * @param User $u
     * @param integer $postId
     * @return Collection/null
     */
    public function unpinPost(User $u, int $postId)
    {
        $post = $u->getPost($postId);

        if (!is_null($post)) {
            $post = $u->unpin($post);
        }

        return $post;
    }
}
