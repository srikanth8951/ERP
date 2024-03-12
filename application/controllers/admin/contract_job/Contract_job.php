<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Contract_job extends CI_Controller {

	public function index()
	{
        $data = array(
            'view_name' => "admin/contract_job_list",
			'page_name' => "Jobs / Contracts",
        );
        
		$this->load->view('admin/account_view',$data);
	}

	public function view()
	{
        $data = array(
            'view_name' => "admin/contract_job_view",
			'page_name' => "Jobs / Contracts",
			'view_type' => 'view',
			'contract_job_id' => $this->uri->segment(4)
        );
        
		$this->load->view('admin/account_view',$data);
	}

    public function add()
	{
        $data = array(
            'view_name' => "admin/contract_job_form",
			'page_name' => "Jobs / Contracts",
			'view_type' => 'add'
        );
        
		$this->load->view('admin/account_view',$data);
	}

	public function update()
	{
        $data = array(
            'view_name' => "admin/contract_job_form",
			'page_name' => "Jobs / Contracts",
			'view_type' => 'update',
			'contract_job_id' => $this->uri->segment(4)
        );
        
		$this->load->view('admin/account_view',$data);
	}

	public function renew()
	{
        $data = array(
            'view_name' => "admin/contract_job_form",
			'page_name' => "Jobs / Contracts",
			'view_type' => 'renew',
			'contract_job_id' => $this->uri->segment(4)
        );
        
		$this->load->view('admin/account_view',$data);
	}
}
