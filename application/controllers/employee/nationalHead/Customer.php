<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customer extends CI_Controller {

	public function index()
	{
        $data = array(
            'view_name' => "employee/nationalHead/customer_list",
			'page_name' => "Customers",
        );
        
		$this->load->view('employee/nationalHead/account_view',$data);
	}

	public function view()
	{
        $data = array(
            'view_name' => "employee/nationalHead/customer_view",
			'page_name' => "Customers",
			'view_type' => 'view',
			'customer_id' => $this->uri->segment(5)
        );
        
		$this->load->view('employee/nationalHead/account_view',$data);
	}
    
}
