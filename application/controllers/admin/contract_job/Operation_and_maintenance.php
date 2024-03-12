<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Operation_and_maintenance extends CI_Controller {

	public function index()
	{
        $data = array(
            'view_name' => "admin/contract_job/operation_and_maintenance_list",
			'page_name' => "O&M Jobs / Contracts",
        );
        
		$this->load->view('admin/account_view',$data);
	}

	public function view()
	{
        $data = array(
            'view_name' => "admin/contract_job/operation_and_maintenance_view",
			'page_name' => "O&M Jobs / Contracts",
			'view_type' => 'view',
			'contract_job_id' => $this->uri->segment(4)
        );
        
		$this->load->view('admin/account_view',$data);
	}

    public function add()
	{
        $data = array(
            'view_name' => "admin/contract_job/operation_and_maintenance_form",
			'page_name' => "O&M Jobs / Contracts",
			'view_type' => 'add'
        );
        
		$this->load->view('admin/account_view',$data);
	}

	public function update()
	{
        $data = array(
            'view_name' => "admin/contract_job/operation_and_maintenance_form",
			'page_name' => "O&M Jobs / Contracts",
			'view_type' => 'update',
			'contract_job_id' => $this->uri->segment(4)
        );
        
		$this->load->view('admin/account_view',$data);
	}

	public function renew()
	{
        $data = array(
            'view_name' => "admin/contract_job/operation_and_maintenance_form",
			'page_name' => "O&M Jobs / Contracts",
			'view_type' => 'renew',
			'contract_job_id' => $this->uri->segment(4)
        );
        
		$this->load->view('admin/account_view',$data);
	}
}
