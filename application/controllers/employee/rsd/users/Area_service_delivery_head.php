<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Area_service_delivery_head extends CI_Controller {

	public function index()
	{
        $data = array(
            'view_name' => "employee/rsd/users/asd_head_list",
			'page_name' => "Area Service Delivery Head",
        );
        
		$this->load->view('employee/rsd/account_view',$data);
	}

	public function view()
	{
        $data = array(
            'view_name' => "employee/rsd/users/asd_head_view",
			'page_name' => "Area Service Delivery Head",
			'employee_id' => $this->uri->segment(6)
        );
        
		$this->load->view('employee/rsd/account_view',$data);
	}

}
