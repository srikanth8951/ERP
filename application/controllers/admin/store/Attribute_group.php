<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Attribute_group extends CI_Controller {

	public function index()
	{
        $data = array(
            'view_name' => "admin/store/attribute_group_view",
			'page_name' => "Attribute Group",
        );
        
		$this->load->view('admin/account_view',$data);
	}
    
    
}
