<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Contract_job_log extends CI_Controller {

	public function index()
	{
        $data = array(
            'view_name' => "employee/dmt/job/contract_job_log_list",
			'page_name' => "Jobs / Contracts",
			'contract_job_id' => $this->uri->segment(4)
        );
        
		$this->load->view('employee/dmt/account_view',$data);
	}

	public function view()
	{
        $data = array(
            'view_name' => "employee/dmt/job/contract_job_log_view",
			'page_name' => "Jobs / Contracts",
			'view_type' => 'view',
			'contract_job_id' => $this->uri->segment(5),
			'back_contract_job_id' => $this->uri->segment(6)
        );
        
		$this->load->view('employee/dmt/account_view',$data);
	}

}
