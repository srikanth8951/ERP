<?php

namespace App\Controllers\Admin\Setting;

use CodeIgniter\RESTful\ResourceController;
use App\Libraries\Auth\User as AuthUser;
use Config\Database;
use App\Models\Admin\SettingModel;

class Email extends ResourceController
{
    function __construct()
	{
		$this->validateUser();
	}

    public function index()
    {
        
        $data = array();
        $data['logged'] = true;
        $this->validatePermission('view_setting'); // Check permission

        $modelSetting = new SettingModel();
        $setting = $modelSetting->getSettings('config_email');
        
        if(isset($setting->mail_engine)){
            $data['mail_engine'] = $setting->mail_engine;
        } else {
            $data['mail_engine'] = '';
        }

        if(isset($setting->host_email)){
            $data['host_email'] = $setting->host_email;
        } else {
            $data['host_email'] = '';
        }

        if(isset($setting->smtp_host)){
            $data['smtp_host'] = $setting->smtp_host;
        } else {
            $data['smtp_host'] = '';
        }

        if(isset($setting->smtp_port)){
            $data['smtp_port'] = $setting->smtp_port;
        } else {
            $data['smtp_port'] = '';
        }

        if(isset($setting->smtp_username)){
            $data['smtp_username'] = $setting->smtp_username;
        } else {
            $data['smtp_username'] = '';
        }

        if(isset($setting->smtp_password)){
            $data['smtp_password'] = $setting->smtp_password;
        } else {
            $data['smtp_password'] = '';
        }

        $response['status'] = 'success';
        $response['data'] = $data;
        return $this->setResponseFormat("json")->respond($response);
    }

    public function save()
    {
        $this->validatePermission('edit_setting'); // Check permission
        $modelSetting = new SettingModel();
        $response = array();
        $resp = array();
        $posts = array(
            'mail_engine' => $this->request->getPost('mail_engine'),
            'host_email' => $this->request->getPost('host_email'),
            'smtp_port' => $this->request->getPost('smtp_port'),
            'smtp_host' => $this->request->getPost('smtp_host'),
            'smtp_port' => $this->request->getPost('smtp_port'),
            'smtp_username' => $this->request->getPost('smtp_username'),
            'smtp_password' => $this->request->getPost('smtp_password')
        );

        if($posts) {
            foreach($posts as $keyword => $value){
                $setting = $modelSetting->getSetting('config_email', $keyword);
                if($setting) {
                    $set = $modelSetting->editSetting($setting->setting_id, $value);
                } else {
                    $set = $modelSetting->addSetting('config_email', $keyword, $value);
                }

                if($set) {
                    array_push($resp, 1);
                } else {
                    array_push($resp, 0);
                }
            }

            if(in_array(0, $resp) == false){
                $response['message'] = 'Email settings are saved';
            } else {
                $response['message'] = 'Email settings are saved... but some error is there';
            }

            $response['status'] = 'success';
        } else {
            $response['status'] = 'error';
            $response['message'] = 'No Post Values';
        }

        return $this->setResponseFormat("json")->respond($response);
    }

    protected function validateUser()
	{
		$this->user_id = AuthUser::isLogged();
		if (! $this->user_id) {
			$response = array(
				'status' => 'error',
				'message' => lang('Common.error_login')
			);
		
			return $this->setResponseFormat("json")->respond($response, 401);
		}
	} 

	protected function validatePermission($permission_name)
	{
		$permission = AuthUser::checkPermission($permission_name);
		if (! $permission) {
			$response = array(
				'status' => lang('status_error'),
				'message' => lang('Common.error_permission')
			);
		
			return $this->setResponseFormat("json")->respond($response, 201);
		}
	}

}