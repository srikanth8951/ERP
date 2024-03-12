<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Branch extends CI_Controller {

	public function index()
	{
        $data = array(
            'view_name' => "admin/branch_view",
			'page_name' => "Branch",
        );
        
		$this->load->view('admin/account_view',$data);
	}
    
    
}