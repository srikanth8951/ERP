<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Department extends CI_Controller {

	public function index()
	{
        $data = array(
            'view_name' => "admin/department_view",
			'page_name' => "Department",
        );
        
		$this->load->view('admin/account_view',$data);
	}
    
    
}
