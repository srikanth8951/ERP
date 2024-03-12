<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Designation extends CI_Controller {

	public function index()
	{
        $data = array(
            'view_name' => "admin/designation_view",
			'page_name' => "Designation",
        );
        
		$this->load->view('admin/account_view',$data);
	}
    
    
}
