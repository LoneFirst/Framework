<?php
define('ROOT_PATH',dirname(dirname(__FILE__)).'/');// call this from inside
define('APP_PATH', ROOT_PATH.'app/');
define('FILE_PATH','//'.substr($_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'],0,-9));// call this from outside

require __DIR__.'/../vendor/autoload.php';
require APP_PATH.'init.php';
