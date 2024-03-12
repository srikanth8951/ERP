<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends CI_Controller {

	public function index()
	{
        $data = array(
            'view_name' => "customer/user_profile_view",
			'page_name' => "Profile",
        );
        
		$this->load->view('customer/account_view',$data);
	}

	public function edit()
	{
        $data = array(
            'view_name' => "customer/user_profile_form",
			'page_name' => "Profile",
			'view_type' => 'edit',
			'customer_id' => $this->uri->segment(5)
        );
        
		$this->load->view('customer/account_view',$data);
	}
}
