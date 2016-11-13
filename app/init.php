<?php
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

function hi()
{
    echo 'hi';
}

// 注册异常处理函数
core\error::registerHandler();

// 设置时区
date_default_timezone_set(config('timezone'));

// 开启session
session_start();

// 创建视图全局函数,可以在任何地方使用视图函数,安全性未知

// 展示指定视图
// @param string view name
// @param array incoming data
// @return object view
function view($viewName,array $data = NULL)
{
    $view = new core\view($viewName, $data);
    return $view;
}


// 重定向
function redirect(string $url) {
    header('Location: '.$url);
    exit();
}

// 服务器响应函数
function response() {
    return new core\response;
}

// 检索路由
new core\route;
// 整个请求的生命周期由此开始
