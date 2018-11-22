<?php
return [
    /*
    |--------------------------------------------------------------------------
    | Database connections and table names
    |--------------------------------------------------------------------------
    */

    'connection' => env('SITE_CONNECTION', env('DB_CONNECTION', 'mysql')),
    'usersTable' => env('USERS_TABLE_NAME', 'users'),
];
