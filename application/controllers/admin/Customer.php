<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customer extends CI_Controller {

	public function index()
	{
        $data = array(
            'view_name' => "admin/customer_list",
			'page_name' => "Customers",
        );
        
		$this->load->view('admin/account_view',$data);
	}

	public function view()
	{
        $data = array(
            'view_name' => "admin/customer_view",
			'page_name' => "Customers",
			'view_type' => 'view',
			'customer_id' => $this->uri->segment(4)
        );
        
		$this->load->view('admin/account_view',$data);
	}

    public function add()
	{
        $data = array(
            'view_name' => "admin/customer_form",
			'page_name' => "Customers",
			'view_type' => 'add',
			'customer_id' => $this->uri->segment(4)
        );
        
		$this->load->view('admin/account_view',$data);
	}

	public function edit()
	{
        $data = array(
            'view_name' => "admin/customer_form",
			'page_name' => "Customers",
			'view_type' => 'edit',
			'customer_id' => $this->uri->segment(4)
        );
        
		$this->load->view('admin/account_view',$data);
	}
    
}
