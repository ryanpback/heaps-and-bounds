<?php

namespace Tests\Unit;

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
     * Can't create post without content
     *
     * @expectedException Illuminate\Database\QueryException
     * @return void
     */
    public function testCantCreatePostWithoutPostContent()
    {
        $user = factory(User::class, 'new')->create();

        $postData = [
            'title' => 'Test Post',
        ];

        $service = new UserPostService($user);
        $service->createPost($postData);

        $this->setExpectedException('Illuminate\Database\QueryException');
    }

    /**
     * Can't create post without post_title
     *
     * @expectedException Illuminate\Database\QueryException
     * @return void
     */
    public function testCantCreatePostWithoutPostTitle()
    {
        $user = factory(User::class, 'new')->create();

        $postData = [
            'content' => 'This is test post content',
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
            'content'   => 'This is test post content',
            'title'     => 'Test Post',
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
        $users  = $this->createUsersWithPosts(1, 1);
        $user   = $users[0];
        $post   = $user->posts()->first();

        // save for assertion
        $originalPostTitle      = $post->title;
        $originalPostContent    = $post->content;

        $data = [
            'content'   => 'New content',
            'title'     => 'New title',
            'post_id'   => $post->id
        ];

        $service        = new UserPostService($user);
        $updatedPost    = $service->updatePost($data);

        $this->assertNotEquals($originalPostTitle, $updatedPost->title);
        $this->assertNotEquals($originalPostContent, $updatedPost->content);
        $this->assertEquals('New title', $updatedPost->title);
        $this->assertEquals('New content', $updatedPost->content);
    }

    /**
     * User can successfully trash (soft delete) a post
     *
     * @return void
     */
    public function testUserCanSuccessfullyTrashAPost()
    {
        // createUsersWithPosts is a trait method
        $users      = $this->createUsersWithPosts(1, 2);
        $user       = $users[0];
        $post       = $user->posts()->first();

        $service = new UserPostService($user);
        $service->trashPost($post->id);

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
        $users      = $this->createUsersWithPosts(1, 2);
        $user       = $users[0];
        $post       = $user->posts()->first();

        $this->assertEquals(2, count($user->posts()->get()));

        $service = new UserPostService($user);
        $service->deletePost($post->id);

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
        $users      = $this->createUsersWithPosts(1, 2);
        $user       = $users[0];
        $post       = $user->posts()->first();

        $this->assertEquals(2, count($user->posts()->get()));

        $service = new UserPostService($user);
        $service->trashPost($post->id);

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
        $users      = $this->createUsersWithPosts(1, 2);
        $user       = $users[0];
        $post       = $user->posts()->first();

        $this->assertFalse($post->pinned);

        $service = new UserPostService($user);
        $service->pinPost($post->id);

        $this->assertTrue($post->fresh()->pinned);
    }

    /**
    * User can unpin a post
    *
    * @return void
    */
    public function testUserCanUnpinAPost()
    {
        $users      = $this->createUsersWithPosts(1, 2);
        $user       = $users[0];
        $post       = $user->posts()->first();

        $service    = new UserPostService($user);
        $post       = $service->pinPost($post->id);
        $this->assertEquals(1, $post->pinned);

        $post = $service->unpinPost($post->id);
        $this->assertFalse($post->pinned);
    }

    /**
     * User can pin only one post, so if another one is pinned,
     * it will be unpinned and the new one will be pinned
     *
     * @return void
     */
    public function testUserCanPinADifferentPost()
    {
        $users  = $this->createUsersWithPosts(1, 2);
        $user   = $users[0];
        $posts  = $user->posts()->get();

        $this->assertFalse($posts[0]->pinned);

        $service = new UserPostService($user);
        $service->pinPost($posts[0]->id);

        $service->pinPost($posts[1]->id);

        $this->assertFalse($posts[0]->fresh()->pinned);
        $this->assertTrue($posts[1]->fresh()->pinned);
    }

    /**
     * User can publish a post
     *
     * @return void
     */
    public function testUserCanPublishAPost()
    {
        $users      = $this->createUsersWithPosts(1, 1);
        $user       = $users[0];
        $post       = $user->posts()->first();

        $this->assertEquals('draft', $post->status);

        $service = new UserPostService($user);
        $service->publishPost($post->id);

        $this->assertEquals('published', $post->fresh()->status);
    }

    /**
     * User can set published post to draft
     *
     * @return void
     */
    public function testUserCanSetPublishedPostToDraft()
    {
        $users      = $this->createUsersWithPosts(1, 1);
        $user       = $users[0];
        $post       = $user->posts()->first();

        $service = new UserPostService($user);
        $service->publishPost($post->id);

        $this->assertEquals('published', $post->fresh()->status);

        $service->unpublishPost($post->id);

        $this->assertEquals('draft', $post->fresh()->status);
    }
}
