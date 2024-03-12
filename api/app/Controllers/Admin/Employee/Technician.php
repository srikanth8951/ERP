<?php

namespace App\Controllers\Admin\Employee;

use CodeIgniter\RESTful\ResourceController;
use App\Libraries\Auth\User as AuthUser;
use App\Models\Admin\UserModel;
use App\Models\Admin\EmployeeModel;

class Technician extends ResourceController
{
	protected $empType = 'technician';

	public function __construct()
	{
		helper('user');
	}

	public function index()
	{

		$this->validatePermission('view_technician');	// Check permission
		$modelEmployee = new EmployeeModel(); // Load model

		$start = $this->request->getGet('start');
		if ($start) {
			$start = (int)$start;
		} else {
			$start = 1;
		}

		$length = $this->request->getGet('length');
		if ($length) {
			$limit = (int)$length;
		} else {
			$limit = 10;
		}

		$search = $this->request->getGet('search');
		if ($search) {
			$search = $search;
		} else {
			$search = '';
		}

		$sort = $this->request->getGet('sort_column');
		if ($sort) {
			$sort = $sort;
		} else {
			$sort = '';
		}

		$order = $this->request->getGet('sort_order');
		if ($order) {
			$order = $order;
		} else {
			$order = '';
		}

		$user_type = getUserTypeByCode($this->empType);

		$filter_data = array(
			'removed' => 0,
			'search' => $search,
			'start' => ($start - 1),
			'limit' => $limit,
			'sort' => $sort,
			'order' => $order,
			'user_type' => $user_type['type_id']
		);

		$total_technicians = $modelEmployee->getTotalEmployees($filter_data);
		$technicians = $modelEmployee->getEmployees($filter_data);

		if ($technicians) {
			$response = array(
				'status' => 'success',
				'message' => lang('Employee.Technician.success_list'),
				'employees' => [
					'type' => $this->empType,
					'data' => $technicians,
					'pagination' => array(
						'total' => (int)$total_technicians,
						'length' => $limit,
						'start' => $start,
						'records' => count($technicians)
					)
				]
			);
			return $this->setResponseFormat("json")->respond($response, 200);
		} else {
			$response = array(
				'status' => 'error',
				'message' => lang('Employee.Technician.error_list')
			);
			return $this->setResponseFormat("json")->respond($response, 201);
		}
	}

	public function getEmployee()
	{
		$response = array();
		$this->validatePermission('view_technician');	// Check permission

		$modelEmployee = new EmployeeModel(); // Load model

		$employee_id = $this->request->getVar('employee_id');
		$technician = $modelEmployee->getEmployee($employee_id);
		if ($technician) {
			$response['status'] = 'success';
			$response['message'] = lang('Employee.Technician.success_detail');
			$response['employee'] = [
				'type' => $this->empType,
				'data' => $technician
			];
			return $this->setResponseFormat("json")->respond($response, 200);
		} else {
			$response['status'] = 'error';
			$response['message'] = lang('Employee.Technician.error_detail');
			return $this->setResponseFormat("json")->respond($response, 201);
		}
	}

	public function addEmployee()
	{
		$response = array();
		$this->validatePermission('add_technician');	// Check permission

		$rules = [
			"first_name" => "required",
			"email" => "required|valid_email",
			"mobile" => "required|numeric"
		];

		$messages = [
			"first_name" => [
				"required" => "First name is required"
			],
			"email" => [
				"required" => "Email is required",
				"valid_emil" => "Invalid email"
			],
			"mobile" => [
				"required" => "First name is required",
				"numeric" => "Mobile number must be numeric"
			]
		];
		if ($this->validate($rules, $messages)) {

			$user_type = getUserTypeByCode($this->empType);
			$modelEmployee = new EmployeeModel(); // Load model

			$technician_data = array_merge($this->request->getPost(null), [
				'user_type' => $user_type['type_id'],
				'is_exist' => 0
			]);

			$filter_data = array(
				'removed' => 0,
				'status' => 1,
				'username' => $technician_data['username'],
				'email' => $technician_data['email']
			);

			$technician = $modelEmployee->getEmployeeByValidation($filter_data);
			if ($technician) {
				$response['status'] = 'error';
				$response['message'] = lang('Employee.Technician.error_exist');
				return $this->setResponseFormat("json")->respond($response, 201);
			} else {
				$add = $modelEmployee->addEmployee($technician_data);
				if ($add) {
					$response['status'] = 'success';
					$response['message'] = lang('Employee.Technician.success_add');
					return $this->setResponseFormat("json")->respond($response, 200);
				} else {
					$response['status'] = 'error';
					$response['message'] = lang('Employee.Technician.error_add');
					return $this->setResponseFormat("json")->respond($response, 201);
				}
			}
		} else {
			$response = [
				'status' => 'error',
				'message' => $this->validator->getErrors()
			];
			return $this->setResponseFormat("json")->respond($response, 201);
		}
	}

	public function editEmployee()
	{
		$response = array();

		$this->validatePermission('edit_technician');	// Check permission
		$user_type = getUserTypeByCode($this->empType);

		$rules = [
			"first_name" => "required",
			"email" => "required|valid_email",
			"mobile" => "required|numeric"
		];

		$messages = [
			"first_name" => [
				"required" => "First name is required"
			],
			"email" => [
				"required" => "Email is required",
				"valid_emil" => "Invalid email"
			],
			"mobile" => [
				"required" => "First name is required",
				"numeric" => "Mobile number must be numeric"
			]
		];
		if ($this->validate($rules, $messages)) {
			$modelEmployee = new EmployeeModel(); // Load model

			$technician_data = array_merge($this->request->getPost(null), [
				'user_type' => $user_type['type_id']
			]);

			$employee_id = $this->request->getVar('employee_id');

			$technician	=	$modelEmployee->getEmployee($employee_id);
			if ($technician) {
				$filter_data = array(
					'removed' => 0,
					'status' => 1,
					'username' => $technician_data['username'],
					'email' => $technician_data['email'],
					'except' => [$technician->user_id]
				);

				$technician = $modelEmployee->getEmployeeByValidation($filter_data);
				if ($technician) {
					$response['status'] = 'error';
					$response['message'] = lang('Employee.Technician.error_exist');
					return $this->setResponseFormat("json")->respond($response, 201);
				} else {
					$edit = $modelEmployee->editEmployee($employee_id, $technician_data);
					if ($edit) {
						$response['status'] = 'success';
						$response['message'] = lang('Employee.Technician.success_edit');
						return $this->setResponseFormat("json")->respond($response, 200);
					} else {
						$response['status'] = 'error';
						$response['message'] = lang('Employee.Technician.error_edit');
						return $this->setResponseFormat("json")->respond($response, 201);
					}
				}
			} else {
				$response['status'] = 'error';
				$response['message'] = lang('Employee.Technician.error_detail');
				return $this->setResponseFormat("json")->respond($response, 201);
			}
		} else {
			$response = [
				'status' => 'error',
				'message' => $this->validator->getErrors()
			];
			return $this->setResponseFormat("json")->respond($response, 201);
		}
	}

	public function deleteEmployee()
	{
		$response = array();

		$this->validatePermission('edit_technician');	// Check permission
		$modelEmployee = new EmployeeModel(); // Load model

		$employee_id = $this->request->getVar('employee_id');
		$technician = $modelEmployee->getEmployee($employee_id);
		if ($technician) {
			$remove = $modelEmployee->removeEmployee($employee_id);
			if ($remove) {
				$response['status'] = 'success';
				$response['message'] = lang('Employee.Technician.success_removed');
				return $this->setResponseFormat("json")->respond($response, 200);
			} else {
				$response['status'] = 'error';
				$response['message'] = lang('Employee.Technician.error_removed');
				return $this->setResponseFormat("json")->respond($response, 201);
			}
		} else {
			$response['status'] = 'error';
			$response['message'] = lang('Employee.Technician.error_detail');
			return $this->setResponseFormat("json")->respond($response, 201);
		}
	}

	public function setEmployeeStatus()
	{
		$response = array();

		//$this->validatePermission('edit_AISDHead');	// Check permission
		$modelUsers = new UserModel(); // Load model
		$modelEmployee = new EmployeeModel();

		$employee_id = $this->request->getVar('employee_id');
		$status = $this->request->getVar('status');
		$AISDHead = $modelEmployee->getEmployee($employee_id);
		if ($AISDHead) {
			$remove = $modelEmployee->setEmployeeStatus($employee_id, $status);
			if ($remove) {
				$response['status'] = 'success';
				$response['message'] = lang('Employee.AISDHead.success_status');
				return $this->setResponseFormat("json")->respond($response, 200);
			} else {
				$response['status'] = 'error';
				$response['message'] = lang('Employee.AISDHead.error_removed');
				return $this->setResponseFormat("json")->respond($response, 201);
			}
		} else {
			$response['status'] = 'error';
			$response['message'] = lang('Employee.AISDHead.error_detail');
			return $this->setResponseFormat("json")->respond($response, 201);
		}
	}

	protected function validatePermission($permission_name)
	{
		$permission = AuthUser::checkPermission($permission_name);
		if (!$permission) {
			$response = array(
				'status' => 'error',
				'message' => lang('Common.error_permission')
			);

			return $this->setResponseFormat("json")->respond($response, 201);
		}
	}

	public function autocomplete()
	{
		$this->validatePermission('view_technician');	// Check permission
		$modelEmployee = new EmployeeModel(); // Load model
		$user_type = getUserTypeByCode($this->empType);
		$filter_data = array(
			'removed' => 0,
			'user_type' => $user_type['type_id'],
			'status' => 1
		);
		$technicianArray = array();
		$technicians = $modelEmployee->getEmployees($filter_data);
		if ($technicians) {
			foreach ($technicians as $technician) {
				$technicianArray[] = array(
					'id' => (int)$technician->technician_id,
					'name' => html_entity_decode($technician->first_name . '' . $technician->last_name)
				);
			}
		}
		$response = array(
			'status' => 'success',
			'employee' => [
				'type' => $this->empType,
				'data' => $technicianArray
			]
		);

		return $this->setResponseFormat("json")->respond($response, 200);
	}
}
