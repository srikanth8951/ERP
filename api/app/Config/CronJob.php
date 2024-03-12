<?php
namespace Config;

use CodeIgniter\Config\BaseConfig;
use App\Libraries\CronJob\Scheduler;
use App\Libraries\AppJobManager;

class Cronjob extends BaseConfig
{
    /**
     * Directory
     */
    public $FilePath = WRITEPATH . 'cronJob/';

    /**
     * Filename setting
     */
    public $FileName = 'jobs';

    /**
     * Set true if you want save logs
     */
    public $logPerformance = true;

    /*
    |--------------------------------------------------------------------------
    | Log Saving Method
    |--------------------------------------------------------------------------
    |
    | Set to specify the REST API requires to be logged in
    |
    | 'file'   Save in file
    | 'database'  Save in database
    |
    */
    public $logSavingMethod = 'file';

    /*
    |--------------------------------------------------------------------------
    | Database Group
    |--------------------------------------------------------------------------
    |
    | Connect to a database group for logging, etc.
    |
    */
    public $databaseGroup = 'default';

    /*
    |--------------------------------------------------------------------------
    | Cronjob Table Name
    |--------------------------------------------------------------------------
    |
    | The table name in your database that stores cronjobs
    |
    */
    public $tableName = 'cronjob';

    /*
    |--------------------------------------------------------------------------
	| Cronjobs
	|--------------------------------------------------------------------------
    |
	| Register any tasks within this method for the application.
	| Called by the TaskRunner.
	|
	| @param Scheduler $schedule
	*/
    public function init(Scheduler $schedule)
    {
        // Send test mail

        // $mailCommand = AppJobManager::getCommand('cron/TestMail/sendMail', [
        //     'name' => 'sasi',
        //     'email' => 'web@mentrictech.in',
        // ]);
        // $schedule->shell($mailCommand)->everyMinute(15);
        
        // Check contract job expiry
        $expiryCheckCommand = AppJobManager::getCommand('cron/contract_job/check_expiry');
        $schedule->shell($expiryCheckCommand)->everyMinute();

        // Check & update ppm frequencies status
        $checkPPMFreqCommand = AppJobManager::getCommand('cron/ContractJob/checkPPMFrequencies');
        $schedule->shell($checkPPMFreqCommand)->daily('00:01 am');

        // $schedule->command('foo:bar')->everyMinute();

        // $schedule->shell('cp foo bar')->daily( '11:00 pm' );

        // $schedule->call( function() { do something.... } )->everyMonday()->named( 'foo' )
    }
}
