<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Regional_service_delivery_head extends CI_Controller {

	public function index()
	{
        $data = array(
            'view_name' => "employee/aisd/users/rsd_head_list",
			'page_name' => "Regional Service Delivery Head",
        );
        
		$this->load->view('employee/aisd/account_view',$data);
	}

	public function view()
	{
        $data = array(
            'view_name' => "employee/aisd/users/rsd_head_view",
			'page_name' => "Regional Service Delivery Head",
			'employee_id' => $this->uri->segment(6)
        );
        
		$this->load->view('employee/aisd/account_view',$data);
	}

}
