<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customer_sector extends CI_Controller {

	public function index()
	{
        $data = array(
            'view_name' => "admin/customer_sector_view",
			'page_name' => "Customer Sector",
        );
        
		$this->load->view('admin/account_view',$data);
	}
    
    
}