<?php
namespace App\Traits;

trait DBMethods
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table;

    /**
     * The connection name for the model.
     *
     * @var string
     */
    protected $connection;

    /**
     * Get the database connection.
     */
    public function getConnectionName()
    {
        return $this->connection;
    }

    /**
     * Get the database connection.
     */
    public function getTableName()
    {
        return $this->table;
    }
}
