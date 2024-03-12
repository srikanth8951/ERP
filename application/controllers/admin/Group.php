<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Group extends CI_Controller {

	public function index()
	{
        $data = array(
            'view_name' => "admin/assets/asset_group_view",
			'page_name' => "Asset Group",
        );
        
		$this->load->view('admin/account_view',$data);
	}

	public function subGroup()
	{
        $data = array(
            'view_name' => "admin/assets/asset_subgroup_view",
			'page_name' => "Asset Sub Group",
        );
        
		$this->load->view('admin/account_view',$data);
	}
    
    
}
