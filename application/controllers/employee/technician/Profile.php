<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Profile extends CI_Controller
{

	public function index()
	{
		$data = array(
			'view_name' => "employee/technician/user_profile_view",
			'page_name' => "Profile",
		);

		$this->load->view('employee/technician/account_view', $data);
	}
}
