<?php
namespace core;

class route
{
    // 在请求周期开始实例一个route,与此同时根据路由表对路由进行解析
    protected $uri;
    protected $handledURI;
    protected $handledFormat;
    protected $c;
    protected $method;
    protected $notfound = true;

    // 在这里进行流程的总览
    public function __construct()
    {
        $this->uri = request::URI();
        $this->handledURI = explode('/',$this->uri);
        $this->c = count($this->handledURI);
        $this->method = request::method(); // 使用request类对请求进行解析
        require ROOT_PATH.'app/routes.php'; // 引入路由表
        if ($this->notfound) {
            header('HTTP/1.1 404 Not Found');
            header("status: 404 Not Found");
        }
    }

    // 内部加载控制器所使用的函数
    // 强行做成类laravel的样式,可能会有安全性漏洞
    private static function loadController($var, $function)
    {
        $tmp = explode('@', $function);
        $eval = array_shift($tmp).'Controller::'.array_shift($tmp);
        $eval = 'controllers\\'.$eval;
        $eval .= '(';
        foreach ($var as $key => $value) {
            $eval .= '$var['.$key.'],';
        }
        if (count($var) != 0)
        {
            $eval = substr($eval, 0, -1);
        }
        $eval .= ');';
        eval($eval);
    }

    // 吃枣要重构,先放着
    public function reg(string $format, $function, string $httpMethod = null)
    {
        if ($httpMethod != null) {
            if (is_string($httpMethod)) {
                if ($this->method != strtoupper($httpMethod)) {
                    return;
                }
            }
        }
        $this->handledFormat = explode('/', $format);
        if (count($this->handledFormat) != $this->c)
        {
            return;
        }
        $var = array();
        foreach ($this->handledFormat as $key => $value)
        {
            if (substr($value, 0, 1) == ':')
            {
                @intval($this->handledURI[$key]);
                array_push($var, $this->handledURI[$key]);
                continue;
            }
            if ($value != $this->handledURI[$key])
            {
                return;
            }
        }
        if (is_callable($function))
        {
            call_user_func($function);
            return;
        }
        $this->notfound = false;
        $this::loadController($var, $function);
    }
}
