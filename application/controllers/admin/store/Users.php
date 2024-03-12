<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Users extends CI_Controller
{

	public function index()
	{
		$data = array(
			'view_name' => "admin/store/user_list",
			'page_name' => "Store Users",
		);

		$this->load->view('admin/account_view', $data);
	}

	public function add()
	{
		$data = array(
			'view_name' => "admin/store/user_form",
			'page_name' => "Store Users",
			'view_type' => 'add',
		);

		$this->load->view('admin/account_view', $data);
	}

	public function view()
	{
		$data = array(
			'view_name' => "admin/store/user_view",
			'page_name' => "Data Management Team",
			'employee_id' => $this->uri->segment(5)
		);

		$this->load->view('admin/account_view', $data);
	}
	public function edit()
	{
		$data = array(
			'view_name' => "admin/store/user_form",
			'page_name' => "Data Management Team",
			'view_type' => 'edit',
			'employee_id' => $this->uri->segment(5)
		);

		$this->load->view('admin/account_view', $data);
	}
}
