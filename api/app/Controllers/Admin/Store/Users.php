<?php

namespace App\Controllers\Admin\Store;

use CodeIgniter\RESTful\ResourceController;
use App\Libraries\Auth\User as AuthUser;
use App\Models\Admin\UserModel;
use App\Models\Admin\EmployeeModel;

class Users extends ResourceController
{
	protected $empType = 'store';

	public function __construct()
	{
		helper('user');
	}

	public function index()
	{

		$this->validatePermission('view_StoreUser');	// Check permission
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
			'is_exist' => 0,
			'user_type' => $user_type['type_id']
		);

		$total_region_heads	= $modelEmployee->getTotalEmployees($filter_data);
		$region_heads	= $modelEmployee->getEmployees($filter_data);

		if ($region_heads) {
			$response = array(
				'status' => 'success',
				'message' => lang('Employee.StoreUser.success_list'),
				'employees' => [
					'type' => $this->empType,
					'data' => $region_heads,
					'pagination' => array(
						'total' => (int)$total_region_heads,
						'length' => $limit,
						'start' => $start,
						'records' => count($region_heads)
					)
				]
			);
			return $this->setResponseFormat("json")->respond($response, 200);
		} else {
			$response = array(
				'status' => 'error',
				'message' => lang('Employee.StoreUser.error_list')
			);
			return $this->setResponseFormat("json")->respond($response, 201);
		}
	}

	public function getEmployee()
	{
		$response = array();
		$this->validatePermission('view_StoreUser');	// Check permission

		$modelEmployee = new EmployeeModel(); // Load model

		$employee_id = $this->request->getVar('employee_id');
		$StoreUser = $modelEmployee->getEmployee($employee_id);
		if ($StoreUser) {
			$response['status'] = 'success';
			$response['message'] = lang('Employee.StoreUser.success_detail');
			$response['employee'] = [
				'type' => $this->empType,
				'data' => $StoreUser
			];
			return $this->setResponseFormat("json")->respond($response, 200);
		} else {
			$response['status'] = 'error';
			$response['message'] = lang('Employee.StoreUser.error_detail');
			return $this->setResponseFormat("json")->respond($response, 201);
		}
	}

	public function addEmployee()
	{
		$response = array();
		$this->validatePermission('add_StoreUser');	// Check permission

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

			$StoreUser_data = array_merge($this->request->getPost(null), [
				'user_type' => $user_type['type_id'],
				'is_exist' => 0
			]);

			$filter_data = array(
				'removed' => 0,
				'status' => 1,
				'username' => $StoreUser_data['username'],
				'email' => $StoreUser_data['email']
			);

			$StoreUser = $modelEmployee->getEmployeeByValidation($filter_data);
			if ($StoreUser) {
				$response['status'] = 'error';
				$response['message'] = lang('Employee.StoreUser.error_exist');
				return $this->setResponseFormat("json")->respond($response, 201);
			} else {
				$add = $modelEmployee->addEmployee($StoreUser_data);
				if ($add) {
					$response['status'] = 'success';
					$response['message'] = lang('Employee.StoreUser.success_add');
					return $this->setResponseFormat("json")->respond($response, 200);
				} else {
					$response['status'] = 'error';
					$response['message'] = lang('Employee.StoreUser.error_add');
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

		$this->validatePermission('edit_StoreUser');	// Check permission
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

			$StoreUser_data = array_merge($this->request->getPost(null), [
				'user_type' => $user_type['type_id']
			]);

			$employee_id = $this->request->getVar('employee_id');

			$StoreUser_detail = $modelEmployee->getEmployee($employee_id);
			if ($StoreUser_detail) {
				$filter_data = array(
					'removed' => 0,
					'status' => 1,
					'username' => $StoreUser_data['username'],
					'email' => $StoreUser_data['email'],
					'except' => [$StoreUser_detail->user_id]
				);

				$StoreUser = $modelEmployee->getEmployeeByValidation($filter_data);
				if ($StoreUser) {
					$response['status'] = 'error';
					$response['message'] = lang('Employee.StoreUser.error_exist');
					return $this->setResponseFormat("json")->respond($response, 201);
				} else {
					$edit = $modelEmployee->editEmployee($employee_id, $StoreUser_data);
					if ($edit) {
						$response['status'] = 'success';
						$response['message'] = lang('Employee.StoreUser.success_edit');
						return $this->setResponseFormat("json")->respond($response, 200);
					} else {
						$response['status'] = 'error';
						$response['message'] = lang('Employee.StoreUser.error_edit');
						return $this->setResponseFormat("json")->respond($response, 201);
					}
				}
			} else {
				$response['status'] = 'error';
				$response['message'] = lang('Employee.StoreUser.error_detail');
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

		$this->validatePermission('edit_StoreUser');	// Check permission
		$modelUsers = new UserModel(); // Load model
		$modelEmployee = new EmployeeModel();

		$employee_id = $this->request->getVar('employee_id');
		$StoreUser = $modelEmployee->getEmployee($employee_id);
		if ($StoreUser) {
			//$remove =$modelEmployee->removeEmployee($employee_id);
			$remove = $modelEmployee->removeEmployee($employee_id, $StoreUser->user_id);

			if ($remove) {
				$response['status'] = 'success';
				$response['message'] = lang('Employee.StoreUser.success_removed');
				return $this->setResponseFormat("json")->respond($response, 200);
			} else {
				$response['status'] = 'error';
				$response['message'] = lang('Employee.StoreUser.error_removed');
				return $this->setResponseFormat("json")->respond($response, 201);
			}
		} else {
			$response['status'] = 'error';
			$response['message'] = lang('Employee.StoreUser.error_detail');
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
		$this->validatePermission('view_StoreUser');	// Check permission
		$modelEmployee = new EmployeeModel(); // Load model
		$user_type = getUserTypeByCode($this->empType);
		$filter_data = array(
			'removed' => 0,
			'user_type' => $user_type['type_id'],
			'status' => 1
		);
		$StoreUserArray = array();
		$StoreUsers = $modelEmployee->getEmployees($filter_data);
		if ($StoreUsers) {
			foreach ($StoreUsers as $StoreUser) {
				$StoreUserArray[] = array(
					'id' => (int)$StoreUser->StoreUser_id,
					'name' => html_entity_decode($StoreUser->first_name . '' . $StoreUser->last_name)
				);
			}
		}
		$response = array(
			'status' => 'success',
			'employee' => [
				'type' => $this->empType,
				'data' => $StoreUserArray
			]
		);

		return $this->setResponseFormat("json")->respond($response, 200);
	}

}
