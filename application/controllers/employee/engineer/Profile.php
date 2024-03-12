<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends CI_Controller {

	public function index()
	{
        $data = array(
            'view_name' => "employee/engineer/user_profile_view",
			'page_name' => "Profile",
			'employee_id' => $this->uri->segment(5)
        );
        
		$this->load->view('employee/engineer/account_view',$data);
	}

	public function edit()
	{
        $data = array(
            'view_name' => "employee/engineer/user_profile_form",
			'page_name' => "Profile",
			'view_type' => 'edit',
			'employee_id' => $this->uri->segment(5)
        );
        
		$this->load->view('employee/engineer/account_view',$data);
	}
}
