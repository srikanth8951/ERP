<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Complaint extends CI_Controller {

	public function index()
	{
        $data = array(
            'view_name' => "admin/complaint_view",
			'page_name' => "Complaints",
        );
        
		$this->load->view('admin/header_footer_view',$data);
	}

    public function status()
	{
        $data = array(
            'view_name' => "admin/complaint_status_view",
			'page_name' => "Complaints Status",
        );
        
		$this->load->view('admin/header_footer_view',$data);
	}

    public function view()
    {
        $data = array(
            'view_name' => "admin/complaint_detail_view",
			'page_name' => "Complaints",
        );
        
		$this->load->view('admin/header_footer_view',$data);
    }
    
}
