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

    // 上传文件
    // @param string $name the input name of the uploaded file
    // @param string $savePath where the file will be move
    // @param array $restriction the restriction rule
    // @param callback $savename a function need return the file name
    // @param callback $verify a function need return a boolern
    // @return boolern
    public static function upload($name, $savePath, array $restriction, callback $savename = null, callback $verify = null)
    {
        $file = $_FILES[$name];
        $typeResult = array_key_exists('type', $restriction)?in_array($file['type'], $restriction['type']):true;
        $sizeResult = array_key_exists('sizelimit', $restriction)?($file['size'] <= $restriction['sizelimit']):true;
        $fileCorrect = ($typeResult && $sizeResult);
        if(!$fileCorrect) {
            return $this;
        }
        if ($file['error'] > 0) {
            throw new error($file['error'], 1);
        } else {
            if (is_null($savename)) {
                $name = $file['name'];
            } else {
                $name = call_user_func($savename, $file);
            }

            if (is_null($verify)) {
                $v = !file_exists($savePath.$name);
            } else {
                $v = call_user_func($verify, $file);
            }

            if ($v) {
                return move_uploaded_file($file['tmp_name'], $savePath.$name);
            }
        }
        return false;
    }

    public static function input()
    {
        return array_merge($_GET, $_POST);
    }
}
