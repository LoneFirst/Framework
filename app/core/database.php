<?php
namespace core;

use PDO;

class database
{
    private $db;

    public function __construct()
    {
        if (config('database:type') == 'mysql')
        {
            $host = config('database:mysql:host');
            $port = config('database:mysql:port');
            $user = config('database:mysql:username');
            $pass = config('database:mysql:password');
            $dbname = config('database:mysql:dbname');

            $dsn = "mysql:host={$host};port={$port};dbname={$dbname};";
            $this->db = new PDO($dsn, $user, $pass);
        }

        // if (config('database:type') == 'sqlite')
        // {
        //     $dsn = config('database:sqlite:url');
        //     $this->db = new PDO($dsn);
        // }
    }
    public function get()
    {
        if (is_null($this->db))
        {
            $this->__construct();
        }
        return $this->db;
    }
}
