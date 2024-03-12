<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Asset extends CI_Controller {

	public function index()
	{
        $data = array(
            'view_name' => "admin/assets/asset_list",
			'page_name' => "Asset",
        );
        
		$this->load->view('admin/account_view',$data);
	}

	public function view()
	{
        $data = array(
            'view_name' => "admin/assets/asset_view",
			'page_name' => "Asset",
			'view_type' => 'detail',
			'asset_id' => $this->uri->segment(4)
        );
        
		$this->load->view('admin/account_view',$data);
	}

    public function add()
	{
        $data = array(
            'view_name' => "admin/assets/asset_add_view",
			'page_name' => "Asset",
        );
        
		$this->load->view('admin/account_view',$data);
	}
}
