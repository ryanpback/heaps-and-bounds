<?php

namespace App\Models;

use App\Traits\DBMethods;
use Illuminate\Database\Eloquent\Model;

class Cheer extends Model
{
    use DBMethods;

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
    public function scopeIsCheeredByUser($query, $cheerableId, $userId)
    {
        return $query->where('cheerable_id', $cheerableId)
                      ->where('user_id', $userId);
    }
}
