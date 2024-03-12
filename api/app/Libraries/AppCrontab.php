<?php

namespace App\Libraries;

use App\Libraries\AppJobManager as ciJobManager;

class AppCrontab
{
    private static $crontabFilePath = WRITEPATH . 'crontab/php-tmp.php';

    public function getPHPInstallPath()
    {
        $path = exec('whereis php');
        $substrPath = substr($path, 5, strlen($path));
        $pathSegment = explode(' ', $substrPath);
        return trim($pathSegment[0]);
    }

    public function formCommand(array $data)
    {
        $periodStr = '';
        $pathString = trim($data['path']);
        $path = ciJobManager::formPath($pathString);
        // $path = trim($data['path']);
        $periods = json_decode(json_encode($data['period']), true);
        if ($periods) {
            foreach ($periods as $periodz) {
                $periodStr .= $periodz . ' ';
            }
        }
        $link =
            trim($periodStr) .
            ' ' .
            $this->getPHPInstallPath() .
            ' ' .
            (ROOTPATH . $path) .
            ' >/dev/null 2>&1';
        return $link;
    }

    protected function arrayToString(array $datas)
    {
        if ($datas) {
            $newString = implode('\n', $datas);
            return $newString;
        } else {
            return '';
        }
    }

    protected function stringToArray(string $data)
    {
        if ($data) {
            $newArray = explode('\n', trim($data));
            if ($newArray) {
                foreach ($newArray as $aKey => $aValue) {
                    if ($aValue) {
                        unset($newArray[$aKey]);
                    }
                }
            }
        }

        return $newArrayay;
    }

    public function getJobs()
    {
        exec('crontab -l', $jobs);
        if ($jobs) {
            return $jobs;
        } else {
            return [];
        }
    }

    public function getTotalJobs()
    {
        $jobs = $this->getJobs();
        return count($jobs);
    }

    public function isJobExist($job)
    {
        $jobs = $this->getJobs();
        if (is_array($jobs)) {
            if (in_array($job, $jobs)) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function addJob(string $job)
    {
        helper('filesystem');
        if (!$this->isJobExist($job)) {
            $jobs = $this->getJobs();

            $content = $job . PHP_EOL;

            // Update temporary cron file
            $this->updateTmpCronFile($job);

            // Check file existance
            if (!write_file(self::$crontabFilePath, $content, 'a+')) {
                // echo 'Unable to write file!';
                return false;
            } else {
                $this->addJobs();
                return true;
            }
        } else {
            return false;
        }
    }

    public function addJobs()
    {
        $filePath = self::$crontabFilePath;
        exec("crontab {$filePath}");
        // if (file_exists($filePath)) {
        //     unlink($filePath);
        // }
    }

    public function updateTmpCronFile($job)
    {
        // Check job existance
        if (file_exists(self::$crontabFilePath)) {
            $fileContents = file_get_contents(self::$crontabFilePath);
            $newContent = str_replace($job, '', $fileContents);
            // $newContent = preg_replace('/\r|\n/', '', $newContentz);
            file_put_contents(self::$crontabFilePath, $newContent);
        }
    }

    public function removeJob($job)
    {
        $jobs = $this->getJobs();
        if ($jobs) {
            $jobPos = array_search($job, $jobs);
            if ($jobPos !== false) {
                $this->updateTmpCronFile($jobs[$jobPos]);
                unset($jobs[$jobPos]);
            }
        }

        return $this->addJobs($jobs);
    }

    public function removeTab()
    {
        exec('crontab -r', $removed);
        return $removed;
    }

    public function getStatus()
    {
        exec('ps -eaf | grep crond', $output);
        return $output;
    }
}
