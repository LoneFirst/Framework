<?php
namespace core;

use PDO;

class database extends \PDO
{
    public static $db;

    public static function get()
    {
        if (isset(self::$db)) {
            return self::$db;
        }
        if (config('database:type') == 'mysql')
        {
            $host = config('database:mysql:host');
            $port = config('database:mysql:port');
            $user = config('database:mysql:username');
            $pass = config('database:mysql:password');
            $dbname = config('database:mysql:dbname');

            $dsn = "mysql:host={$host};port={$port};dbname={$dbname};";
            self::$db = new self($dsn, $user, $pass);
        }

        if (config('database:type') == 'sqlite')
        {
            $dsn = config('database:sqlite:dsn');
            self::$db = new self($dsn);
        }

        return self::$db;
    }

    public function query()
    {
        $param = func_get_args();
        $log = '';
        $log .= date('c', time()).' ';
        $log .= $param[0];
        log::writeHttpLog($log);
        return call_user_func_array('parent::query', $param);
    }
}
