<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Checklist extends CI_Controller {

	/**
	 * Index Page for this controller.
	 */
	public function index()
	{
		$data = array(
            'view_name' => "employee/regionHead/checklist_list",
			'page_name' => "Checklist",
        );
        
		$this->load->view('employee/regionHead/account_view', $data);
	}

	public function view()
	{
				
		$data = array(
            'view_name' => "employee/regionHead/checklist_view",
			'page_name' => "Checklist",
        );
		$data['checklist_id'] = $this->uri->segment(5);
        
		$this->load->view('employee/regionHead/account_view', $data);
	}

}
