<?php

    namespace App\Libraries\Auth;

    use App\Libraries\Auth\Token as AuthToken;

    class User
    {
        private static $user_id = 0;
        private static $first_name = '';
        private static $last_name = '';
        private static $user_email = '';
        private static $user_mobile = '';
        private static $user_type = 0;
        private static $user_token = '';
        private static $permissions = array();
        private static $user_login_id = 0;
        private static $user_login_remember_status = 0;
        
        public static function isLogged() : int
        {
            helper('default'); // Load default helper
            $request = \Config\Services::request();
            $builder = \Config\Database::connect();
            $loginProceed = false;
            if ($request->getVar('Authorization')) {
                $authorization = $request->getVar('Authorization');
            } else {
                $authorization = $request->getServer('HTTP_AUTHORIZATION');
            }
                    
            self::$user_token = $authorization;
            $authData = AuthToken::getJWT($authorization);
            
            $authStatus = isset($authData['status']) ? $authData['status'] : false;
            if ($authStatus == 'success') {
                
                $Uid = isset($authData['data']['uid']) ? $authData['data']['uid'] : '';
                $Ulid = isset($authData['data']['ulid']) ? $authData['data']['ulid'] : '';

                $condition_data = array(
                    'ul.user_login_id' => $Ulid,
                    'u.user_id' => $Uid,
                    'u.status' => 1,
                    'ul.ul_status' => 1
                );

                $result = $builder
                    ->table('user As u')
                    ->join('user_login AS ul', 'ul.user_id = u.user_id')
                    ->select('u.*, ul.user_login_id, ul.ul_expiry_datetime AS login_expiry_datetime, ul.ul_created_datetime AS login_datetime, ul.ul_status AS login_status, ul.ul_remember_status AS login_remember_status')
                    ->where($condition_data)
                    ->get();
                
                if ($result->getNumRows()) {
                    $row = $result->getRow();
                    
                    if ($row->login_remember_status == 2) {
                        $loginProceed = true;
                    } else {
                        $login_expiry_datetime = $row->login_expiry_datetime ? $row->login_expiry_datetime : '0000-00-00 00:00:00';
                        $loginProceed = $login_expiry_datetime >= date('Y-m-d H:i:s') ? true : false;
                    }
                    
                    if ($loginProceed) {
                        self::$user_id = (int)$row->user_id;
                        self::$first_name = $row->first_name;
                        self::$last_name = $row->last_name;
                        self::$user_email = $row->email;
                        self::$user_mobile = $row->mobile;
                        self::$user_type = (int)$row->user_type;
                        self::$user_login_id = (int)$row->user_login_id;
                        self::$user_login_remember_status = (int)$row->login_remember_status;

                        // get Permissions
                        if ($row->permission) {
                            self::$permissions = $row->permission ? json_decode($row->permission) : '';
                        }

                        // Update user expiry datetime                            
                        if (self::$user_login_remember_status == 0) {
                            self::updateExpiry($row->user_login_id);
                        }
                    } else {
                        self::resetUser();
                    }  
                } else {
                    self::resetUser();
                }
            } else {
                $assocResponse = [
                    "status" => "error",
                    "message" => $authData["message"],
                    "authData" => []
                ];
            }
            return self::$user_id;
        }

        public static function updateExpiry($user_login_id, $login_duration = null)
        {   
            $builder = \Config\Database::connect();

            // Update expiration time
            $currrentDatetime = date('Y-m-d H:i:s');
            if (! $login_duration) {
                $login_duration_string = array([APP_USER_LOGIN_DURATION, '+']);
            } else {
                $login_duration_string = array([$login_duration, '+']);
            }
            
            $expiry_datetimez = calculateTime($currrentDatetime, $login_duration_string);
            $update_data = array(
                'ul_expiry_datetime' => $expiry_datetimez,
                'ul_updated_datetime' => $currrentDatetime
            );
            $builder
                ->table('user_login')
                ->where(['user_login_id' => $user_login_id])
                ->update($update_data);
        }

        protected static function resetUser($user_login_id = null)
        {
            if (! $user_login_id) {
                $user_login_id = self::$user_login_id;
            }

            self::$user_id = 0;
            self::$first_name = '';
            self::$last_name = '';
            self::$user_email = 0;
            self::$user_mobile = 0;
            self::$user_type = 0;
            self::$user_token = '';
            self::$permissions = array();
            self::$user_login_id = 0;
            self::$user_login_remember_status = 0;
    
            // Logout user
            if ($user_login_id) {
                self::logoutUser($user_login_id);
            }
        }
        
        public static function logoutUser($user_login_id)
        {
            $builder = \Config\Database::connect();
            $logout_data = array(
                'ul_status' => 0,
                'ul_updated_datetime' => date('Y-m-d H:i:s')
            );
            
            $result = $builder
                ->table('user_login')
                ->where('user_login_id', $user_login_id)
                ->update($logout_data);
            return $result;
        }

        public static function generateToken($user_id, $login_id) : string
        {
            $payload = array(
                "iss" => "mentrictech.in",
                "sub" => "User Access Token",
                "uid" => $user_id,
                "ulid" => $login_id
            );
            $accessToken = AuthToken::generateJWT($payload); 
            return $accessToken;
        }

        public static function getUserToken() : string
        {
            return self::$user_token;
        }
    
        public static function getUserType() : array
        {
            $utype = getUserType(self::$user_type);
            if ($utype) {
                return $utype;
            } else {
                return [];
            }
        }

        public static function getId() : int
        {
            return self::$user_id;
        }

        public static function getLoginId() : int
        {
            return self::$user_login_id;
        }

        public static function getEmail() : string
        {
            return self::$user_email;
        }

        public static function getMobile() : string
        {
            return self::$user_mobile;
        }

        public static function getUserName() : string
        {
            return self::$first_name . ' ' . self::$last_name;
        }

        public static function checkPermission($code) : bool
        {
            if (in_array($code, self::$permissions)) {
                return true;
            } else {
                return false;
            }
        }

    }