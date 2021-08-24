<?php
return [
    'account' => [
        'hash_algorithm' => 'default', // default, md5
        'storage' => 'mysql', // mysql, sleekdb
        'sleekdb' => [
            'data_dir' => 'var' . DIRECTORY_SEPARATOR .'storage',
            'cache_lifetime' => 900,
        ],
        'mysql' => [
            'dsn' => 'mysql:host=localhost;dbname=mydatabase;charset=utf8',
            'username' => 'authserver',
            'password' => 'password',
            'table' => 'users',
            'columns' => [
                'id' => 'user_id',
                'username' => 'username',
                'email' => 'email',
                'password_hash' => 'password_hash',
            ]
        ]
    ]
];
