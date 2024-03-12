<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Client_account_manager extends CI_Controller {

	public function index()
	{
        $data = array(
            'view_name' => "employee/aisd/users/cam_list",
			'page_name' => "Client Account Manger",
        );
        
		$this->load->view('employee/aisd/account_view',$data);
	}

	public function view()
	{
        $data = array(
            'view_name' => "employee/aisd/users/cam_view",
			'page_name' => "Client Account Manger",
			'employee_id' => $this->uri->segment(6)
        );
        
		$this->load->view('employee/aisd/account_view',$data);
	}

}
