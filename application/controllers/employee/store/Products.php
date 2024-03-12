<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Products extends CI_Controller {

	public function index()
	{
        $data = array(
            'view_name' => "employee/store/product_list",
			'page_name' => "Products",
        );
        
		$this->load->view('employee/store/account_view',$data);
	}

    public function add()
	{
        $data = array(
            'view_name' => "employee/store/product_form",
			'page_name' => "Products",
			'view_type' => 'add',
        );
        
		$this->load->view('employee/store/account_view',$data);
	}

	public function edit()
	{
        $data = array(
            'view_name' => "employee/store/product_form",
			'page_name' => "Products",
			'view_type' => 'edit',
			'product_id' => $this->uri->segment(5)
        );

		$this->load->view('employee/store/account_view',$data);
	}
    
    
}
