<?php
namespace core;

class view
{
    private $viewPath; // 寻求的视图所在的路径
    private $viewTempPath; // 寻求的视图缓存所在路径
    private $phpCode = array(); // 被保护的php代码
    private $data = array(); // 传入路由的数据

    public function __construct($viewName, $data = NULL)
    {
        $this->viewPath = ROOT_PATH.'resources/views/'.$viewName.'.tpl';
        $this->viewTempPath = ROOT_PATH.'cache/'.md5($viewName).'.php';
        if ($data != NULL) {
            $this->data = $data;
        }
    }

    public function exists()
    {
        if (func_get_args() == 0)
        {
            return file_exists($this->viewName);
        }
        $file = ROOT_PATH.'resources/views/'.$viewName.'.php';
        return file_exists($file);
    }

    public function push($key, $value)
    {
        $this->data[$key] = $value;
        return $this;
    }

    private function handleView()
    {
        $h = @fopen($this->viewPath, 'rb');
        if (!$h) {
            return;
        }
        $source = '';
        while (!feof($h)) {
            $source .= fread($h, 8192);
        }
        $source = $this->protectCode($source);

        // varibles e.g. {{ $n }}
        $source = preg_replace('/\{\{\s(.*)\s\}\}/', '<?php echo \\1;?>', $source);
        $source = $this->protectCode($source);
        // replace...

        $source = $this->popCode($source);
        $output = '<?php'.PHP_EOL;
        $output .= 'if(!defined(\'ROOT_PATH\'))exit();'.PHP_EOL;
        $output .= '?>'.PHP_EOL;
        $output .= trim($source);
        $output = preg_replace('/\s*\?\>\s*\<\?php\s*/is', PHP_EOL, $output);
        if (!file_exists($this->viewTempPath)) {
            @touch($this->viewTempPath);
        }
        if (!is_writable($this->viewTempPath)) {
            throw new exception('缓存文件不可写');
        }
        file_put_contents($this->viewTempPath, $output);
    }

    public function render()
    {
        // 如果设置中模板引擎没有开启,则直接输出视图模板文件
        foreach ($this->data as $key => $value) {
            $$key = $value;
        }
        if (!config('template')) {
            include $this->viewPath;
            return;
        }

        if (!file_exists($this->viewPath) && !file_exists($this->viewTempPath)) {
            throw new exception('不存在对应的视图文件');
        }elseif (!file_exists($this->viewTempPath)) {
            $this->handleView();
        }elseif (filemtime($this->viewTempPath) <= filemtime($this->viewPath)) {
            $this->handleView();
        }
        include $this->viewTempPath;
    }

    // 将php代码替换避免受到影响
    private function protectCode($source)
    {
        $source = preg_replace_callback('/<\?php.+?\?>/is', array($this, 'saveCode'), $source);
        return $source;
    }

    // 替换过程中使用的回调函数
    private function saveCode($matches)
    {
        $tmp = '__PROTECTED_PHP_CODE_NO_'.count($this->phpCode).'__';
        array_push($this->phpCode, $matches[0]);
        return $tmp;
    }

    // 释放php代码
    private function popCode($source)
    {
        foreach ($this->phpCode as $key => $value) {
            $tmp = '__PROTECTED_PHP_CODE_NO_'.$key.'__';
            $source = str_replace($tmp, $value, $source);
        }
        $this->phpCode = array();
        return $source;
    }
}
