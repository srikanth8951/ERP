<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Engineer extends CI_Controller {

	public function index()
	{
        $data = array(
            'view_name' => "employee/rsd/users/engineer_list",
			'page_name' => "Engineers",
        );
        
		$this->load->view('employee/rsd/account_view',$data);
	}

	public function view()
	{
        $data = array(
            'view_name' => "employee/rsd/users/engineer_view",
			'page_name' => "Area Marketing Head",
			'employee_id' => $this->uri->segment(6)
        );
        
		$this->load->view('employee/rsd/account_view',$data);
	}

}
