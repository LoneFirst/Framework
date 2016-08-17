<?php
namespace core;

class view
{
    protected $viewPath;
    protected $html;

    public function __construct($viewName, $data = NULL)
    {
        $this->viewPath = ROOT_PATH.'resources/views/'.$viewName.'.php';
        // $this->html = fopen($this->view, 'rb');
        // if (config['template']) {
        //     $this::handleView();
        // }
        include $this->viewPath;
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

    private static function handleView()
    {

    }
}
