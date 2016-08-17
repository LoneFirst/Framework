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

    // 在这里进行流程的总览
    public function __construct()
    {
        $this->uri = request::URI();
        $this->handledURI = explode('/',$this->uri);
        $this->c = count($this->handledURI);
        $this->method = request::method(); // 使用request类对请求进行解析
        require ROOT_PATH.'app/routes.php'; // 引入路由表
    }

    // 内部加载控制器所使用的函数
    // 强行做成类laravel的样式,可能会有安全性漏洞
    private static function loadController($var, $function)
    {
        $eval = str_replace('@', '::', $function);
        $eval = 'controller\\'.$eval;
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

    public function get($format, $function)
    {
        if ($this->method != 'GET')
        {
            return;
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
        $this::loadController($var, $function);
    }

    public function post($format, $function)
    {
        if ($this->method != 'POST')
        {
            return;
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
        $this::loadController($var, $function);
    }

    public function put($format, $function)
    {
        if ($this->method != 'put')
        {
            return;
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
        $this::loadController($var, $function);
    }

    public function delete($format, $function)
    {
        if ($this->method != 'DELETE')
        {
            return;
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
        $this::loadController($var, $function);
    }

    public function HEAD($format, $function)
    {
        if ($this->method != 'HEAD')
        {
            return;
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
        $this::loadController($var, $function);
    }

    public function any($format, $function)
    {
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
        $this::loadController($var, $function);
    }
}
