<?php

namespace App\Libraries;

class App_email
{
    private $mailSetting = [];

    public function __construct()
    {
        helper('default');
        $this->mailSetting = getSettings('config_email');
    }

    public function send($args)
    {
        $mail_engine = $this->mailSetting->mail_engine;
        $emailConfig = ['setting' => $this->mailSetting];
        switch ($mail_engine) {
            case 'smtp':
                $emailEngine = new \App\Libraries\Email\Smtp_engine(
                    $emailConfig
                );
                break;
            default:
                $emailEngine = new \App\Libraries\Email\Mail_engine(
                    $emailConfig
                );
        }

        $response = $emailEngine->send($args);
        return $response;
    }
}
