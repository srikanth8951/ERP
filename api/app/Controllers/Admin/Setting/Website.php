<?php

namespace App\Controllers\Admin\Setting;

use CodeIgniter\RESTful\ResourceController;
use App\Libraries\Auth\User as AuthUser;
use Config\Database;
use App\Models\Admin\SettingModel;

class Website extends ResourceController
{
    private $user_id;

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
        $setting = $modelSetting->getSettings('config_website');
        
        if(isset($setting->app_name)){
            $data['app_name'] = html_entity_decode($setting->app_name);
        } else {
            $data['app_name'] = '';
        }

        if(isset($setting->app_slogan)){
            $data['app_slogan'] = html_entity_decode($setting->app_slogan);
        } else {
            $data['app_slogan'] = '';
        }

        if(isset($setting->app_company_name)){
            $data['app_company_name'] = html_entity_decode($setting->app_company_name);
        } else {
            $data['app_company_name'] = '';
        }

        if(isset($setting->app_logo)){
            if($setting->app_logo && file_exists(ROOTPATH . 'public/images/' . $setting->app_logo)) {
                $data['app_logo'] = base_url('public/images/' . $setting->app_logo);
            } else {
                $data['app_logo'] = base_url('public/images/logo.png');   
            }
        } else {
            $data['app_logo'] = base_url('public/images/logo.png');
        }

        if(isset($setting->app_email)){
            $data['app_email'] = $setting->app_email;
        } else {
            $data['app_email'] = '';
        }

        if(isset($setting->app_mobile)){
            $data['app_mobile'] = $setting->app_mobile;
        } else {
            $data['app_mobile'] = '';
        }

        if(isset($setting->app_file_storage)){
            $data['app_file_storage'] = $setting->app_file_storage;
        } else {
            $data['app_file_storage'] = 'default';
        }

        if(isset($setting->app_maintenance_mode)){
            $data['app_maintenance_mode'] = $setting->app_maintenance_mode;
        } else {
            $data['app_maintenance_mode'] = 0;
        }

        if(isset($setting->app_addr_streetname)){
            $data['app_addr_streetname'] = $setting->app_addr_streetname;
        } else {
            $data['app_addr_streetname'] = '';
        }

        if(isset($setting->app_addr_city)){
            $data['app_addr_city'] = $setting->app_addr_city;
        } else {
            $data['app_addr_city'] = '';
        }

        if(isset($setting->app_addr_state)){
            $data['app_addr_state'] = $setting->app_addr_state;
        } else {
            $data['app_addr_state'] = '';
        }

        if(isset($setting->app_addr_pincode)){
            $data['app_addr_pincode'] = $setting->app_addr_pincode;
        } else {
            $data['app_addr_pincode'] = '';
        }

        if(isset($setting->app_facebook_profile)){
            $data['app_facebook_profile'] = $setting->app_facebook_profile;
        } else {
            $data['app_facebook_profile'] = '';
        }
        if(isset($setting->app_twitter_profile)){
            $data['app_twitter_profile'] = $setting->app_twitter_profile;
        } else {
            $data['app_twitter_profile'] = '';
        }
        if(isset($setting->app_google_profile)){
            $data['app_google_profile'] = $setting->app_google_profile;
        } else {
            $data['app_google_profile'] = '';
        }
        if(isset($setting->app_youtube_profile)){
            $data['app_youtube_profile'] = $setting->app_youtube_profile;
        } else {
            $data['app_youtube_profile'] = '';
        }
        if(isset($setting->app_linkedin_profile)){
            $data['app_linkedin_profile'] = $setting->app_linkedin_profile;
        } else {
            $data['app_linkedin_profile'] = '';
        }
        if(isset($setting->app_instagram_profile)){
            $data['app_instagram_profile'] = $setting->app_instagram_profile;
        } else {
            $data['app_instagram_profile'] = '';
        }

        $response['status'] = 'success';
        $response['data'] = $data;
        return $this->setResponseFormat("json")->respond($response);
    }

    public function save()
    {
        $this->validatePermission('edit_setting'); // Check permission
        $response = array();
        $resp = array();
        $posts = array(
            'app_name' => $this->request->getPost('app_name'),
            'app_slogan' => $this->request->getPost('app_slogan'),
            'app_company_name' => $this->request->getPost('app_company_name'),
            'app_email' => $this->request->getPost('app_email'),
            'app_mobile' => $this->request->getPost('app_mobile'),
            'app_file_storage' => $this->request->getPost('app_file_storage'),
            'app_addr_streetname' => $this->request->getPost('app_addr_streetname'),
            'app_addr_city' => $this->request->getPost('app_addr_city'),
            'app_addr_state' => $this->request->getPost('app_addr_state'),
            'app_addr_pincode' => $this->request->getPost('app_addr_pincode'),
            'app_facebook_profile' => $this->request->getPost('app_facebook_profile'),
            'app_google_profile' => $this->request->getPost('app_google_profile'),
            'app_twitter_profile' => $this->request->getPost('app_twitter_profile'),
            'app_linkedin_profile' => $this->request->getPost('app_linkedin_profile'),
            'app_youtube_profile' => $this->request->getPost('app_youtube_profile'),
            'app_instagram_profile' => $this->request->getPost('app_instagram_profile')
        );
        
        $modelSetting = new SettingModel();

        if($posts) {
            foreach($posts as $keyword => $value){
                $setting = $modelSetting->getSetting('config_website', $keyword);
                if($setting) {
                    $set = $modelSetting->editSetting($setting->setting_id, $value);
                } else {
                    $set = $modelSetting->addSetting('config_website', $keyword, $value);
                }

                if($set) {
                    array_push($resp, 1);
                } else {
                    array_push($resp, 0);
                }
            }

            if(in_array(0, $resp) == false){
                $response['message'] = 'Website settings are saved';
                
                // Upload image
                $setting = $modelSetting->getSetting('config_website', 'app_logo');
                $upload = false;
                if($setting) {
                    $settingLogoValue = $setting->value;
                } else {
                    $settingLogoValue = '';
                }

                if(!empty($this->request->getPost('app_logo_blob'))) {
                    $upload = $this->savePicture($this->request->getPost('app_logo_blob'), $settingLogoValue, 'blob');
                }

                if($upload){
                    $json = $upload;
                    if(isset($upload['status']) && $upload['status'] == 'success'){
                        $setting = $modelSetting->getSetting('config_website', 'app_logo');
                        if($setting) {
                            $saveImage = $modelSetting->editSetting($setting->setting_id, $upload['image']);
                        } else {
                            $saveImage = $modelSetting->addSetting('config_website', 'app_logo', $upload['image']);
                        }
                        if(!$saveImage){
                            $json['message'] .= 'Image not uploaded!';
                        }
                    }
                } else {
                    $json['error'] = true;
                    $json['message'] = 'Upload image not available!';
                }
                
            } else {
                $response['message'] = 'Website settings are saved... but some error is there';
            }

            $response['status'] = 'success';
        } else {
            $response['status'] = 'error';
            $response['message'] = 'No Post Values';
        }


        return $this->setResponseFormat("json")->respond($response);
    }
    
    //Save image
    private function savePicture($uploadImage, $thumb='', $uploadType='file') {
        $json = array();
        $json['status'] = false;
        $file_upload = false;

        if(!empty($uploadImage)) {
            
            // Load file storage library
            $fileStorage = new \App\Libraries\Storage\DefaultStorage();
            if($uploadType == 'blob'){

                $file_data = array(
                    'blob' => $uploadImage,
                    'newName' => 'applogo' . date('YmdHis'),
                    'uploadPath' => ROOTPATH . 'public/images/',
                    'extension' => 'png'
                );
               
                $file_upload = $fileStorage->uploadBlobFile($file_data);
            } else {

                $filename = $uploadImage->getClientName();
                $file_data = array(
                    'file' => $uploadImage,
                    'newName' => $filename . 'pic' . date('YmdHis'),
                    'uploadPath' => ROOTPATH . 'public/images/'
                );

                $file_upload = $fileStorage->uploadFile($file_data);
            }

            if($thumb) {
                $fileStorage->deleteFile($file_data['uploadPath'].$thumb);
            }
                
            $file_upload_status = isset($file_upload['status']) ? $file_upload['status'] : false;
            if($file_upload_status){
                $json['status'] = 'success';
                $json['message'] = 'Image uploaded';
                $json['image'] = $file_upload['name'];
            } else {
                $json['status']= 'error';
                $json['message'] = $file_upload['message'];
            }
        } else {
            $json['status']= 'error';
            $json['message'] = 'Please upload image file!';
        }
        
        return $json;
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