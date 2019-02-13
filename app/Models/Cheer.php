<?php

namespace App\Models;

use App\Traits\DBMethods;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cheer extends Model
{
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
        $this->connection   = config('tables.connection');
        $this->table        = config('tables.cheersTable');
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'cheerable_id',
        'cheerable_type',
        'id',
    ];

    /**
     * Typecasting FTW.
     *
     * @var array
     */
    protected $casts = [
        'cheerable_id'      => 'string',
        'cheerable_type'    => 'string',
        'id'                => 'integer',
        'user_id'           => 'integer',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    /*
    |--------------------------------------------------------------------------
    | Cheer Relationships
    |--------------------------------------------------------------------------
    */
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function cheerable()
    {
        return $this->morphTo();
    }

    /*
    |--------------------------------------------------------------------------
    | Cheer Methods
    |--------------------------------------------------------------------------
    */
    public function scopeIsCheeredByUser($query, $cheerableId, $cheerableType, $userId)
    {
        return $query->where('cheerable_id', $cheerableId)
                     ->where('cheerable_type', $cheerableType)
                     ->where('user_id', $userId);
    }
}
