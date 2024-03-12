<?php

namespace App\Libraries;

class AppLog
{
    private static $logFilePath = '';

    public static function initLog()
    {
        helper('filesystem');
        $logFileName = 'log-' . date('Y-m-d') . '.php';
        self::$logFilePath = WRITEPATH . 'applogs/' . $logFileName;
    }

    public static function writeLog(string $code, string $data)
    {
        $datetime = date('H:i:s');
        $codeString = ucwords(str_replace('_', ' ', $code));
        $content = "[{$codeString} Log at {$datetime}]: " . $data . PHP_EOL;
        if (!write_file(self::$logFilePath, $content, 'a+')) {
            // echo 'Unable to write file!';
        } else {
            // echo 'File written';
        }
    }
}
