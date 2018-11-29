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
        $this->connection = config('tables.connection');
        $this->table = config('tables.questionsTable');
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'question_body',
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
        'question_body'     => 'string',
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
}
