<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Technician extends CI_Controller {

	public function index()
	{
        $data = array(
            'view_name' => "admin/employee/technician_list",
			'page_name' => "Technician",
			'employee_id' => $this->uri->segment(4)
        );
        
		$this->load->view('admin/account_view',$data);
	}

	public function view()
	{
        $data = array(
            'view_name' => "admin/employee/technician_view",
			'page_name' => "Area Marketing Head",
			'employee_id' => $this->uri->segment(4)
        );
        
		$this->load->view('admin/account_view',$data);
	}

    public function add()
	{
        $data = array(
            'view_name' => "admin/employee/technician_form",
			'page_name' => "Technician",
			'view_type' => 'add',
        );
        
		$this->load->view('admin/account_view',$data);
	}

	public function edit()
	{
        $data = array(
            'view_name' => "admin/employee/technician_form",
			'page_name' => "Technician",
			'view_type' => 'edit',
			'employee_id' => $this->uri->segment(4)
        );
        
		$this->load->view('admin/account_view',$data);
	}
}
