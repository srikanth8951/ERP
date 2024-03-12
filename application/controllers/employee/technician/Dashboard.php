<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

	public function index()
	{
        $data = array(
            'view_name' => "employee/technician/dashboard_view",
			'page_name' => "Dashboard",
        );
        
		$this->load->view('employee/technician/account_view',$data);
	}
}
