<?php

namespace App\Controllers\Admin\Employee;

use CodeIgniter\RESTful\ResourceController;
use App\Libraries\Auth\User as AuthUser;
use App\Models\Admin\UserModel;
use App\Models\Admin\EmployeeModel;

class Manager extends ResourceController
{
	protected $empType = 'o&m_manager';

	public function __construct()
	{
		helper('user');
	}

	public function index()
	{

		$this->validatePermission('view_manager');	// Check permission
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

		$total_managers = $modelEmployee->getTotalEmployees($filter_data);
		$managers = $modelEmployee->getEmployees($filter_data);

		if ($managers) {
			$response = array(
				'status' => 'success',
				'message' => lang('Employee.Manager.success_list'),
				'employees' => [
					'type' => $this->empType,
					'data' => $managers,
					'pagination' => array(
						'total' => (int)$total_managers,
						'length' => $limit,
						'start' => $start,
						'records' => count($managers)
					)
				]
			);
			return $this->setResponseFormat("json")->respond($response, 200);
		} else {
			$response = array(
				'status' => 'error',
				'message' => lang('Employee.Manager.error_list')
			);
			return $this->setResponseFormat("json")->respond($response, 201);
		}
	}

	public function getEmployee()
	{
		$response = array();
		$this->validatePermission('view_manager');	// Check permission

		$modelEmployee = new EmployeeModel(); // Load model

		$employee_id = $this->request->getVar('employee_id');
		$manager = $modelEmployee->getEmployee($employee_id);
		if ($manager) {
			$response['status'] = 'success';
			$response['message'] = lang('Employee.Manager.success_detail');
			$response['employee'] = [
				'type' => $this->empType,
				'data' => $manager
			];
			return $this->setResponseFormat("json")->respond($response, 200);
		} else {
			$response['status'] = 'error';
			$response['message'] = lang('Employee.Manager.error_detail');
			return $this->setResponseFormat("json")->respond($response, 201);
		}
	}

	public function addEmployee()
	{
		$response = array();
		$this->validatePermission('add_manager');	// Check permission

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

			$manager_data = array_merge($this->request->getPost(null), [
				'user_type' => $user_type['type_id'],
				'is_exist' => 0
			]);

			$filter_data = array(
				'removed' => 0,
				'status' => 1,
				'username' => $manager_data['username'],
				'email' => $manager_data['email']
			);

			$manager = $modelEmployee->getEmployeeByValidation($filter_data);
			if ($manager) {
				$response['status'] = 'error';
				$response['message'] = lang('Employee.manager.error_exist');
				return $this->setResponseFormat("json")->respond($response, 201);
			} else {
				$add = $modelEmployee->addEmployee($manager_data);
				if ($add) {
					$response['status'] = 'success';
					$response['message'] = lang('Employee.Manager.success_add');
					return $this->setResponseFormat("json")->respond($response, 200);
				} else {
					$response['status'] = 'error';
					$response['message'] = lang('Employee.Manager.error_add');
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

		$this->validatePermission('edit_manager');	// Check permission
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
			$manager_data = array_merge($this->request->getPost(null), [
				'user_type' => $user_type['type_id']
			]);

			$employee_id = $this->request->getVar('employee_id');
			$manager = $modelEmployee->getEmployee($employee_id);
			if ($manager) {
				$filter_data = array(
					'removed' => 0,
					'status' => 1,
					'username' => $manager_data['username'],
					'email' => $manager_data['email'],
					'except' => [$manager->user_id]
				);

				$manager = $modelEmployee->getEmployeeByValidation($filter_data);
				if ($manager) {
					$response['status'] = 'error';
					$response['message'] = lang('Employee.manager.error_exist');
					return $this->setResponseFormat("json")->respond($response, 201);
				} else {
					$edit = $modelEmployee->editEmployee($employee_id, $manager_data);
					if ($edit) {
						$response['status'] = 'success';
						$response['message'] = lang('Employee.Manager.success_edit');
						return $this->setResponseFormat("json")->respond($response, 200);
					} else {
						$response['status'] = 'error';
						$response['message'] = lang('Employee.Manager.error_edit');
						return $this->setResponseFormat("json")->respond($response, 201);
					}
				}
			} else {
				$response['status'] = 'error';
				$response['message'] = lang('Employee.Manager.error_detail');
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

		$this->validatePermission('edit_manager');	// Check permission
		$modelEmployee = new EmployeeModel(); // Load model

		$employee_id = $this->request->getVar('employee_id');
		$manager = $modelEmployee->getEmployee($employee_id);
		if ($manager) {
			$remove = $modelEmployee->removeEmployee($employee_id);
			if ($remove) {
				$response['status'] = 'success';
				$response['message'] = lang('Employee.Manager.success_removed');
				return $this->setResponseFormat("json")->respond($response, 200);
			} else {
				$response['status'] = 'error';
				$response['message'] = lang('Employee.Manager.error_removed');
				return $this->setResponseFormat("json")->respond($response, 201);
			}
		} else {
			$response['status'] = 'error';
			$response['message'] = lang('Employee.Manager.error_detail');
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
		$this->validatePermission('view_manager');	// Check permission
		$modelEmployee = new EmployeeModel(); // Load model
		$user_type = getUserTypeByCode($this->empType);
		$filter_data = array(
			'removed' => 0,
			'user_type' => $user_type['type_id'],
			'status' => 1
		);
		$managerArray = array();
		$managers = $modelEmployee->getEmployees($filter_data);
		if ($managers) {
			foreach ($managers as $manager) {
				$managerArray[] = array(
					'id' => (int)$manager->manager_id,
					'name' => html_entity_decode($manager->first_name . '' . $manager->last_name)
				);
			}
		}
		$response = array(
			'status' => 'success',
			'employee' => [
				'type' => $this->empType,
				'data' => $managerArray
			]
		);

		return $this->setResponseFormat("json")->respond($response, 200);
	}
}
