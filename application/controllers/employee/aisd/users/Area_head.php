<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Area_head extends CI_Controller {

	public function index()
	{
        $data = array(
            'view_name' => "employee/aisd/users/area_head_list",
			'page_name' => "Area Marketing Head",
        );
        
		$this->load->view('employee/aisd/account_view',$data);
	}

	public function view()
	{
        $data = array(
            'view_name' => "employee/aisd/users/area_head_view",
			'page_name' => "Area Marketing Head",
			'employee_id' => $this->uri->segment(6)
        );
        
		$this->load->view('employee/aisd/account_view',$data);
	}

}