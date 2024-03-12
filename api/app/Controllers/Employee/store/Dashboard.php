<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

	
	function __construct(){
		parent::__construct();

		$this->api->init(); // Init api

		$this->load->library('users');	// Load users library
		$this->validate();

	}

	public function totals(){
		$this->validatePermission('view_setting');	// Check permission

		$total_clients = $this->model_client->getTotalClients();
		$total_employees = $this->model_employee->getTotalEmployees();
		$total_unassigned_tickets = $this->model_ticket->getTotalTickets(['filter_unassigned' => getSetting('ticket_unassigned_statuses')]);
		$total_assigned_tickets = $this->model_ticket->getTotalTickets(['filter_assigned' => getSetting('ticket_unassigned_statuses')]);

		$response = array(
			'status' => $this->lang->line('status_success'),
			'totals' => array(
				'clients' => $total_clients,
				'employees' => $total_employees,
				'ticket' => array(
					'all' => ($total_unassigned_tickets + $total_assigned_tickets),
					'assigned' => $total_assigned_tickets,
					'unassigned' => $total_unassigned_tickets
				)
				
				
			)
		);

		$this->api->response($response, HTTP_OK);
	}

	protected function validate(){
		$this->user_id = $this->users->isLogged();
		if(!$this->user_id){
			$response = array(
				'status' => $this->lang->line('status_error'),
				'message' => $this->lang->line('error_login')
			);
		
			$this->api->response($response, HTTP_UNAUTHORIZED);
		} else {
			$this->loadDetails();
		}
	} 

	protected function validatePermission($permission_name){
		$permission = $this->users->checkPermission($permission_name);
		if(!$permission){
			$response = array(
				'status' => $this->lang->line('status_error'),
				'message' => $this->lang->line('error_permission')
			);
		
			$this->api->response($response, HTTP_ACCEPTED);
		}
	}

	protected function loadDetails(){
		$this->load->model('admin/ticket_model', 'model_ticket');	// Load ticket model
		$this->load->model('admin/client_model', 'model_client');
		$this->load->model('admin/employee_model', 'model_employee');
		// $this->load->language('dashboard');	// Load language
	}

}
