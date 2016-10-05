<?php
namespace core;

class route
{
    // 在请求周期开始实例一个route,与此同时根据路由表对路由进行解析
    protected $uri; // 请求的uri
    protected $handledURI; // 经过处理的uri
    protected $handledFormat; // 经过处理的路由格式
    protected $c; // 节点个数
    protected $method; // 请求的方法
    protected $notfound = true; // 没有找到对应的路由规则
    protected $controllerList; // 控制器列表

    // 在这里进行流程的总览
    public function __construct()
    {
        $this->uri = request::URI();
        $this->handledURI = $this->handleURI($this->uri);
        $this->c = count($this->handledURI);
        $this->method = request::method(); // 使用request类对请求进行解析
        $this->controllerList = $this->listControllers();
        require ROOT_PATH.'app/routes.php'; // 引入路由表
        if ($this->notfound) {
            header('HTTP/1.1 404 Not Found');
            header("status: 404 Not Found");
        }
    }

    // 处理uri将后缀形式的参数进行处理
    private function handleURI($uri)
    {
        if(strpos($uri, '?')) {
            $uri = substr($uri, 0, strpos($uri, '?'));
        }
        $uri = explode('/', $uri);
        return $uri;
    }

    // 内部加载控制器所使用的函数
    // 强行做成类laravel的样式,可能会有安全性漏洞
    private function loadController($var, $function)
    {
        $tmp = explode('@', $function);
        $controllerName = array_shift($tmp).'Controller';
        $functionName = array_shift($tmp);
        if(!in_array($controllerName, $this->controllerList)) {
            return;
        }

        // 这里应该判断类里存不存在指定函数,暂时不知道怎么实现

        $controllerName = 'controllers\\'.$controllerName;
        $this->notfound = false;

        $eval = $controllerName.'::'.$functionName;
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
        $this::loadController($var, $function);
    }

    private function listControllers()
    {
        $fileList = scandir(ROOT_PATH.'app/controllers');
        foreach ($fileList as $key => $value) {
            $fileList[$key] = substr($value, 0, -4);
        }
        return $fileList;
    }
}
