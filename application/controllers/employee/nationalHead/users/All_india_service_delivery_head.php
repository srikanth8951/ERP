<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class All_india_service_delivery_head extends CI_Controller {

	public function index()
	{
        $data = array(
            'view_name' => "employee/nationalHead/users/aisd_head_view",
			'page_name' => "All India Service Delivery Head",
			'employee_id' => $this->uri->segment(4)
        );
        
		$this->load->view('employee/nationalHead/account_view',$data);
	}

	// public function view()
	// {
    //     $data = array(
    //         'view_name' => "admin/employee/aisd_head_view",
	// 		'page_name' => "All India Service Delivery Head",
	// 		'employee_id' => $this->uri->segment(4)
    //     );
        
	// 	$this->load->view('admin/account_view',$data);
	// }
  
}
