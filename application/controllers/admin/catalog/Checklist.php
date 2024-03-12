<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Checklist extends CI_Controller {

	/**
	 * Index Page for this controller.
	 */
	public function index()
	{
		$group = $this->uri->segment(4);
		$data = array(
            'view_name' => "admin/catalog/checklist_list",
			'page_name' => "Checklist",
			'group' => $group
        );

		if ($group) {
			$data['page_name'] = ucfirst($group) . ' Checklist';
		} else {
			$data['page_name'] = 'Checklist';
		}
        
		$this->load->view('admin/account_view', $data);
	}

	public function view()
	{
		$group = $this->uri->segment(4);
		$data = array(
            'view_name' => "admin/catalog/checklist_view",
			'group' => $group
        );
		if ($group) {
			$data['page_name'] = ucfirst($group) . ' Checklist';
			$data['checklist_id'] = (int)$this->uri->segment(6);
		} else {
			$data['page_name'] = 'Checklist';
			$data['checklist_id'] = (int)$this->uri->segment(5);
		}
		
		$this->load->view('admin/account_view', $data);
	}

}
