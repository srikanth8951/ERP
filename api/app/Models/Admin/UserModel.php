<?php

namespace App\Models\Admin;

use Config\Services;
use Config\Database;

class UserModel
{

    private $cdb;

    public function __construct()
    {
        helper(['default', 'text']);
        $this->cdb = Database::connect('default');
    }

    public function getTotalUsers($data = [])
    {
        $condition = array();

        if (isset($data['user_type'])) {
            $condition['usr.user_type'] = $data['user_type'];
        }

        if (isset($data['status'])) {
            $condition['usr.status'] = (int)$data['status'];
        }

        if (isset($data['removed'])) {
            $condition['usr.removed'] = (int)$data['removed'];
        }

        $builder = $this->cdb->table('user usr');
        $builder->select('COUNT(*) AS total');
        $builder->join('designation des', 'des.designation_id = usr.designation');
        $builder->join('department dept', 'dept.department_id = usr.department');
        $builder->where($condition);

        if (isset($data['search'])) {
            $builder->groupStart()
                ->like('(CONCAT(usr.first_name, " ", usr.last_name))', $data['search'])
                ->orLike('(usr.email)', $data['search'])
                ->orLike('(usr.mobile)', $data['search'])
                ->orLike('(des.name)', $data['search'])
                ->orLike('(dept.name)', $data['search'])
                // ->orLike('usr.status', $data['search'])
                ->orLike('DATE_FORMAT(usr.created_datetime, "%d/%m/%Y")', $data['search'])
                ->groupEnd();
        }

        if (isset($data['except'])) {
            $builder->whereNotIn('usr.user_id', $data['except']);
        }
        $query = $builder->get();

        if ($query->getNumRows()) {
            return $query->getRow()->total;
        } else {
            return 0;
        }
    }

    public function getUsers($data = [])
    {
        $condition = array();

        if (isset($data['user_type'])) {
            $condition['usr.user_type'] = $data['user_type'];
        }

        if (isset($data['status'])) {
            $condition['usr.status'] = (int)$data['status'];
        }

        if (isset($data['removed'])) {
            $condition['usr.removed'] = (int)$data['removed'];
        }

        $builder = $this->cdb->table('user AS usr');
        $builder->join('designation AS des', 'des.designation_id = usr.designation');
        $builder->join('department AS dept', 'dept.department_id = usr.department');
        $builder->where($condition);

        if (isset($data['search'])) {
            $builder->groupStart()
                ->like('(CONCAT(usr.first_name, " ", usr.last_name))', $data['search'])
                ->orLike('(usr.email)', $data['search'])
                ->orLike('(usr.mobile)', $data['search'])
                ->orLike('(des.name)', $data['search'])
                ->orLike('(dept.name)', $data['search'])
                // ->orLike('usr.status', $data['search'])
                ->orLike('DATE_FORMAT(usr.created_datetime, "%d/%m/%Y")', $data['search'])
                ->groupEnd();
        }

        if (isset($data['except'])) {
            if ($data['except']) {
                $builder->whereNotIn('usr.user_id', $data['except']);
            }
        }

        $query = $builder->get();

        if ($query->getNumRows()) {
            return $query->getResult();
        } else {
            return false;
        }
    }

    public function getUser($user_id)
    {
        $condition = array();
        $builder = $this->cdb->table('user');
        $builder->select('user_id, first_name, last_name,username, image, email, mobile, 
        user_type, status, removed, created_datetime, updated_datetime');
        $condition['user_id'] = $user_id;
        if (isset($data['status'])) {
            $condition['status'] = (int)$data['status'];
        }

        $builder->where($condition);
        $query = $builder->get();
        if ($query->getNumRows()) {
            return $query->getRow();
        } else {
            return false;
        }
    }

    public function getUserLogins($data = [])
    {
        $condition = array();
        if (isset($data['user_type'])) {
            $condition['u.user_type'] = $data['user_type'];
        }

        if (isset($data['status'])) {
            $condition['u.status'] = (int)$data['status'];
        }

        if (isset($data['login_date'])) {
            $condition['DATE(ul.ul_created_datetime)'] = $data['login_date'];
        }

        if (isset($data['expiry_datetime'])) {
            $condition['ul.ul_expiry_datetime >= '] = $data['expiry_datetime'];
        }

        if (isset($data['login_status'])) {
            $condition['ul.ul_status'] = $data['login_status'];
        }

        $builder = $this->cdb->table('user AS u');
        $builder->join('user_login As ul', 'ul.user_id = u.user_id');
        $builder->select('ul.*');
        $builder->where($condition);

        if (isset($data['except'])) {
            $builder->whereNotIn('user_id', $data['except']);
        }
        $query = $builder->get();

        if ($query->getNumRows()) {
            return $query->getResult();
        } else {
            return false;
        }
    }

    public function getUserByEmail($email, $data = array())
    {
        $condition['email'] = $email;
        if (isset($data['status'])) {
            $condition['status'] = (int)$data['status'];
        }

        if (isset($data['removed'])) {
            $condition['removed'] = (int)$data['removed'];
        }

        $builder = $this->cdb->table('user');
        $builder->where($condition);

        if (isset($data['except'])) {
            if ($data['except']) {
                $builder->whereNotIn('user_id', $data['except']);
            }
        }

        $query = $builder->get();

        if ($query->getNumRows() > 0) {
            return $query->getRow();
        } else {
            return false;
        }
    }

    public function getUserByMobile($mobile, $data = array())
    {

        $condition['mobile'] = $mobile;
        if (isset($data['status'])) {
            $condition['status'] = (int)$data['status'];
        }

        if (isset($data['removed'])) {
            $condition['removed'] = (int)$data['removed'];
        }

        $builder = $this->cdb->table('user');
        $builder->where($condition);

        if (isset($data['except'])) {
            if ($data['except']) {
                $builder->whereNotIn('user_id', $data['except']);
            }
        }

        $query = $builder->get();

        if ($query->getNumRows() > 0) {
            return $query->getRow();
        } else {
            return false;
        }
    }

    public function getUserByUsername($username, $data = array())
    {
        $condition['user_name'] = $username;
        if (isset($data['status'])) {
            $condition['status'] = (int)$data['status'];
        }

        if (isset($data['removed'])) {
            $condition['removed'] = (int)$data['removed'];
        }

        $builder = $this->cdb->table('user');
        $builder->where($condition);

        if (isset($data['except'])) {
            if ($data['except']) {
                $builder->whereNotIn('user_id', $data['except']);
            }
        }

        $query = $builder->get();

        if ($query->getNumRows() > 0) {
            return $query->getRow();
        } else {
            return false;
        }
    }

    public function getUserByUEM($username, $email, $mobile, $data = array())
    {
        $condition = array();
        if (isset($data['status'])) {
            $condition['status'] = (int)$data['status'];
        }

        if (isset($data['removed'])) {
            $condition['removed'] = (int)$data['removed'];
        }

        $builder = $this->cdb->table('user');
        $builder->select('*');
        if ($condition) {
            $builder->where($condition);
        }
        $builder->groupStart()
            ->orWhere('email', $email)
            ->orWhere('mobile', $mobile)
            ->orWhere('user_name', $username)
            ->groupEnd();
        $query = $builder->get();

        if ($query->getNumRows() > 0) {
            return $query->getRow();
        } else {
            return false;
        }
    }

    public function getUserByLogin($user_login_id, $data = [])
    {
        $condition = array(
            'ul.user_login_id' => $user_login_id
        );

        if (isset($data['login_date'])) {
            $condition['DATE(ul.ul_created_datetime)'] = $data['login_date'];
        }

        if (isset($data['expiry_datetime'])) {
            $condition['ul.ul_expiry_datetime >= '] = $data['expiry_datetime'];
        }

        if (isset($data['status'])) {
            $condition['ul.ul_status'] = $data['status'];
        }

        if (isset($data['except_columns'])) {
            $except_columns = $data['except_columns'];
        } else {
            $except_columns = array();
        }

        $builder = $this->cdb->table('user');
        $builder->select('u.*, ul.user_login_id, ul.ul_status AS login_status, ul.ul_expiry_datetime AS login_expiry_datetime, ul.ul_created_datetime AS login_datetime_added, ul.ul_updated_datetime AS login_datetime_modified');
        $builder->from('user AS u');
        $builder->join('user_login As ul', 'ul.user_id = u.user_id');
        $builder->where($condition);
        $query = $builder->get();
        if ($query->getNumRows()) {
            $row = $query->getRow();
            if ($except_columns) {
                foreach ($except_columns as $cvalue) {
                    unset($row->{$cvalue});
                }
            }
            return $row;
        } else {
            return false;
        }
    }

    public function addUser($data)
    {
        $this->cdb->transStart();
        $password = password_hash($data['password'], PASSWORD_BCRYPT);
        $login_data = array(
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'user_name' => $data['user_name'],
            'password' => $password,
            'address' => $data['address'],
            'country' => $data['country'],
            'state' => $data['state'],
            'city' => $data['city'],
            'pincode' => $data['pincode'],
            'email' => $data['email'],
            'mobile' => $data['mobile'],
            'area' => (int)$data['area'],
            'department' => (int)$data['department'],
            'designation' => (int)$data['designation'],
            'work_status' => (int)$data['work_expertise'],
            'work_expertise' => (int)$data['work_expertise'],
            'permission' => $data['permission'],
            'user_type' => $data['user_type'],
            'status' => 1,
            'joining_date' => date("Y-m-d", strtotime($data['joining_date'])),
            'created_datetime' => date('Y-m-d H:i:s')
        );

        $this->cdb->table('user')->insert($login_data);
        $insertId = $this->cdb->insertID();
        $this->cdb->transComplete();

        if ($this->cdb->transStatus() === false) {
            return false;
        } else {
            return $insertId;
        }
    }

    public function editUser($user_id, $data)
    {
        $this->cdb->transStart();
        $login_data = array(
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'mobile' => $data['mobile'],
            'address' => $data['address'],
            'country' => $data['country'],
            'state' => $data['state'],
            'city' => $data['city'],
            'pincode' => $data['pincode'],
            'email' => $data['email'],
            'mobile' => $data['mobile'],
            'area' => (int)$data['area'],
            'department' => (int)$data['department'],
            'designation' => (int)$data['designation'],
            'work_status' => (int)$data['work_expertise'],
            'work_expertise' => (int)$data['work_expertise'],
            'permission' => $data['permission'],
            'user_type' => $data['user_type'],
            'status' => 1,
            'joining_date' => date("Y-m-d", strtotime($data['joining_date'])),

            'updated_datetime' => date('Y-m-d H:i:s')
        );

        $builder = $this->cdb->table('user');
        $builder->where('user_id', $user_id);
        $result = $builder->update($login_data);
        $this->cdb->transComplete();

        if ($this->cdb->transStatus() === false) {
            return false;
        } else {
            return $result;
        }
    }

    public function login($username, $password, $data = [])
    {
        $condition = array(
            'status' => 1,
            'removed' => 0

        );

        $builder = $this->cdb->table('user');
        $builder->where($condition);
        $builder->groupStart()
            ->where('email', $username)
            ->orWhere('mobile', $username)
            ->orWhere('username', $username)
            ->groupEnd();

        if (isset($data['access'])) {
            if ($data['access']) {
                $builder->whereIn('user_type', $data['access']);
            }
        }

        $query = $builder->get();
        // echo $this->cdb->getLastQuery();
        if ($query->getNumRows() > 0) {
            $row = $query->getRow();
            if (password_verify($password, $row->password)) {
                return $row;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }


    public function addUserLogin($user_id, $data)
    {
        $request = Services::request();
        $login_datetime = date('Y-m-d H:i:s');
        $userAgent = $request->getUserAgent();
        $remember_status = $request->getPost('remember_me');
        if ($userAgent->isBrowser()) {
            $rememberStatus = $remember_status ?  1 : 0;
        } else {
            $rememberStatus = 2;
        }

        switch ($rememberStatus) {
            case '0';
                $expirydatetime = calculateTime($login_datetime, array([APP_USER_LOGIN_DURATION, '+']));
                $expiryDatetime = $expirydatetime;
                break;
            case '1':
                $expirydatetime = calculateTime($login_datetime, array(['1 week', '+']));
                $expiryDatetime = $expirydatetime;
                break;
            default:
                $expiryDatetime = '0000-00-00 00:00:00';
        }

        $login_data = array(
            'user_id' => (int)$user_id,
            'ul_status' => 1,
            'ul_ip_address' => $request->getIPAddress(),
            'ul_user_agent' => $userAgent,
            'ul_expiry_datetime' => $expiryDatetime,
            'ul_remember_status' => (int)$rememberStatus,
            'ul_created_datetime' => $login_datetime
        );

        $this->cdb->table('user_login')->insert($login_data);
        $login_id = $this->cdb->insertID();
        if ($login_id) {
            return $login_id;
        } else {
            return false;
        }
    }

    public function setUserLoginExpiry($user_login_id, $expiry_datetime)
    {
        $logout_data = array(
            'ul_expiry_datetime' => $expiry_datetime,
            'ul_updated_datetime' => date('Y-m-d H:i:s')
        );

        $builder = $this->cdb->table('user_login');
        $builder->where('user_login_id', $user_login_id);
        $result = $builder->update($logout_data);
        return $result;
    }

    public function setUserLogout($user_login_id, $data = [])
    {

        $logout_data = array(
            'ul_status' => 0,
            'ul_updated_datetime' => date('Y-m-d H:i:s')
        );

        $builder = $this->cdb->table('user_login');
        $builder->where('user_login_id', $user_login_id);
        $result = $builder->update($logout_data);
        return $result;
    }

    // Form unique token
    public function formUniqueToken($user_id)
    {
        $token = hash('sha256', random_string('alnum', 32) . $user_id . strtotime('now'));
        $exist = $this->getUserByToken($token);
        if ($exist) {
            $this->formUniqueToken();
        } else {
            return $token;
        }
    }

    // Form token
    public function formToken($user_id)
    {
        $token = hash('sha256', random_string('alnum', 32) . $user_id . strtotime('now'));
        return $token;
    }

    // Form OTP
    public function formOTP($user_id)
    {
        $token = random_string('numeric', 4);
        return $token;
    }

    // Reset password
    public function setPassword($user_id, $password)
    {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        $reset_data = array(
            'password' => $hashed_password,
            'updated_datetime' => date('Y-m-d H:i:s')
        );

        $this->cdb->transStart();
        $builder = $this->cdb->table('user');
        $builder->where('user_id', $user_id);
        $result = $builder->update($reset_data);
        $this->cdb->transComplete();

        if ($this->cdb->transStatus() === false) {
            return false;
        } else {
            return $result;
        }
    }

    // Reset password
    public function resetPassword($user_id, $password)
    {
        return $this->setPassword($user_id, $password);
    }


    // Set user status
    public function setUserStatus($user_id, $status)
    {

        $update_data = array(
            'status' => (int)$status,
            'updated_datetime' => date('Y-m-d H:i:s')
        );
        $builder = $this->cdb->table('user');
        $builder->where('user_id', $user_id);
        $result = $builder->update($update_data);
        return $result;
    }

    public function removeUser($user_id)
    {
        $builder = $this->cdb->table('user');
        $builder->where('user_id', $user_id);
        $result = $builder->update(['status' => 0, 'removed' => 1]);
        return $result;
    }

    public function delete($user_id)
    {
        $builder = $this->cdb->table('user');
        $builder->where('user_id', $user_id);
        $builder->delete();

        $builder = $this->cdb->table('social_profile');
        $builder->where('user_id', $user_id);
        $builder->delete();
    }

    public function approval($user_id)
    {

        $approve_data = array(
            'approval' => 0,
            'isVerified' => 1
        );
        $builder = $this->cdb->table('user');
        $builder->where('user_id', $user_id);
        $builder->update($approve_data);
    }

    //Social Profiles
    public function getSocialProfiles($user_id)
    {
        $builder = $this->cdb->table('social_profile');
        $builder->where('user_id', $user_id);
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            $socials = array();
            $results = $query->getResult();
            if ($results) {
                foreach ($results as $result) {
                    $sm_name = $result->sm_name;
                    $socials[$sm_name] = $result->sm_link;
                }
            }

            return $socials;
        } else {
            return false;
        }
    }

    public function getSocialProfile($user_id, $sm_name)
    {
        $condition = array(
            'user_id' => $user_id,
            'sm_name' => $sm_name
        );
        $builder = $this->cdb->table('social_profile');
        $builder->where($condition);
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            $socials = array();
            return $query->getRow();
        } else {
            return false;
        }
    }

    public function addSocialProfile($user_id, $sm_name, $link)
    {
        $insert_data = array(
            'user_id' => $user_id,
            'sm_name' => $sm_name,
            'sm_link' => $link,
        );
        $this->cdb->table('social_profile')->insert($insert_data);
        return $this->cdb->insertID();
    }

    public function updateSocialProfile($profile_id, $link)
    {
        $update_data = array(
            'sm_link' => $link
        );
        $builder = $this->cdb->table('social_profile');
        $builder->where('social_profile_id', $profile_id);
        $query = $builder->update('social_profile', $update_data);
        return $query;
    }

    public function deleteSocialProfile($profile_id)
    {
        $builder = $this->cdb->table('social_profile');
        $builder->where('social_profile_id', $profile_id);
        $builder->delete();
    }

    //User Activity
    public function getActivities($user_id, $data = array())
    {
        $condition = array(
            'user_id' => $user_id
        );
        if (isset($data['notify'])) {
            $condition['is_notify'] = $data['notify'];
        }
        $builder = $this->cdb->table('user_activity');
        $builder->where($condition);
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            $socials = array();
            return $query->getResult();
        } else {
            return false;
        }
    }

    public function getActivity($user_id)
    {
        $condition = array(
            'user_id' => $user_id
        );
        $builder = $this->cdb->table('user_activity');
        $builder->where($condition);
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            $socials = array();
            return $query->getRow();
        } else {
            return false;
        }
    }

    public function addActivity($user_id, $data)
    {
        $insert_data = array(
            'user_id' => $user_id,
            'keyword' => $data['keyword'],
            'message' => $data['message'],
            'link' => $data['link'],
            'is_notify' => $data['notify']
        );
        $this->cdb->table('user_activity')->insert($insert_data);
        return $this->cdb->insertID();
    }

    public function setActivityNotify($user_activity_id, $notify)
    {
        $update_data = array(
            'is_notify' => $notify,
            'updated_datetime' => date('Y-m-d H:i:s')
        );

        $builder = $this->cdb->table('user_activity');
        $builder->where('user_activity_id', $user_activity_id);
        $query = $builder->update($update_data);
        return $query;
    }

    public function deleteActivity($user_activity_id)
    {
        $builder = $this->cdb->table('user_activity');
        $builder->where('user_activity_id', $user_activity_id);
        $builder->delete();
    }

    // Password Rest
    public function getUserByRecoverEmailToken($email, $token, $data = [])
    {
        $condition = array(
            'recover_email_token' => $token,
            'email' => $email
        );

        if (isset($data['status'])) {
            $condition['status'] = $data['status'];
        }

        if (isset($data['mobile'])) {
            $condition['mobile'] = $data['mobile'];
        }

        if (isset($data['except_columns'])) {
            $except_columns = $data['except_columns'];
        } else {
            $except_columns = array();
        }

        $builder = $this->cdb->table('user');
        $builder->select('*');
        $builder->where($condition);
        $query = $builder->get();
        if ($query->getNumRows()) {
            $row = $query->getRow();
            if ($except_columns) {
                foreach ($except_columns as $cvalue) {
                    unset($row->{$cvalue});
                }
            }
            return $row;
        } else {
            return false;
        }
    }

    public function getUserByRecoverMobileOTP($mobile, $otp, $data = [])
    {
        $condition = array(
            'recover_mobile_otp' => $otp,
            'mobile' => $mobile
        );

        if (isset($data['status'])) {
            $condition['status'] = $data['status'];
        }

        if (isset($data['email'])) {
            $condition['email'] = $data['email'];
        }

        if (isset($data['except_columns'])) {
            $except_columns = $data['except_columns'];
        } else {
            $except_columns = array();
        }

        $builder = $this->cdb->table('user');
        $builder->select('*');
        $builder->where($condition);
        $query = $builder->get();
        if ($query->getNumRows()) {
            $row = $query->getRow();
            if ($except_columns) {
                foreach ($except_columns as $cvalue) {
                    unset($row->{$cvalue});
                }
            }
            return $row;
        } else {
            return false;
        }
    }

    public function setRecoverEmailToken($user_id, $token)
    {
        $register_data = array(
            'recover_email_token' => $token,
            'recover_email_datetime' => date('Y-m-d H:i:s'),
            'updated_datetime' => date('Y-m-d H:i:s')
        );

        $this->cdb->transStart();
        $builder = $this->cdb->table('user');
        $builder->where('user_id', $user_id);
        $result = $builder->update($register_data);
        $this->cdb->transComplete();

        if ($this->cdb->transStatus() === false) {
            return false;
        } else {
            return $result;
        }
    }

    public function resetRecoverEmailToken($user_id)
    {
        $register_data = array(
            'recover_email_token' => '',
            'updated_datetime' => date('Y-m-d H:i:s')
        );

        $this->cdb->transStart();
        $builder = $this->cdb->table('user');
        $builder->where('user_id', $user_id);
        $result = $builder->update($register_data);
        $this->cdb->transComplete();

        if ($this->cdb->transStatus() === false) {
            return false;
        } else {
            return $result;
        }
    }

    public function setRecoverMobileOTP($user_id, $otp)
    {
        $register_data = array(
            'recover_mobile_otp' => $otp,
            'recover_mobile_datetime' => date('Y-m-d H:i:s'),
            'updated_datetime' => date('Y-m-d H:i:s')
        );

        $this->cdb->transStart();
        $builder = $this->cdb->table('user');
        $builder->where('user_id', $user_id);
        $result = $builder->update($register_data);
        $this->cdb->transComplete();

        if ($this->cdb->transStatus() === false) {
            return false;
        } else {
            return $result;
        }
    }

    public function resetRecoverMobileOTP($user_id)
    {
        $register_data = array(
            'recover_mobile_otp' => '',
            'updated_datetime' => date('Y-m-d H:i:s')
        );

        $this->cdb->transStart();
        $builder = $this->cdb->table('user');
        $builder->where('user_id', $user_id);
        $result = $builder->update($register_data);
        $this->cdb->transComplete();

        if ($this->cdb->transStatus() === false) {
            return false;
        } else {
            return $result;
        }
    }

    public function getUserByValidation($data = array())
    {
        if (isset($data['removed'])) {
            $condition['removed'] = (int)$data['removed'];
        }

        if (isset($data['status'])) {
            $condition['status'] = (int)$data['status'];
        }

        $builder = $this->cdb->table('user');
        $builder->groupStart()
            ->where('email', $data['email'], 'before')
            ->orWhere('username', $data['username'], 'before')
            ->groupEnd();
        $builder->where($condition);

        if (isset($data['except'])) {
            if ($data['except']) {
                $builder->whereNotIn('user_id', $data['except']);
            }
        }

        $query = $builder->get();

        if ($query->getNumRows() > 0) {
            return $query->getRow();
        } else {
            return false;
        }
    }

    public function editProfile($user_id, $data)
    {

        // Update user
        $user_data = array(
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'mobile' => $data['mobile'],
            'user_type' => $data['user_type'],
            'updated_datetime' => date('Y-m-d H:i:s')
        );

        if (isset($data['image']) && $data['image']) {
            $user_data['image'] = $data['image'];
        }

        $result = $this->cdb->table('user')->where(['user_id' => $user_id])->update($user_data);

        $this->cdb->transComplete();

        if ($this->cdb->transStatus() === false) {
            return false;
        } else {
            return $result;
        }
    }
}
