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
    private function loadController($var, $function)
    {
        $tmp = explode('@', $function);
        $controllerName = array_shift($tmp).'Controller';
        $functionName = array_shift($tmp);
        if(!in_array($controllerName, $this->controllerList)) {
            return;
        }

        $controllerName = 'controllers\\'.$controllerName;

        // 这里要先通过实例化引入类才可也在后面获取方法成员
        // 这里也让控制器的构造函数有了意义 一举双鸟
        eval('new '.$controllerName.';');
        $functionList = get_class_methods($controllerName);
        if(!in_array($functionName, $functionList)) {
            return;
        }

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
    public function reg(string $format, $function, $httpMethod = null)
    {
        if ($httpMethod != null) {
            if (is_string($httpMethod)) {
                if ($this->method != strtoupper($httpMethod)) {
                    return;
                }
            }elseif (is_array($httpMethod)) {
                foreach ($httpMethod as $key => $value) {
                    $httpMethod[$key] = strtoupper($value);
                }
                if (!in_array($this->method, $httpMethod)) {
                    return;
                }
            }else{
                return;
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
