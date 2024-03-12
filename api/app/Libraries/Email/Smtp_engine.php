<?php
namespace App\Libraries\Email;

class Smtp_engine 
{
	
	private $mailSetting = array();
    private $from_name;
    private $from_email;

	public function __construct($config)
    {
		$this->mailSetting = $config['setting'];
        $this->from_name = getSetting('app_name');
        $this->from_email = $this->mailSetting->smtp_username;
	}

	public function send($data)
    {
        $email = \Config\Services::email();

		extract($data);	// Extract datas

		//mail configuration
        $config['protocol']    = 'smtp';

        $config['SMTPHost']    = $this->mailSetting->smtp_host;

        $config['SMTPPort']    =  $this->mailSetting->smtp_port;

        $config['SMTPUser']    = $this->mailSetting->smtp_username;

        $config['SMTPPass']    = $this->mailSetting->smtp_password;

        $config['charset']    = 'utf-8';

        $config['newline']    = "\r\n";

        $config['crlf'] = "\r\n";

        $config['mailType'] = 'html'; // or html

        $config['validate'] = TRUE; // bool whether to validate email or not       
        $email->initialize($config);

        $email->setTo($to_email);
        $email->setFrom($this->from_email, $this->from_name);
        $email->setSubject($subject);

        // Carbon copy
        if (isset($to_additionals)) {
            if ($to_additionals) {
                $email->setCC($to_additionals);
            }
                
        }

        $htmlContent = $content;      
        $email->setMessage($htmlContent);

        //Send email
        if ($email->send()) {
            // print_r($email->printDebugger(['headers']));
        	return true;
        } else {
        	return false;
        }
	}
}