<?php
namespace core;

class request
{
    public function URI()
    {
        return substr($_SERVER['REQUEST_URI'],strpos($_SERVER['PHP_SELF'],'index.php'));
    }
    public function method()
    {
        return $_SERVER['REQUEST_METHOD'];
    }
}
