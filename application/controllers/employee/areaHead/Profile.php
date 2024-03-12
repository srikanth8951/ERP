<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends CI_Controller {

	public function index()
	{
        $data = array(
            'view_name' => "employee/areaHead/user_profile_view",
			'page_name' => "Profile",
        );
        
		$this->load->view('employee/areaHead/account_view',$data);
	}

    
}
