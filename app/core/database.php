<?php
namespace core;

use PDO;

class database
{
    public function get()
    {
        if (config('database:type') == 'mysql')
        {
            $host = config('database:mysql:host');
            $port = config('database:mysql:port');
            $user = config('database:mysql:username');
            $pass = config('database:mysql:password');
            $dbname = config('database:mysql:dbname');

            $dsn = "mysql:host={$host};port={$port};dbname={$dbname};";
            $db = new PDO($dsn, $user, $pass);
            return $db;
        }

        // if (config('database:type') == 'sqlite')
        // {
        //     $dsn = config('database:sqlite:url');
        //     $this->db = new PDO($dsn);
        // }
    }
}
