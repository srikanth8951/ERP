<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Products extends CI_Controller {

	public function index()
	{
        $data = array(
            'view_name' => "admin/store/product_list",
			'page_name' => "Spare Parts",
        );
        
		$this->load->view('admin/account_view',$data);
	}

    public function add()
	{
        $data = array(
            'view_name' => "admin/store/product_form",
			'page_name' => "Spare Parts",
			'view_type' => 'add',
        );
        
		$this->load->view('admin/account_view',$data);
	}

	public function edit()
	{
        $data = array(
            'view_name' => "admin/store/product_form",
			'page_name' => "Spare Parts",
			'view_type' => 'edit',
			'product_id' => $this->uri->segment(5)
        );

		$this->load->view('admin/account_view',$data);
	}
    
    
}
