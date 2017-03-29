<?php
namespace core;

class route
{
    // 在请求周期开始实例一个route,与此同时根据路由表对路由进行解析
    protected $uri; // 请求的uri
    protected $handledURI; // 经过处理的uri
    protected $handledFormat; // 经过处理的路由格式
    protected $c; // 请求的节点个数
    protected $method; // 请求的方法
    protected $notfound = true; // 没有找到对应的路由规则
    protected $controllerList; // 控制器列表
    protected $locker = false; // 一旦路由规则匹配即立即锁死

    // 在这里进行流程的总览
    public function __construct()
    {
        $this->uri = request::URI();
        $this->handledURI = $this->handleURI($this->uri);
        $this->c = count($this->handledURI);
        $this->method = request::method(); // 使用request类对请求进行解析
        $this->controllerList = $this->listControllers();
        require ROOT_PATH.'app/routes.php'; // 引入路由表
        log::writeHttpLog($this->generateLog());
        if ($this->notfound) {
            throw new error('404 Not Found');
            exit();
        }
    }

    // 处理uri将后缀形式的参数进行处理
    private function handleURI($uri)
    {
        if (false !== strpos($uri, '?')) {
            $uri = substr($uri, 0, strpos($uri, '?'));
        }
        if (false !== strpos($uri, '#')) {
            $uri = substr($uri, 0, strpos($uri, '#'));
        }
        $handledURI = array_filter(explode('/', $uri));
        return $handledURI;
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
        $controller = new $controllerName();
        $functionList = get_class_methods($controllerName);
        if(!in_array($functionName, $functionList)) {
            return;
        }

        $this->notfound = false;

        // 采用更加安全的形式来调用函数
        // 上面已经实例化了控制器类 下面可以直接使用
        call_user_func_array(array($controller, $functionName), $var);
    }

    // 吃枣要重构,先放着
    public function reg($format, $function, $httpMethod = null)
    {
        if ($this->locker) {
            return;
        }
        // 判断当前请求方式是否正确
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
            } else {
                return;
            }
        }

        // 判断URL是否对应
        $handledFormat = array_filter(explode('/', $format));
        if (count($handledFormat) != $this->c) {
            return;
        }
        $var = array();
        foreach ($handledFormat as $key => $value)
        {
            if (substr($value, 0, 1) == ':') {
                @intval($this->handledURI[$key]); // 尝试将参数转换成整数类型
                array_push($var, $this->handledURI[$key]);
                continue;
            }
            if ($value != $this->handledURI[$key]) {
                return;
            }
        }

        // 执行匿名函数或者寻找对应的控制器方法
        if (is_callable($function)) {
            call_user_func_array($function, $var);
            $this->notfound = false;
        } else {
            $this::loadController($var, $function);
        }

        // 锁死路由
        $this->locker = true;
        return;
    }

    // 返回包含所有控制器的数组
    private function listControllers()
    {
        $fileList = scandir(ROOT_PATH.'app/controllers');
        foreach ($fileList as $key => $value) {
            $fileList[$key] = substr($value, 0, -4);
        }
        return $fileList;
    }

    // 生成路由日志
    private function generateLog()
    {
        $log = '';
        $log .= date('c', $_SERVER['REQUEST_TIME']).' ';
        $log .= $_SERVER['REMOTE_ADDR'].':'.$_SERVER['REMOTE_PORT'].' ';
        $log .= $this->method.' '.$this->uri.' ';
        $log .= $_SERVER['HTTP_USER_AGENT'];
        return $log;
    }
}
