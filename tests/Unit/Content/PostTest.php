<?php

namespace Tests\Unit\Content;

use App\Models\User;
use App\Services\PostService;
use App\Traits\FactoryTraits;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class PostTest extends TestCase
{
    use RefreshDatabase;
    use WithoutMiddleware;
    use FactoryTraits;

    /**
     * Test new post validation - without content
     *
     * @expectedException Illuminate\Database\QueryException
     * @return void
     */
    public function testCantCreatePostWithoutPostContent()
    {
        $user = factory(User::class, 'new')->create();

        $postData = [
            'title' => 'I have a title but no post content',
        ];

        $service = new PostService($user);
        $service->createPost($postData);

        $this->setExpectedException('Illuminate\Database\QueryException');
    }

    /**
     * Test new post validation - without post_title
     *
     * @expectedException Illuminate\Database\QueryException
     * @return void
     */
    public function testCantCreatePostWithoutPostTitle()
    {
        $user = factory(User::class, 'new')->create();

        $postData = [
            'post_content' => 'I have post content but no title',
        ];

        $service = new PostService($user);
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
            'post_content'  => 'I have post content',
            'title'         => 'I have a title',
        ];

        $service = new PostService($user);
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
        $originalPostContent    = $post->post_content;

        $data = [
            'post_content'  => 'New content',
            'post_id'       => $post->id,
            'title'         => 'New title',
        ];

        $service        = new PostService($user);
        $updatedPost    = $service->updatePost($data);

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
        $users      = $this->createUsersWithPosts(1, 2);
        $user       = $users[0];
        $post       = $user->posts()->first();

        $service    = new PostService($user);
        $service->trashPost($post->id);

        $this->assertEquals(1, $user->getMyTrashedPosts()->count());
    }

    /**
     * User can successfully restore a post
     *
     * @return void
     */
    public function testUserCanSuccessfullyRestoreAPost()
    {
        // createUsersWithPosts is a trait method
        $users  = $this->createUsersWithPosts(1, 2);
        $user   = $users[0];
        $post   = $user->posts()->first();

        $this->assertEquals(2, $user->posts()->count());

        $service = new PostService($user);
        $service->trashPost($post->id);

        $deletedPosts = $user->getMyTrashedPosts();
        $this->assertEquals(1, $deletedPosts->count());

        $service->restorePost($deletedPosts[0]->id);

        $this->assertEquals(0, $user->getMyTrashedPosts()->count());
        $this->assertEquals(2, $user->posts()->count());
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

        $this->assertEquals(2, $user->posts()->count());

        $service = new PostService($user);
        $service->deletePost($post->id);

        $deletedPosts = $user->getMyTrashedPosts();
        $this->assertEquals(0, $deletedPosts->count());

        $this->assertEquals(1, $user->posts()->count());
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

        $service    = new PostService($user);
        $pinnedPost = $service->pinPost($post->id);

        $this->assertTrue($pinnedPost->pinned);
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

        $service    = new PostService($user);
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

        $service        = new PostService($user);
        $pinnedPost1    = $service->pinPost($posts[0]->id);
        $pinnedPost2    = $service->pinPost($posts[1]->id);

        $this->assertFalse($pinnedPost1->fresh()->pinned);
        $this->assertTrue($pinnedPost2->pinned);
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

        $service        = new PostService($user);
        $publishedPost  = $service->publishPost($post->id);

        $this->assertEquals('published', $publishedPost->status);
    }

    /**
     * User can set published post to draft
     *
     * @return void
     */
    public function testUserCanSetPublishedPostToDraft()
    {
        $users          = $this->createUsersWithPosts(1, 1);
        $user           = $users[0];
        $post           = $user->posts()->first();

        $service        = new PostService($user);
        $publishedPost  = $service->publishPost($post->id);

        $this->assertEquals('published', $publishedPost->status);

        $unpublishedPost = $service->unpublishPost($post->id);

        $this->assertEquals('draft', $unpublishedPost->status);
    }
}
