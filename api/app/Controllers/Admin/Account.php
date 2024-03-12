<?php

namespace App\Controllers\Admin;

use CodeIgniter\RESTful\ResourceController;
use App\Libraries\Auth\User as AuthUser;
use App\Models\Admin\UserModel;

class Account extends ResourceController
{
    private $error = array();

    public function __construct()
    {
        helper('user');    // Load helper
    }

	public function login()
    {
		$json = array();
        $modelUser = new UserModel(); // Load user modal

        $rules = [
			"user_name" => "required",
			"user_password" => "required"
		];

		$messages = [
			"user_name" => [
				"required" => "Username is required",
			],
			"user_password" => [
				"required" => "Password is required"
			],
		];

        if ($this->validate($rules, $messages)) {
    	    $user_name = $this->request->getPost('user_name');
    	    $password = $this->request->getPost('user_password');

            $filter_data = array();
    		$user = $modelUser->login($user_name, $password, $filter_data);   // Check Login
            
    		if ($user) {
                $login_datetime = date('Y-m-d H:i:s');
                $login_data = array();
                $login = $modelUser->addUserLogin($user->user_id, $login_data); // Add user login
                if ($login) {
                    $accessToken = AuthUser::generateToken($user->user_id, $login);    // Generate token
                    
                    $userType = getUserByTypeId($user->user_type);  // Get User type
                    $userData = [
                        'first_name' => $user->first_name,
                        'last_name' => $user->last_name,
                        'email' => $user->email,
                        'mobile' => $user->mobile,
                        'auth_token' => $accessToken,
                        'type' => $userType['name'] ?? ''
                    ];
                    
                    $json['status'] = 'success';
                    $json['message'] = 'Loggedin successfully';
                    $json['user'] = $userData;
                } else {
                    $json['status'] = 'error';
                    $json['message'] = 'Login not created!';
                }
                
    		} else {
    			$json['status'] = 'error';
    			$json['message'] = 'Invalid Email/Password!';
    		}
        } else {
            $json['status'] = 'error';
            $json['message'] = $this->validator->getErrors();
        }

		return $this->setResponseFormat("json")->respond($json, 201);
	}

    public function checkLoggedin()
    {
        $response = array();
        $modelUser = new UserModel(); // Load user modal
        if (AuthUser::isLogged()) {
            $userLoginId = AuthUser::getLoginId();
            
            $filter_login = array(
                // 'expiry_datetime' => date('Y-m-d H:i:s'),
                'except_columns' => array('login_token', 'password')
            );
            $user = $modelUser->getUserByLogin($userLoginId, $filter_login);
            if ($user) {
                $login_expiry_datetime = $user->login_expiry_datetime ? $user->login_expiry_datetime : '0000-00-00 00:00:00';
                if ($login_expiry_datetime >= date('Y-m-d H:i:s')) {
                    $user_type_id = $user->user_type;
                    $user->user_type = getUserByTypeId($user_type_id);

                    if ($user->image) {
                        if (file_exists(ROOTPATH . 'public/uploads/user_profile_image/' . $user->image)) {
                            $user->image = base_url() . '/public/uploads/user_profile_image/' . $user->image;
                        } else {
                            $user->image = "http://mentric.co.in/sw/assets/images/users/avatar-1.jpg";
                        }
                    } else {
                        $user->image = "http://mentric.co.in/sw/assets/images/users/avatar-1.jpg";
                    }

                    $response['status'] = 'success';
                    $response['message'] = 'Loggedin';
                    $response['user'] = $user;
                    return $this->setResponseFormat("json")->respond($response, 200);
                } else {
                    AuthUser::logoutUser($userLoginId);  // Reset user login
                    $response['status'] = 'error';
                    $response['message'] = 'Login Session Expired!';
                    return $this->setResponseFormat("json")->respond($response, 401);
                }
            } else {
                AuthUser::logoutUser($userLoginId);  // Reset user login
                $response['status'] = 'error';
                $response['message'] = 'No user available!';
                return $this->setResponseFormat("json")->respond($response, 401);
            } 
            
        } else {
            $response['status'] = 'error';
            $response['message'] = lang('Common.error_login');
            return $this->setResponseFormat("json")->respond($response, 401);
        }   
    }

    public function logout()
    {
        $response = array();
        
        $userLoginId = AuthUser::getLoginId();
        $logout = AuthUser::logoutUser($userLoginId);
        if ($logout) {
            $response['status'] = 'success';
            $response['message'] = 'Logged out';
            return $this->setResponseFormat("json")->respond($response, 200);
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Logged out';
            return $this->setResponseFormat("json")->respond($response, 201);
        }
        
    }

    protected function validateUser() {
        $this->user_id = AuthUser::isLogged();
        if (!$this->user_id) {
            $response = array(
                'status' => 'error',
                'message' => lang('Account.invalid_login')
            );
            
            return $this->setResponseFormat("json")->respond($response, 201);
        }
    }

}
