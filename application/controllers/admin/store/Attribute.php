<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Attribute extends CI_Controller {

	public function index()
	{
        $data = array(
            'view_name' => "admin/store/attribute_view",
			'page_name' => "Attribute",
        );
        
		$this->load->view('admin/account_view',$data);
	}
    
    
}
