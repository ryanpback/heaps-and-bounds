<?php

namespace Tests\Unit;

use App\Models\Post;
use App\Models\User;
use App\Services\UserPostService;
use App\Traits\FactoryTraits;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class UserPostTest extends TestCase
{
    use RefreshDatabase;
    use WithoutMiddleware;
    use FactoryTraits;

    /**
     * Can't create post without post_content
     * @expectedException Illuminate\Database\QueryException
     *
     * @return void
     */
    public function testCantCreatePostWithoutPostContent()
    {
        $user = factory(User::class, 'new')->create();

        $postData = [
            'title'         => 'Test Post',
        ];

        $service = new UserPostService($user);
        $service->createPost($postData);

        $this->setExpectedException('Illuminate\Database\QueryException');
    }

    /**
     * Can't create post without post_title
     * @expectedException Illuminate\Database\QueryException
     *
     * @return void
     */
    public function testCantCreatePostWithoutPostTitle()
    {
        $user = factory(User::class, 'new')->create();

        $postData = [
            'post_content'  => 'This is test post content',
        ];

        $service = new UserPostService($user);
        $service->createPost($postData);

        $this->setExpectedException('Illuminate\Database\QueryException');
    }

    /**
     * User can successfully create a post
     *
     * @return void
     */
    public function testUserCanSuccessfullyCreateAPost()
    {
        $user = factory(User::class, 'new')->create();

        $postData = [
            'post_content'  => 'This is test post content',
            'title'         => 'Test Post',
        ];

        $service = new UserPostService($user);
        $service->createPost($postData);

        $this->assertEquals(1, $user->posts()->count());
    }

    /**
     * User can successfully update a post
     *
     * @return void
     */
    public function testUserCanSuccessfullyUpdateAPost()
    {
        // createUsersWithPosts is a trait method
        $users = $this->createUsersWithPosts(1, 1);
        $user = $users[0];

        $post = $user->posts()->first();

        // save for assertion
        $originalPostTitle = $post->title;
        $originalPostContent = $post->post_content;

        $postId = $post->id;
        $data = [
            'post_content'  => 'New content',
            'title'         => 'New title',
            'post_id'       => $postId
        ];

        $service = new UserPostService($user);
        $updatedPost = $service->updatePost($data);

        $this->assertNotEquals($originalPostTitle, $updatedPost->title);
        $this->assertNotEquals($originalPostContent, $updatedPost->post_content);
        $this->assertEquals('New title', $updatedPost->title);
        $this->assertEquals('New content', $updatedPost->post_content);
    }

    /**
     * User can successfully trash (soft delete) a post
     *
     * @return void
     */
    public function testUserCanSuccessfullyTrashAPost()
    {
        // createUsersWithPosts is a trait method
        $users = $this->createUsersWithPosts(1, 2);
        $user = $users[0];

        $post = $user->posts()->first();
        $postId = $post->id;

        $service = new UserPostService($user);
        $service->trashPost($postId);
        $deletedPosts = $user->getMyTrashedPosts();

        $this->assertEquals(1, count($deletedPosts));
    }

    /**
     * User can successfully delete (force delete) a post
     *
     * @return void
     */
    public function testUserCanSuccessfullyDeleteAPost()
    {
        // createUsersWithPosts is a trait method
        $users = $this->createUsersWithPosts(1, 2);
        $user = $users[0];

        $post = $user->posts()->first();
        $postId = $post->id;
        $this->assertEquals(2, count($user->posts()->get()));

        $service = new UserPostService($user);
        $service->deletePost($postId);

        $deletedPosts = $user->getMyTrashedPosts();
        $this->assertEquals(0, count($deletedPosts));

        $this->assertEquals(1, count($user->posts()->get()));
    }

    /**
     * User can successfully restore a post
     *
     * @return void
     */
    public function testUserCanSuccessfullyRestoreAPost()
    {
        // createUsersWithPosts is a trait method
        $users = $this->createUsersWithPosts(1, 2);
        $user = $users[0];

        $post = $user->posts()->first();
        $postId = $post->id;
        $this->assertEquals(2, count($user->posts()->get()));

        $service = new UserPostService($user);
        $service->trashPost($postId);

        $deletedPosts = $user->getMyTrashedPosts();
        $this->assertEquals(1, count($deletedPosts));

        $deletedPost = $deletedPosts[0];
        $service->restorePost($deletedPost->id);

        $this->assertEquals(0, count($user->getMyTrashedPosts()));
        $this->assertEquals(2, count($user->posts()->get()));
    }

    /**
    * User can pin a post
    *
    * @return void
    */
    public function testUserCanPinAPost()
    {
        $users = $this->createUsersWithPosts(1, 2);
        $user = $users[0];

        $post = $user->posts()->first();
        $postId = $post->id;

        $this->assertEquals(0, $post->pinned);

        $service = new UserPostService($user);
        $service->pinPost($postId);

        $post = Post::find($postId);

        $this->assertEquals(1, $post->pinned);
    }

    /**
    * User can unpin a post
    *
    * @return void
    */
    public function testUserCanUnpinAPost()
    {
        $users = $this->createUsersWithPosts(1, 2);
        $user = $users[0];

        $post = $user->posts()->first();
        $postId = $post->id;

        $service = new UserPostService($user);
        $post = $service->pinPost($postId);

        $this->assertEquals(1, $post->pinned);

        $post = $service->unpinPost($post->id);

        $this->assertEquals(0, $post->pinned);
    }
}
