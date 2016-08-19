<?php
namespace core;

class view
{
    protected $viewPath;

    public function __construct($viewName, $data = NULL)
    {
        $this->viewPath = ROOT_PATH.'resources/views/'.$viewName.'.php';
        if ($data != NULL) {
            foreach ($data as $key => $value) {
                $eval = '$this->'.$key.'=\''.$value.'\';';
                eval($eval);
            }
        }

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
}
