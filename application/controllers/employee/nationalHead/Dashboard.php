<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

	public function index()
	{
        $data = array(
            'view_name' => "employee/nationalHead/dashboard_view",
			'page_name' => "Dashboard",
        );
        
		$this->load->view('employee/nationalHead/account_view',$data);
	}
}