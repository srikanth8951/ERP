<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Password extends CI_Controller {

	public function index()
	{
        $data = array(
            'view_name' => "employee/manager/password_change_view",
			'page_name' => "CHANGE PASSWORD",
        );
        
		$this->load->view('employee/manager/account_view',$data);
	}
   
}
