<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Region extends CI_Controller {

	public function index()
	{
        $data = array(
            'view_name' => "admin/region_view",
			'page_name' => "Region",
        );
        
		$this->load->view('admin/account_view',$data);
	}
    
    
}
