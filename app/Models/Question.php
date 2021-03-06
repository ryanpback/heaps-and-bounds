<?php

namespace App\Models;

use App\Traits\DBMethods;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Question extends Model
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
        $this->table        = config('tables.questionsTable');
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'question_content',
        'title',
    ];

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'user_id',
    ];

    /**
     * Typecasting FTW.
     *
     * @var array
     */
    protected $casts = [
        'id'                => 'integer',
        'question_content'  => 'string',
        'title'             => 'string',
        'user_id'           => 'integer',
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
    | Question Relationships
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
    | Question Methods
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | Question Scopes
    |--------------------------------------------------------------------------
     */
    public static function scopeGetAllUsersQuestions($query, $userId, $withTrashed = false)
    {
        if ($withTrashed) {
            return $query->withTrashed()
                            ->with(['cheers' => function ($q) {
                                $q->withTrashed();
                            }])
                            ->where('user_id', $userId);
        }

        return $query->with('cheers')->where('user_id', $userId);
    }
}
