<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Category extends CI_Controller {

	public function index()
	{
        $data = array(
            'view_name' => "admin/store/category_view",
			'page_name' => "Category",
        );
        
		$this->load->view('admin/account_view',$data);
	}
    
    
}
