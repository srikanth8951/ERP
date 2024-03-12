<?php

namespace App\Commands;

use App\Commands\CronJobCommand;
use CodeIgniter\CLI\CLI;

class SchedulePublish extends CronJobCommand
{
    /**
     * The Command's Name
     *
     * @var string
     */
    protected $name = 'schedule:publish';

    /**
     * The Command's Description
     *
     * @var string
     */
    protected $description = '';

    /**
     * The Command's Usage
     *
     * @var string
     */
    protected $usage = 'schedule:publish';

    /**
     * Actually execute a command.
     *
     * @param array $params
     */
    public function run(array $params)
    {
        $this->getConfig();

        $logMethod = $this->config->logSavingMethod;
        if ($logMethod == 'database') {
            command('make:migrate');
        } else {
            $filePath = $this->config->FilePath;
            if (!is_dir($filePath)) {
                mkdir($filePath);
            }
        }

        CLI::write('Cronjob published');
    }
}
