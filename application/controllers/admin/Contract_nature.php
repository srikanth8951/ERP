<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Contract_nature extends CI_Controller {

	public function index()
	{
        $data = array(
            'view_name' => "admin/contract_nature",
			'page_name' => "Nature of Contract",
        );
        
		$this->load->view('admin/account_view',$data);
	}
    
    
}
