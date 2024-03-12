<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vendor extends CI_Controller {

	public function index()
	{
        $data = array(
            'view_name' => "admin/vendor_list",
			'page_name' => "Vendors",
        );
        
		$this->load->view('admin/account_view',$data);
	}

	public function view()
	{
        $data = array(
            'view_name' => "admin/vendor_view",
			'page_name' => "Vendors",
			'view_type' => 'view',
			'vendor_id' => $this->uri->segment(4)
        );
        
		$this->load->view('admin/account_view',$data);
	}

    public function add()
	{
        $data = array(
            'view_name' => "admin/vendor_form",
			'page_name' => "Vendors",
			'view_type' => 'add',
			'vendor_id' => $this->uri->segment(4)
        );
        
		$this->load->view('admin/account_view',$data);
	}

	public function edit()
	{
        $data = array(
            'view_name' => "admin/vendor_form",
			'page_name' => "Vendors",
			'view_type' => 'edit',
			'vendor_id' => $this->uri->segment(4)
        );
        
		$this->load->view('admin/account_view',$data);
	}
    
}
