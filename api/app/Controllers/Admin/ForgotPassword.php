<?php

namespace App\Controllers\Admin;

use CodeIgniter\RESTful\ResourceController;
use App\Libraries\Auth\User as AuthUser;
use Config\Database;
use App\Models\Admin\UserModel;

class ForgotPassword extends ResourceController
{

	private $error = array();

	function __construct() {
		helper('text'); // Load String helper
	}

	// For Email
	public function sendPasswordResetMail()
	{
		if ($this->validateForgotForm()) {
			$modelUser = new UserModel(); // Load user modal
			$user = $modelUser->getUserByEmail($this->request->getPost('user_email'));
			if ($user) {
				if ((int)$user->status == 1) {
					$token = $modelUser->formOTP($user->user_id);
					$recoverResult = $modelUser->setRecoverEmailToken($user->user_id, $token);
					
					if ($recoverResult) {
						// Send notify mail to user
						$emailuser = new \App\Controllers\Email\AdminForgotPassword();
						$mailResponse = $emailuser->sendResetOTPMail([
							'user_id' => $user->user_id,
							'user_base_link' => ''
						]);
						
						if ($mailResponse) {
							$response = array(
								'status' => 'success',
								'message' => lang('success_password_mail'),
								'message_info' => sprintf(lang('Admin.ForgotPassword.success_send_mail'), $user->email)
							);
						} else {
							$response = array(
								'status' => 'error',
								'message' => lang('Admin.ForgotPassword.error_update_recovery')
							);
						}
					} else {
						$response = array(
							'status' => 'error',
							'message' => lang('Admin.ForgotPassword.error_send_mail')
						);
					}
				} else {
					$response = array(
						'status' => 'error',
						'message' => lang('Admin.ForgotPassword.error_account_status')
					);
				}
			} else {
				$response = array(
					'status' => 'error',
					'message' => lang('Admin.ForgotPassword.error_no_account')
				);
			}
		} else {
			$response['status'] = 'error';
            $response['message'] = $this->error ? $this->error : '';
		}

		return $this->setResponseFormat("json")->respond($response, 201);
	}

	public function recoverPasswordByEmail()
	{

        $data = array();
		$modelUser = new UserModel(); // Load user modal

        $token = $this->request->getPost('recover_otp');
        $email = $this->request->getPost('recover_email');
        $user = $modelUser->getuserByRecoverEmailToken($email, $token);
        $current_date = date('Y-m-d H:i:s');
        if ($user) {
        	if ((int)$user->status == 1) {
	            $activation_date = date('Y-m-d H:i:s', strtotime('+30 minutes', strtotime($user->recover_email_datetime)));
	            // echo $current_date; echo '<br>'; echo $activation_date;
	            if ($current_date <= $activation_date) {
	                $response = array(
						'status' => 'success',
						'message' => lang('success_user_validation'),
						'user' => array(
							'email' => $user->email,
							'mobile' => $user->mobile
						)
					);
					return $this->setResponseFormat("json")->respond($response, 200);
	            } else {
	                $response = array(
						'status' => 'error',
						'message' => lang('error_recovery_time_exceeds')
					);
					return $this->setResponseFormat("json")->respond($response, 201);
	            }
	        } else {
	        	$response = array(
					'status' => 'error',
					'message' => lang('error_account_status')
				);
				return $this->setResponseFormat("json")->respond($response, 201);
	        }
        } else {
            $response = array(
				'status' => 'error',
				'message' => lang('error_recovery_link_expired')
			);
			return $this->setResponseFormat("json")->respond($response, 201);
        }

    }

	public function resetPasswordByEmail()
	{

        $data = array();
		$modelUser = new UserModel(); // Load user modal
        $token = $this->request->getPost('recover_otp');
        $email = $this->request->getPost('recover_email');
        $user = $modelUser->getuserByRecoverEmailToken($email, $token);
        $current_date = date('Y-m-d H:i:s');
        if ($user) {
        	if ((int)$user->status == 1) {
	            $recover_date = date('Y-m-d H:i:s', strtotime('+30 minutes', strtotime($user->recover_email_datetime)));
	            //echo $current_date; echo '<br>'; echo $recover_date;
	            if ($current_date <= $recover_date) {

	            	$password = $this->request->getPost('user_password');
					$updated = $modelUser->setPassword($user->user_id, $password);
					if ($updated) {
		                $verify = $modelUser->resetRecoverEmailToken($user->user_id);
		          		$response = array(
								'status' => 'success',
								'message' => lang('success_password_reset')
							);
							return $this->setResponseFormat("json")->respond($response, 200);
					} else {
						$response = array(
							'status' => 'error',
							'message' => lang('error_password_reset')
						);
						return $this->setResponseFormat("json")->respond($response, 201);
					}

	            } else {
	                $response = array(
						'status' => 'error',
						'message' => lang('error_recovery_time_exceeds')
					);
					return $this->setResponseFormat("json")->respond($response, 201);
	            }
	        } else {
	        	$response = array(
					'status' => 'error',
					'message' => lang('error_account_status')
				);
				return $this->setResponseFormat("json")->respond($response, 201);
	        }
        } else {
            $response = array(
				'status' => 'error',
				'message' => lang('error_recovery_link_expired')
			);
			return $this->setResponseFormat("json")->respond($response, 201);
        }

    }


	protected function validateForgotForm()
	{
		$user_email = $this->request->getPost('user_email');
        if (!filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
            $this->error['email'] = lang('Admin.ForgotPassword.error_user_email');
        }
        
        return !$this->error;
    }
    
}