<?php

namespace App\Models;

use App\Traits\DBMethods;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use DBMethods;
    use SoftDeletes;

    /**
     * Create a new instance to set the table and connection.
     *
     * @return void
     *
     */
    public function __construct($attributes = [])
    {
        parent::__construct($attributes);
        $this->connection   = config('tables.connection');
        $this->table        = config('tables.postsTable');
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'post_content',
        'title',
    ];

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'id',
        'user_id',
    ];

    /**
     * Typecasting FTW.
     *
     * @var array
     */
    protected $casts = [
        'post_content'  => 'string',
        'id'            => 'integer',
        'pinned'        => 'boolean',
        'status'        => 'string',
        'title'         => 'string',
        'user_id'       => 'integer',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

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
    | Post Relationships
    |--------------------------------------------------------------------------
    */
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function cheers()
    {
        return $this->morphMany('App\Models\Cheer', 'cheerable');
    }

    /*
    |--------------------------------------------------------------------------
    | Post Methods
    |--------------------------------------------------------------------------
    */
}
