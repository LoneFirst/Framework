<?php
require __DIR__.'/../vendor/autoload.php';

date_default_timezone_set('UTC');
use core\config;
use core\route;
use PHPUnit\Framework\TestCase;
function config($keyPath)
{
    $config = new config;
    $result = $config->result($keyPath);
    return $result;
}
define('ROOT_PATH', __DIR__.'/../');
class coreTest extends TestCase
{
    function testConfig() {
        $t = new config;
        $this->assertEquals(true, $t->result('debug'));
    }
}
