<?php

namespace App\Controllers\Email;

use App\Libraries\Template\Twig_loader;
use App\Libraries\App_email AS ciMailer;
use Config\Database;

class AdminForgotPassword
{
    private $db;

    public function __construct()
    {
        $this->db = Database::connect(); // connect to default database
    }

    public function sendResetOTPMail($data)
    {
        $response = false;

        helper('default');
        $twig = new Twig_loader();
		$settings = getSettings('config_system');
		$userData = [];

        $user_id = $data['user_id'];
		$user = $this->db->table('user')
			->where('user_id', $user_id)
			->get()
			->getRow();
		if ($user) {
            $userData['name'] = $user->first_name . ' ' . $user->last_name;
            $userData['recover_email_token'] = $user->recover_email_token;

            $mail_params = array(
                'to_name' => $user->first_name . ' ' . $user->last_name,
                'to_email' => $user->email,
                'subject' => 'Sterling Wilson ERP application password reset',
                'content' => $twig->render('email/user_password_reset_otp', ['user' => $userData, 'settings' => $settings])
            );

            // Initiate mail
            $ciMailer = new ciMailer();
            $response = $ciMailer->send($mail_params);
        }

        return $response;  
    }

}