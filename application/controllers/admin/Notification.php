<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Notification extends CI_Controller {

	public function index()
	{
        $data = array(
            'view_name' => "admin/notification_view",
			'page_name' => "Notifications",
        );
        
		$this->load->view('admin/header_footer_view',$data);
	}

    
}
