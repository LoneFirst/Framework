<?php
$this->config = [
    // database config
    'database' => [
        // database is based PDO
        'type' => 'mysql', // just support mysql now

        'mysql' => [
            'host' => '127.0.0.1',
            'port' => '3306',
            'dbname' => 'test', // database name
            'username' => 'root',
            'password' => '',
        ],


    ],

    // template engine switch
    'template' => true,

];
