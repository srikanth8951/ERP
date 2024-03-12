<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Password extends CI_Controller {

	public function index()
	{
        $data = array(
            'view_name' => "employee/regionHead/password_change_view",
			'page_name' => "CHANGE PASSWORD",
        );
        
		$this->load->view('employee/regionHead/account_view',$data);
	}
	
}
