<?php
namespace core;

class request
{
    private static $URI;
    private static $method;

    public static function URI()
    {
        if (isset(self::$URI)) {
            return self::$URI;
        } else {
            return substr($_SERVER['REQUEST_URI'],strpos($_SERVER['PHP_SELF'],'index.php'));
        }
    }

    public static function method()
    {
        if (isset(self::$method)) {
            return self::$method;
        } else {
            return $_SERVER['REQUEST_METHOD'];
        }
    }

    public function upload($name, $savePath, $restriction, callback $savename, callback $verify)
    {
        $file = $_FILES[$name];
        $fileCorrect = ((is_array($restriction)?in_array($file['type'], $restriction['type']):($file['type'] == $restriction['type'])) &&
                        ($file['size'] <= $restriction['sizelimit']));
        if(!$fileCorrect) {
            return;
        }
        if ($file['error'] > 0) {
            throw new error($file['error'], 1);
        } elseif (call_user_func_array($verify, $file)) {
            $name = call_user_func_array($savename, $file);
            move_uploaded_file($file['tmp_name'], $savePath.$name);
        }
    }
}
