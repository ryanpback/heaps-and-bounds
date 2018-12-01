<?php

namespace App\Models;

use App\Traits\DBMethods;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use Notifiable;
    use DBMethods;
    use SoftDeletes;

    /**
     * Create a new instance to set the table and connection.
     *
     * @return void
     */
    public function __construct($attributes = [])
    {
        parent::__construct($attributes);
        $this->connection = config('tables.connection');
        $this->table = config('tables.usersTable');
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email',
        'first_name',
        'last_name',
        'password',
        'username',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'deleted_at',
        'updated_at'
    ];


    /*
    |--------------------------------------------------------------------------
    | User Relationships
    |--------------------------------------------------------------------------
    */
    public function posts()
    {
        return $this->hasMany(\App\Models\Post::class);
    }

    /*
    |--------------------------------------------------------------------------
    | User Methods
    |--------------------------------------------------------------------------
    */
    /**
     * Check if user has post
     *
     * @param int $postId
     * @param bool $findWithTrashed //default false
     * @return Post/null
     */
    public function getPost($postId, $findWithTrashed = false)
    {
        $post = $this->posts();

        if ($findWithTrashed) {
            $post = $post->withTrashed();
        }

        $post = $post->find($postId);

        return $post;
    }

    /**
     * User will make the post pinned
     *
     * @return Post $p
     */
    public function pin(Post $p)
    {
        if ($p->pinned !== 1) {
            $p->pinned = 1;
            $p->save();
        }

        return $p;
    }

    /**
     * User will make the post unpinned
     *
     * @return Post $p
     */
    public function unpin(Post $p)
    {
        if ($p->pinned !== 0) {
            $p->pinned = 0;
            $p->save();
        }

        return $p;
    }

    /**
     * Get the user's deleted posts
     *
     * @return Collection $deletedPosts
     */
    public function getMyTrashedPosts()
    {
        return $this->posts()->onlyTrashed()->get();
    }

    /*
    |--------------------------------------------------------------------------
    | User Scopes
    |--------------------------------------------------------------------------
    */

    /**
     * Return the user and their posts
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithPosts($query)
    {
        return $query->with('posts');
    }
}
