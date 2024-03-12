<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Standard_operating_procedure extends CI_Controller {

	public function index()
	{
        $data = array(
            'view_name' => "admin/standard_operating_procedure",
			'page_name' => "Standard Operating Procedure [SOP]",
        );
        
		$this->load->view('admin/account_view',$data);
	}
    
    
}
