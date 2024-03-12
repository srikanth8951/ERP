<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Area extends CI_Controller {

	public function index()
	{
        $data = array(
            'view_name' => "admin/area_view",
			'page_name' => "City",
        );
        
		$this->load->view('admin/account_view',$data);
	}
    
    
}
