<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Group_sub extends CI_Controller {

	public function index()
	{
        $data = array(
            'view_name' => "admin/asset_subgroup_view",
			'page_name' => "Asset Sub-Group",
        );
        
		$this->load->view('admin/header_footer_view',$data);
	}
    
    
}
