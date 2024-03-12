<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Data_management_team extends CI_Controller {

	public function index()
	{
        $data = array(
            'view_name' => "admin/employee/dmt_list",
			'page_name' => "Data Management Team",
        );
        
		$this->load->view('admin/account_view',$data);
	}

	public function view()
	{
        $data = array(
            'view_name' => "admin/employee/dmt_view",
			'page_name' => "Data Management Team",
			'employee_id' => $this->uri->segment(4)
        );
        
		$this->load->view('admin/account_view',$data);
	}

    public function add()
	{
        $data = array(
            'view_name' => "admin/employee/dmt_form",
			'page_name' => "Data Management Team",
			'view_type' => 'add'
        );
        
		$this->load->view('admin/account_view',$data);
	}

	public function edit()
	{
        $data = array(
            'view_name' => "admin/employee/dmt_form",
			'page_name' => "Data Management Team",
			'view_type' => 'edit',
			'employee_id' => $this->uri->segment(4)
        );
        
		$this->load->view('admin/account_view',$data);
	}
}
