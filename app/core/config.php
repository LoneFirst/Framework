<?php
namespace core;

class config
{
    public $config;
    public function __construct()
    {
        require ROOT_PATH.'app/configs.php';
    }
    public function result($keyPath)
    {
        $var = explode(':', $keyPath);
        $tmp[-1] = $this->config;
        foreach ($var as $key => $value) {
            $tmp[$key] = $tmp[$key - 1][$value];
        }
        return $tmp[count($var) - 1];
    }
}
