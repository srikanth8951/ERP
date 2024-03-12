<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Client_account_manager extends CI_Controller {

	public function index()
	{
        $data = array(
            'view_name' => "admin/employee/cam_list",
			'page_name' => "Client Account Manger",
        );
        
		$this->load->view('admin/account_view',$data);
	}

	public function view()
	{
        $data = array(
            'view_name' => "admin/employee/cam_view",
			'page_name' => "Client Account Manger",
			'employee_id' => $this->uri->segment(4)
        );
        
		$this->load->view('admin/account_view',$data);
	}

    public function add()
	{
        $data = array(
            'view_name' => "admin/employee/cam_form",
			'page_name' => "Client Account Manger",
			'view_type' => 'add'
        );
        
		$this->load->view('admin/account_view',$data);
	}

	public function edit()
	{
        $data = array(
            'view_name' => "admin/employee/cam_form",
			'page_name' => "Client Account Manger",
			'view_type' => 'edit',
			'employee_id' => $this->uri->segment(4)
        );
        
		$this->load->view('admin/account_view',$data);
	}
}
