<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Payment_terms extends CI_Controller {

	public function index()
	{
        $data = array(
            'view_name' => "admin/payment_terms",
			'page_name' => "Terms Of Payment",
        );
        
		$this->load->view('admin/account_view',$data);
	}
    
    
}
