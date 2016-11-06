<?php
namespace core;

class Error extends \Exception
{
    private $trace;

    public function __construct($message = '服务器脚本错误', $code = 0, $previous = null, $trace = array())
    {
        parent::__construct($message, $code, $previous);
        if (!$trace) {
            $this->trace = debug_backtrace();
        }else{
            $this->trace = $trace;
        }
    }

    public static function registerHandler()
    {
        set_exception_handler(array(__CLASS__, 'handleUncaughtException'));
        set_error_handler(array(__CLASS__, 'handleError'));
    }

    public static function handleError($errNo, $errStr, $errFile, $errLine)
    {
        if ($errNo == E_STRICT) {
            return;
        }
        if ($errNo == E_NOTICE) {
            return;
        }
        $trace = debug_backtrace();
        array_unshift($trace, array('file' => $errFile, 'line' => $errLine));
        $exception = new self($errStr, $errNo, null, $trace);
        self::handleUncaughtException($exception);
    }

    public static function handleUncaughtException($instance)
    {
        @ob_end_clean();
        if (!($instance instanceof Error)) {
            $instance = new self($instance->getMessage(), intval($instance->getCode()), $instance, $instance->getTrace());
        }
        //echo $instance->getMessage();
        view('status')->push('error', $instance)->render();
        exit();
    }

    public function formatTrace()
    {
        $backtrace = $this->trace;
        krsort($backtrace);
        echo '<ol>';
        foreach ($backtrace as $key => $value) {
            if (!isset($value['file'])) {
                continue;
            }
            if (!isset($value['function'])) {
                continue;
            }
            echo '<li><strong>'.$value['function'].'</strong> in <strong>'.$value['file'].'</strong> line <strong>'.$value['line'].'</strong></li>';
        }
        echo '</ol>';
        // krsort($backtrace);
        // $trace = '';
        // foreach ($backtrace as $error) {
        //     $error['line'] = $error['line'] ? ":{{ \$error['line'] }}" : '';
        //     $log = '';
        //     if ($error['file']) {
        //         $log = str_replace(str_replace('\\', '/', ROOT_PATH), '', $error['file']) . $error['line'];
        //     }
        //     if ($error['class']) {
        //         $error['class'] = str_replace('\\', '/', $error['class']);
        //         $log .= " ({$error['class']}{$error['type']}{$error['function']})";
        //     }
        //     $trace .= "{$log}\r\n";
        // }
        // return $trace;
    }
}
