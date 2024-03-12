<?php

namespace App\Libraries\Email;


class Mail_engine 
{
        
        private $from_name;
        private $from_email;

        public function __construct($config)
        {
                $setting = $config['setting'];
                $this->from_name = getSetting('app_name');
                $this->from_email = $setting->host_email;
        }

        /*
        * Params array(
        *        to_name
        *        to_email
        *        subject
        *        content
        *)
        */
        public function send($data)
        {

                $email = \Config\Services::email();

                extract($data); // Extract datas

                //mail configuration
                $config['protocol']    = 'mail';

                $config['charset']    = 'utf-8';

                $config['newline']    = "\r\n";

                $config['mailType'] = 'html'; // or html

                $config['validate'] = TRUE; // bool whether to validate email or not       
                $email->initialize($config);
                $email->setFrom($this->from_email);
                $email->setTo($to_email);
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
                        return true;
                } else {
                        return false;
                }
        }
}