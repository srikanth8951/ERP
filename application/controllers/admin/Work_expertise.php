<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Work_expertise extends CI_Controller {

	public function index()
	{
        $data = array(
            'view_name' => "admin/work_expertise",
			'page_name' => "Work Expertise",
        );
        
		$this->load->view('admin/account_view',$data);
	}
    
    
}
