<?php

namespace App\Libraries;

class AppJobManager
{
    const OS_WINDOWS = 1;
    const OS_NIX = 2;
    const OS_OTHER = 3;

    protected static $pid;
    protected static $serverOS;
    protected static $command;

    public static function getPid()
    {
        return self::$pid;
    }

    public static function getOS()
    {
        self::$serverOS = strtoupper(PHP_OS);

        if (substr(self::$serverOS, 0, 3) === 'WIN') {
            return self::OS_WINDOWS;
        } elseif (
            self::$serverOS === 'LINUX' ||
            self::$serverOS === 'FREEBSD' ||
            self::$serverOS === 'DARWIN'
        ) {
            return self::OS_NIX;
        }

        return self::OS_OTHER;
    }

    public static function checkSupportedOS()
    {
        if (self::getOS() !== self::OS_NIX) {
            throw new Exception(
                "Background process library is not supported for {self::$serverOS}"
            );
        }

        return true;
    }

    public static function formPath($route, $params = [])
    {
        helper('jobs');
        $jobPath = formJobPath($route, $params);
        return $jobPath;
    }

    public static function setCommand(
        $route,
        $params = [],
        $runType = 'background'
    ) {
        helper('jobs');
        self::$command = formJobCommand($route, $params, $runType);
        return self::$command;
    }

    public static function getCommand(
        $route,
        $params = [],
        $runType = 'background'
    ) {
        helper('jobs');
        $commandz = formJobCommand($route, $params, $runType);
        return $commandz;
    }

    public static function run(
        $route = '',
        $params = [],
        $runType = 'background'
    ) {
        $commandz = self::setCommand($route, $params, $runType);
        $result = self::runCommand($commandz);
        return $result;
    }

    public static function runCommand($commandz)
    {
        self::checkSupportedOS(); // Check OS support

        self::$pid = shell_exec("{$commandz} echo $!");
        return self::$pid;
    }

    public static function setPid($id)
    {
        self::$pid = $id;
    }

    public function isRunning()
    {
        $runStatus = false;
        self::checkSupportedOS(); // Check OS support

        try {
            $result = shell_exec(sprintf('ps %d 2>&1', self::$pid));
            if (
                count(preg_split("/\n/", $result)) > 2 &&
                !preg_match('/ERROR: Process ID out of range/', $result)
            ) {
                $runStatus = true;
            }
        } catch (Exception $e) {
            $runStatus = false;
        }

        return $runStatus;
    }

    public function stop()
    {
        $stopStatus = null;
        self::checkSupportedOS(); // Check OS support

        try {
            $result = shell_exec(sprintf('kill %d 2>&1', $this->pid));
            if (!preg_match('/No such process/', $result)) {
                $stopStatus = true;
            }
        } catch (Exception $e) {
            $stopStatus = false;
        }

        return $stopStatus;
    }
}
