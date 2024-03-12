<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

abstract class CronJobCommand extends BaseCommand
{
    /**
     * The Command's Group
     *
     * @var string
     */
    protected $group = 'cronjob';

    /**
     * Config 
     */
    protected $config = null;

    /**
     * Get Congfig
     */
    public function getConfig()
    {
        $this->config = config( 'CronJob' );
    }
}
