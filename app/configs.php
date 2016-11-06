<?php
$this->config = [
    // you can add any custom config Here
    'sitename' => 'test',

    // show the debug message or not
    'debug' => true,

    // database config
    'database' => [
        // database is based PDO
        'type' => 'mysql', // change database type here

        'mysql' => [
            'host' => '127.0.0.1',
            'port' => '3306',
            'dbname' => 'test', // database name
            'username' => 'root',
            'password' => '',
        ],

        'sqlite' => [
            'dsn' => 'sqlite::memory:',
        ],

    ],


    // set date default timezone
    'timezone' => 'Asia/Shanghai',

    // template engine switch

    /*
     * turn on the template engine will reduce the diffculty of develop
     * turn off the template engine will save the CPU time and RAM
     */
    'template' => [
        0 => true, // this is the switcher of the template engine
        'cache' => false, // cache the handled view temp or not
    ],

    'log' => [
        0 => true,
        'path' => ROOT_PATH.'log/',
    ]

];
