<?php

namespace App\Commands;

use App\Commands\CronJobCommand;
use CodeIgniter\CLI\CLI;
use App\Libraries\CronJob\JobRunner;

class ScheduleRun extends CronJobCommand
{
    /**
     * The Command's Name
     *
     * @var string
     */
    protected $name = 'schedule:run';

    /**
     * The Command's Description
     *
     * @var string
     */
    protected $description = 'Runs tasks based on the schedule';

    /**
     * The Command's Usage
     *
     * @var string
     */
    protected $usage = 'schedule:run [options]';

    /**
     * The Command's Arguments
     *
     * @var array
     */
    protected $arguments = [];

    /**
     * The Command's Options
     *
     * @var array
     */
    protected $options = [
        '-time' => 'Set run time',
    ];

    /**
     * Actually execute a command.
     *
     * @param array $params
     */
    public function run(array $params)
    {
        $this->getConfig();

        CLI::newline();
        CLI::write('### Running tasks ### ');
        CLI::newline();

        $this->config->init(\Config\Services::scheduler());

        $runner = new JobRunner();
        $runTime = $params['time'] ?? CLI::getOption('time');

        if ($runTime) {
            $runner->withTestTime($runTime);
        }

        $runner->run();

        CLI::newline();
        CLI::write('### Completed tasks ### ');
        CLI::newline();
    }
}
