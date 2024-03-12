<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Contract_job extends CI_Controller {

	public function index()
	{
        $data = array(
            'view_name' => "customer/job/contract_job_list",
			'page_name' => "Jobs / Contracts",
        );
        
		$this->load->view('customer/account_view',$data);
	}

    public function add()
	{
        $data = array(
            'view_name' => "customer/job/contract_job_form",
			'page_name' => "Jobs / Contracts",
			'view_type' => 'add'
        );
        
		$this->load->view('customer/account_view',$data);
	}

	public function view()
	{
        $data = array(
            'view_name' => "employee/customer/job/contract_job_view",
			'page_name' => "Jobs / Contracts",
			'view_type' => 'view',
			'contract_job_id' => $this->uri->segment(5)
        );
        
		$this->load->view('employee/customer/account_view',$data);
	}
}
