<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Area_head extends CI_Controller {

	public function index()
	{
        $data = array(
            'view_name' => "admin/employee/area_head_list",
			'page_name' => "Area Marketing Head",
        );
        
		$this->load->view('admin/account_view',$data);
	}

	public function view()
	{
        $data = array(
            'view_name' => "admin/employee/area_head_view",
			'page_name' => "Area Marketing Head",
			'employee_id' => $this->uri->segment(4)
        );
        
		$this->load->view('admin/account_view',$data);
	}

    public function add()
	{
        $data = array(
            'view_name' => "admin/employee/area_head_form",
			'page_name' => "Area Marketing Head",
			'view_type' => 'add'
        );
        
		$this->load->view('admin/account_view',$data);
	}

	public function edit()
	{
        $data = array(
            'view_name' => "admin/employee/area_head_form",
			'page_name' => "Area Marketing Head",
			'view_type' => 'edit',
			'employee_id' => $this->uri->segment(4)
        );
        
		$this->load->view('admin/account_view',$data);
	}
}
