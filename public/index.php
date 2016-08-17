<?php
define('ROOT_PATH',dirname(dirname(__FILE__)).'/');// call this from inside
define('APP_PATH', ROOT_PATH.'app/');
define('FILE_PATH','//'.substr($_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'],0,-9));// call this from outside

require __DIR__.'/../vendor/autoload.php';

// 将变量全局化
$config = new core\config;

// 返回对应的设置内容
// @param string key path
// @return string result of the config key
// example : config('database:type') -> return 'mysql'
function config($keyPath)
{
    global $config;
    $result = $config->result($keyPath);
    return $result;
}
new core\route;

// 创建视图全局函数,可以在任何地方使用视图函数,安全性未知

// 展示指定视图
// @param string view name
// @param array incoming data
// @return object view
function view($viewName)
{
    $view = new core\view($viewName, $data = NULL);
    return $view;
    // 目前视图类功能有限,以下功能暂时不需要
    // $var = func_get_args();
    // if (count($var) == 0)
    // {
    //     $view = new core\view();
    //     return $view;
    // }
    // $eval = '$view = new core\view(';
    // foreach ($var as $key => $value) {
    //     $eval .= '$var['.$key.'],';
    // }
    // $eval = substr($eval, 0, -1);
    // $eval .= ');';
    // eval($eval);
    // return $view;
}
