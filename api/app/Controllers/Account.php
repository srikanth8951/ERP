<?php

namespace App\Controllers;

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

                    if (!empty($userType)) {
                        if ($userType['type_id'] == 217) {
                            $link = 'employee/dmt/dashboard';
                        } else if ($userType['type_id'] == 210) {
                            $link = 'employee/aisd/dashboard';
                        } else if ($userType['type_id'] == 211) {
                            $link = 'employee/regionHead/dashboard';
                        } else if ($userType['type_id'] == 220) {
                            $link = 'employee/rsd/dashboard';
                        } else if ($userType['type_id'] == 221) {
                            $link = 'employee/asd/dashboard';
                        } else if ($userType['type_id'] == 212) {
                            $link = 'employee/areaHead/dashboard';
                        } else if ($userType['type_id'] == 219) {
                            $link = 'employee/cam/dashboard';
                        } else if ($userType['type_id'] == 213) {
                            $link = 'employee/engineer/dashboard';
                        } else if ($userType['type_id'] == 201) {
                            $link = 'employee/nationalHead/dashboard';
                        } else if ($userType['type_id'] == 222) {
                            $link = 'employee/customer/dashboard';
                        } else if ($userType['type_id'] == 214) {
                            $link = 'employee/manager/dashboard';
                        } else if ($userType['type_id'] == 215) {
                            $link = 'employee/supervisor/dashboard';
                        } else if ($userType['type_id'] == 216) {
                            $link = 'employee/technician/dashboard';
                        } else if ($userType['type_id'] == 224) {
                            $link = 'employee/store/dashboard';
                        } else if ($userType['type_id'] == 1) {
                            $link = 'admin/dashboard';
                        }
                    }

                    if ($user->image && file_exists(ROOTPATH . 'public/uploads/user_profile_image' . '/' . $user->image)) {
                        $thumb = base_url() . '/uploads/user_profile_image' . '/' . $user->image;
                    } else {
                        $thumb = '';
                    }

                    $userData = [
                        'first_name' => $user->first_name,
                        'last_name' => $user->last_name,
                        'email' => $user->email,
                        'mobile' => $user->mobile,
                        'profile_image_link' => $thumb,
                        'auth_token' => $accessToken,
                        'type' => $userType['name'] ?? '',
                        'type_id' => $userType['type_id'],
                    ];

                    $json['status'] = 'success';
                    $json['message'] = lang('Login.success_login');
                    $json['user'] = $userData;
                    $json['link'] = $link;
                } else {
                    $json['status'] = 'error';
                    $json['message'] = lang('Login.error_login_creation');
                }
            } else {
                $json['status'] = 'error';
                $json['message'] = lang('Login.error_login_action');
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
                    $response['message'] = lang('Login.success_login_check');
                    $response['user'] = $user;
                    return $this->setResponseFormat("json")->respond($response, 200);
                } else {
                    AuthUser::logoutUser($userLoginId);  // Reset user login
                    $response['status'] = 'error';
                    $response['message'] = lang('Login.error_login_session');
                    return $this->setResponseFormat("json")->respond($response, 401);
                }
            } else {
                AuthUser::logoutUser($userLoginId);  // Reset user login
                $response['status'] = 'error';
                $response['message'] = lang('Login.error_no_user');
                return $this->setResponseFormat("json")->respond($response, 401);
            }
        } else {
            $response['status'] = 'error';
            $response['message'] = lang('Common.error_login');
            return $this->setResponseFormat("json")->respond($response, 401);
        }
    }

    public function changePassword()
    {
        $response = array();
        $modelUser = new UserModel(); // Load user modal
        $userLoginId = AuthUser::getLoginId();
        $currentPassword = $this->request->getPost('user_current_password');
        $user = $modelUser->getUserByLogin($userLoginId);
        if ($user) {
            if (password_verify($currentPassword, $user->password)) {
                $newPassword = $this->request->getPost('user_new_password');
                $updated = $modelUser->setPassword($user->user_id, $newPassword);
                if ($updated) {
                    $response = array(
                        'status' => 'success',
                        'message' => lang('ForgotPassword.success_password_change')
                    );
                    return $this->setResponseFormat("json")->respond($response, 200);
                } else {
                    $response = array(
                        'status' => 'error',
                        'message' => lang('ForgotPassword.error_password_change')
                    );
                    return $this->setResponseFormat("json")->respond($response, 201);
                }
            } else {
                $response = array(
                    'status' => 'error',
                    'message' => lang('ForgotPassword.error_password_verify')
                );
                return $this->setResponseFormat("json")->respond($response, 201);
            }
        } else {
            $response['status'] = 'error';
            $response['message'] = lang('Login.error_no_user');
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
            $response['message'] = lang('Login.success_logout');
            return $this->setResponseFormat("json")->respond($response, 200);
        } else {
            $response['status'] = 'error';
            $response['message'] = lang('Login.error_logout_action');
            return $this->setResponseFormat("json")->respond($response, 201);
        }
    }

    protected function validateUser()
    {
        $this->user_id = AuthUser::isLogged();
        if (!$this->user_id) {
            $response = array(
                'status' => 'error',
                'message' => lang('Common.invalid_login')
            );

            return $this->setResponseFormat("json")->respond($response, 201);
        }
    }
}
