<?php
return [
    /*
    |--------------------------------------------------------------------------
    | Database connections and table names
    |--------------------------------------------------------------------------
    */
    // CONNECTIONS
    'connection'        => env('SITE_CONNECTION', env('DB_CONNECTION', 'mysql')),

    // TABLE NAMES
    'postsTable'        => env('POSTS_TABLE_NAME'),
    'questionsTable'    => env('QUESTIONS_TABLE_NAME'),
    'usersTable'        => env('USERS_TABLE_NAME'),
];
