<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

	public function index()
	{
        $data = array(
            'view_name' => "employee/areaHead/dashboard_view",
			'page_name' => "Dashboard",
        );
        
		$this->load->view('employee/areaHead/account_view',$data);
	}
}
