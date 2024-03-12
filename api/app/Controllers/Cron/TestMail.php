<?php

namespace App\Controllers\Cron;

use App\Controllers\BaseController;
use CodeIgniter\CLI\CLI;
use App\Libraries\AppCliUri;
use App\Libraries\AppLog;
use App\Libraries\Template\Twig_loader;
use App\Libraries\App_email as ciMailer;
use Config\Database;


class TestMail extends BaseController
{
    private $db;

    public function __construct()
    {
        $this->db = Database::connect(); // connect to default database

        helper(['default']);    // Loading user & contract_job helpers

        AppLog::initLog(); // Init log
    }

    public function sendMail()
    {
        $response = false;

        $cliUri = new AppCliUri();
        $name = $cliUri->getQuery('name');
        $email = $cliUri->getQuery('email');

        $twig = new Twig_loader();
        $settings = getSettings('config_system');
        
        $mail_params = array(
            'to_name' => $name,
            'to_email' => $email,
            'subject' => 'Test Mail - Sterling Wilson',
            'content' => 'Dev test...'
        );

        // Initiate mail
        $ciMailer = new ciMailer();
        $response = $ciMailer->send($mail_params);
        if ($response) {
            CLI::write('Response: Mail sent');
        } else {
            CLI::write('Response: Mail not sent');
        }
        
    }

}
