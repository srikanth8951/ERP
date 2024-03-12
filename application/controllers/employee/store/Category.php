<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Category extends CI_Controller {

	public function index()
	{
        $data = array(
            'view_name' => "employee/store/category_view",
			'page_name' => "Category",
        );
        
		$this->load->view('employee/store/account_view',$data);
	}
    
    
}
