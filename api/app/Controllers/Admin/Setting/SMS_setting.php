<?php

// Path: admin > setting > SMS_setting.php  

defined('BASEPATH') OR exit('No direct script access allowed');

class SMS_setting extends CI_Controller {
    private $user_id;
    
    function __construct(){
        parent::__construct();
        $this->api->init(); // Init api

        $this->load->library('users');  // Load users library
        $this->validate();
    }

    public function index(){
        $this->api->checkMethod('post'); // Check Method
        $this->validatePermission('view_setting'); // Check permission
        $data = array();
        $data['logged'] = true;

        $setting = $this->model_setting->getSettings('config_sms');
        
        if(isset($setting->api_provider)){
            $data['api_provider'] = $setting->api_provider;
        } else {
            $data['api_provider'] = '';
        }

        if(isset($setting->api_url)){
            $data['api_url'] = $setting->api_url;
        } else {
            $data['api_url'] = '';
        }

        if(isset($setting->api_id)){
            $data['api_id'] = $setting->api_id;
        } else {
            $data['api_id'] = '';
        }

        if(isset($setting->api_key)){
            $data['api_key'] = $setting->api_key;
        } else {
            $data['api_key'] = '';
        }

        $response['status'] = 'success';
        $response['data'] = $data;
        $this->api->response($response, HTTP_OK);
    }

    public function save() {
        $this->api->checkMethod('post'); // Check Method
        $this->validatePermission('edit_setting'); // Check permission
        $response = array();
        $resp = array();
        $posts = array(
            'api_provider' => $this->input->post('api_provider'),
            'api_url' => $this->input->post('api_url'),
            'api_id' => $this->input->post('api_id'),
            'api_key' => $this->input->post('api_key')
        );

        if($posts) {
            foreach($posts as $keyword => $value){
                $setting = $this->model_setting->getSetting('config_sms', $keyword);
                if($setting) {
                    $set = $this->model_setting->editSetting($setting->setting_id, $value);
                } else {
                    $set = $this->model_setting->addSetting('config_sms', $keyword, $value);
                }

                if($set) {
                    array_push($resp, 1);
                } else {
                    array_push($resp, 0);
                }
            }

            if(in_array(0, $resp) == false){
                $response['message'] = 'Website settings are saved';
            } else {
                $response['message'] = 'Website settings are saved... but some error is there';
            }

            $response['status'] = 'success';
            $response_status = HTTP_OK;
        } else {
            $response['status'] = 'error';
            $response['message'] = 'No Post Values';
            $response_status = HTTP_ACCEPTED;
        }


        $this->api->response($response, $response_status);
    }
    
    protected function validate(){
        $this->user_id = $this->users->isLogged();
        if(!$this->user_id){
            $response = array(
                'status' => 'error',
                'message' => 'Invalid Login'
            );
        
            $this->api->response($response, HTTP_UNAUTHORIZED);
        } else {
            $this->loadDetails();
        }
    } 

    protected function validatePermission($permission_name){
        $permission = $this->users->checkPermission($permission_name);
        if(!$permission){
            $response = array(
                'status' => 'error',
                'message' => 'Permission Denied'
            );
        
            $this->api->response($response, HTTP_ACCEPTED);
        }
    }

    protected function loadDetails() {
        $this->load->model('Setting_model', 'model_setting');   // Load setting model
    }
}