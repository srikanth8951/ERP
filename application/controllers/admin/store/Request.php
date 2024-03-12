<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Request extends CI_Controller {

	public function index()
	{
        $data = array(
            'view_name' => "admin/store/request_list",
			'page_name' => "STORE REQUEST",
        );
        
		$this->load->view('admin/account_view',$data);
	}

    public function view()
    {
        $data = array(
            'view_name' => "admin/store/request_detail_view",
			'page_name' => "Store Request",
            'request_id' => $this->uri->segment(5)
        );
        
		$this->load->view('admin/account_view',$data);
    }
    
}
