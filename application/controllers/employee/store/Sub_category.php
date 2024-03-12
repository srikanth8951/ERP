<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sub_category extends CI_Controller {

	public function index()
	{
        $data = array(
            'view_name' => "employee/store/sub_category_view",
			'page_name' => "Sub Category",
        );
        
		$this->load->view('employee/store//account_view',$data);
	}
    
    
}
