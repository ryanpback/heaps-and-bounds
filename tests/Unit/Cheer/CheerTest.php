<?php

namespace Tests\Unit\Cheer;

use App\Models\User;
use App\Models\Post;
use App\Models\Question;
use App\Services\CheerService;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class CheerTest extends TestCase
{
    use RefreshDatabase;
    use WithoutMiddleware;

    /**
     * User can cheer a post, and not a question
     */
    public function testUserCanCheerAPost()
    {
        $user       = factory(User::class, 'new')->create();
        $post       = factory(Post::class)->create(['user_id' => $user->id]);
        $question   = factory(Question::class)->create(['user_id' => $user->id]);

        $service    = new CheerService($post->id, 'post');
        $service->cheer($user->id);

        $this->assertEquals(1, $post->cheers()->count());
        $this->assertEquals(0, $question->cheers()->count());
        $this->assertEquals(1, $user->cheers()->count());
    }

    /**
     * User can uncheer a post
     */
    public function testUserCanUnCheerAPost()
    {
        $user       = factory(User::class, 'new')->create();
        $post       = factory(Post::class)->create(['user_id' => $user->id]);

        $service    = new CheerService($post->id, 'post');
        $service->cheer($user->id);

        $this->assertEquals(1, $post->cheers()->count());
        $this->assertEquals(1, $user->cheers()->count());

        $service->cheer($user->id);

        $this->assertEquals(0, $post->cheers()->count());
        $this->assertEquals(0, $user->cheers()->count());
    }

    /**
     * User can cheer a question, and not a post
     *
     * @return void
     */
    public function testUserCanCheerAQuestion()
    {
        $user       = factory(User::class, 'new')->create();
        $question   = factory(Question::class)->create(['user_id' => $user->id]);
        $post       = factory(Post::class)->create(['user_id' => $user->id]);

        $service    = new CheerService($question->id, 'question');
        $service->cheer($user->id);

        $this->assertEquals(1, $question->cheers()->count());
        $this->assertEquals(0, $post->cheers()->count());
        $this->assertEquals(1, $user->cheers()->count());
    }

    /**
     * User can uncheer a question
     */
    public function testUserCanUnCheerAQuestion()
    {
        $user       = factory(User::class, 'new')->create();
        $question   = factory(Question::class)->create(['user_id' => $user->id]);

        $service    = new CheerService($question->id, 'question');
        $service->cheer($user->id);

        $this->assertEquals(1, $question->cheers()->count());
        $this->assertEquals(1, $user->cheers()->count());

        $service->cheer($user->id);

        $this->assertEquals(0, $question->cheers()->count());
        $this->assertEquals(0, $user->cheers()->count());
    }

    /**
     * Tests that the get_class call in App\Services\CheerService -> Cheer() ->isCheeredByUser() works
     * to get the cheer where $cheerableId passed in and the $cheerable_type match. Post primary key
     * and question primary key could (would) have a match and it would delete the first.
     *
     * @return void
     */
    public function testUncheerTheCorrectCheerable()
    {
        $user               = factory(User::class, 'new')->create();
        $question           = factory(Question::class)->create(['user_id' => $user->id]);
        $post               = factory(Post::class)->create(['user_id' => $user->id]);

        $serviceQuestion    = new CheerService($question->id, 'question');
        $serviceQuestion->cheer($user->id);

        $servicePost = new CheerService($post->id, 'post');
        $servicePost->cheer($user->id);

        $this->assertEquals(1, $question->cheers()->count());
        $this->assertEquals(1, $post->cheers()->count());
        $this->assertEquals(2, $user->cheers()->count());

        $servicePost->cheer($user->id);

        $this->assertEquals(1, $question->cheers()->count());
        $this->assertEquals(0, $post->cheers()->count());
        $this->assertEquals(1, $user->cheers()->count());
    }
}
