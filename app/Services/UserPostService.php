<?php

namespace App\Services;

use App\Models\User;

class UserPostService
{
    private $user;

    /**
     * Construct a new instance
     *
     * @param User $u
     */
    public function __construct(User $u)
    {
        $this->user = $u;
    }

    /**
     * User post creation
     *
     * @param array $postData
     * @return Post
     */
    public function createPost(array $postData)
    {
        return $this->user->posts()->create($postData);
    }

    /**
     * User post update
     *
     * @param array $data
     * @return Post/null
     */
    public function updatePost(array $data)
    {
        $post = $this->user->getPost($data['post_id']);

        if (!is_null($post)) {
            $post->update($data);
        }

        return $post;
    }

    /**
     * User post move to trash (soft delete)
     *
     * @param integer $postId
     * @return Collection/null
     */
    public function trashPost(int $postId)
    {
        $post = $this->user->getPost($postId);

        if (is_null($post)) {
            return $post;
        }

        $post->delete();

        return $this->user->withPosts()->get();
    }

    /**
     * User post deletion (force delete)
     *
     * @param integer $postId
     * @return Collection/null
     */
    public function deletePost(int $postId)
    {
        $post = $this->user->getPost($postId);

        if (is_null($post)) {
            return $post;
        }

        $post->forceDelete();

        return $this->user->withPosts()->get();
    }

    /**
     * User post restoration
     *
     * @param integer $postId
     * @return Collection/null
     */
    public function restorePost(int $postId)
    {
        $post = $this->user->getPost($postId, true);

        if (is_null($post)) {
            return $post;
        }

        $post->restore();

        return $this->user->withPosts()->get();
    }

    /**
     * Check if user has post and mark it pinned
     *
     * @param integer $postId
     * @return Post/null $post
     */
    public function pinPost(int $postId)
    {
        $post = $this->user->getPost($postId);

        if (!is_null($post)) {
            $post = $this->user->pin($post);
        }

        return $post;
    }

    /**
     * Check if user has post and un-pin it
     *
     * @param integer $postId
     * @return Post/null $post
     */
    public function unpinPost(int $postId)
    {
        $post = $this->user->getPost($postId);

        if (!is_null($post)) {
            $post = $this->user->unpin($post);
        }

        return $post;
    }

    /**
     * User can set post status to published
     *
     * @param integer $postId
     * @return Post/null $post
     */
    public function publishPost(int $postId)
    {
        $post = $this->user->getPost($postId);

        if (!is_null($post)) {
            $post = $this->user->publish($post);
        }

        return $post;
    }

    /**
     * User can set post status from published tp draft
     *
     * @param integer $postId
     * @return Post/null $post
     */
    public function unpublishPost(int $postId)
    {
        $post = $this->user->getPost($postId);

        if (!is_null($post)) {
            $post = $this->user->unpublish($post);
        }

        return $post;
    }
}
