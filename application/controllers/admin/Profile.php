<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends CI_Controller {

	public function index()
	{
        $data = array(
            'view_name' => "admin/user_profile_view",
			'page_name' => "Profile",
        );
        
		$this->load->view('admin/account_view',$data);
	}

    
}
