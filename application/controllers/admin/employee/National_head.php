<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class National_head extends CI_Controller {

	public function index()
	{
        $data = array(
            'view_name' => "admin/employee/national_head_view",
			'page_name' => "National Head",
			'employee_id' => $this->uri->segment(4)
        );
        
		$this->load->view('admin/account_view',$data);
	}

    public function add()
	{
        $data = array(
            'view_name' => "admin/employee/national_head_form",
			'page_name' => "National Head",
			'view_type' => 'add'
        );
        
		$this->load->view('admin/account_view',$data);
	}

	public function edit()
	{
        $data = array(
            'view_name' => "admin/employee/national_head_form",
			'page_name' => "National Head",
			'view_type' => 'edit',
			'employee_id' => $this->uri->segment(4)
        );
        
		$this->load->view('admin/account_view',$data);
	}
}
