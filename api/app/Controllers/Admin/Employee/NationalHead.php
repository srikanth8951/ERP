<?php

namespace App\Controllers\Admin\Employee;

use CodeIgniter\RESTful\ResourceController;
use App\Libraries\Auth\User as AuthUser;
use App\Models\Admin\UserModel;
use App\Models\Admin\EmployeeModel;

class NationalHead extends ResourceController
{
	protected $empType = 'national_head';

	public function __construct()
	{
		helper('user');
	}

	public function index()
	{
		$this->getEmployee();
	}

	public function getEmployee()
	{
		$response = array();
		$this->validatePermission('view_national_head');	// Check permission

		$modelEmployee = new EmployeeModel(); // Load model

		$user_type = getUserTypeByCode($this->empType);

		$filter_data = array(
			'removed' => 0,
			'status' => 1
		);
		$nationalHead = $modelEmployee->getEmployeeByType($user_type['type_id'], $filter_data);
		if ($nationalHead) {
			$response['status'] = 'success';
			$response['message'] = lang('Employee.NationalHead.success_detail');
			$response['employee'] = [
				'type' => $this->empType,
				'data' => $nationalHead
			];
			return $this->setResponseFormat("json")->respond($response, 200);
		} else {
			$response['status'] = 'error';
			$response['message'] = lang('Employee.NationalHead.error_detail');
			return $this->setResponseFormat("json")->respond($response, 201);
		}
	}

	public function addEmployee()
	{
		$response = array();
		$this->validatePermission('add_national_head');	// Check permission

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

			$user_type = getUserTypeByCode($this->empType);

			$filter_data = array(
				'removed' => 0,
				'status' => 1
			);
			$nationalHead = $modelEmployee->getEmployeeByType($user_type['type_id'], $filter_data);
			if ($nationalHead) {
				$response = [
					'status' => 'error',
					'message' => lang('Employee.NationalHead.error_exist')
				];
				return $this->setResponseFormat("json")->respond($response, 201);
			} else {
				$nationalHead_data = array_merge($this->request->getPost(null), [
					'user_type' => $user_type['type_id']
				]);

				$filter_data = array(
					'removed' => 0,
					'status' => 1,
					'username' => $nationalHead_data['username'],
					'email' => $nationalHead_data['email']
				);

				$nationalHead = $modelEmployee->getEmployeeByValidation($filter_data);
				if ($nationalHead) {
					$response['status'] = 'error';
					$response['message'] = lang('Employee.NationalHead.error_exist');
					return $this->setResponseFormat("json")->respond($response, 201);
				} else {
					$add = $modelEmployee->addEmployee($nationalHead_data);
					if ($add) {
						$response['status'] = 'success';
						$response['message'] = lang('Employee.NationalHead.success_add');
						return $this->setResponseFormat("json")->respond($response, 200);
					} else {
						$response['status'] = 'error';
						$response['message'] = lang('Employee.NationalHead.error_add');
						return $this->setResponseFormat("json")->respond($response, 201);
					}
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

		$this->validatePermission('edit_national_head');	// Check permission
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

			$nationalHead_data = array_merge($this->request->getPost(null), [
				'user_type' => $user_type['type_id']
			]);

			$employee_id = $this->request->getVar('employee_id');
			$user_type = getUserTypeByCode($this->empType);

			$filter_data = array(
				'removed' => 0,
				'status' => 1
			);
			$nationalHead_detail = $modelEmployee->getEmployeeByType($user_type['type_id'], $filter_data);
			if ($nationalHead_detail) {
				$filter_data = array(
					'removed' => 0,
					'status' => 1,
					'username' => $nationalHead_data['username'],
					'email' => $nationalHead_data['email'],
					'except' => [$nationalHead_detail->user_id]
				);

				$nationalHead = $modelEmployee->getEmployeeByValidation($filter_data);
				if ($nationalHead) {
					$response['status'] = 'error';
					$response['message'] = lang('Employee.NationalHead.error_exist');
					return $this->setResponseFormat("json")->respond($response, 201);
				} else {
					$edit = $modelEmployee->editEmployee($employee_id, $nationalHead_data);
					if ($edit) {
						$response['status'] = 'success';
						$response['message'] = lang('Employee.NationalHead.success_edit');
						return $this->setResponseFormat("json")->respond($response, 200);
					} else {
						$response['status'] = 'error';
						$response['message'] = lang('Employee.NationalHead.error_edit');
						return $this->setResponseFormat("json")->respond($response, 201);
					}
				}
			} else {
				$response['status'] = 'error';
				$response['message'] = lang('Employee.NationalHead.error_detail');
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

		$this->validatePermission('edit_national_head');	// Check permission
		$modelUsers = new UserModel(); // Load model
		$modelEmployee = new EmployeeModel();

		$employee_id = $this->request->getVar('employee_id');
		$nationalHead = $modelEmployee->getEmployee($employee_id);
		if ($nationalHead) {
			//$remove =$modelEmployee->removeEmployee($employee_id);
			$remove = $modelEmployee->removeEmployee($employee_id, $nationalHead->user_id);

			if ($remove) {
				$response['status'] = 'success';
				$response['message'] = lang('Employee.NationalHead.success_removed');
				return $this->setResponseFormat("json")->respond($response, 200);
			} else {
				$response['status'] = 'error';
				$response['message'] = lang('Employee.NationalHead.error_removed');
				return $this->setResponseFormat("json")->respond($response, 201);
			}
		} else {
			$response['status'] = 'error';
			$response['message'] = lang('Employee.NationalHead.error_detail');
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

	public function autocomplete()
	{
		$this->validatePermission('view_national_head');	// Check permission
		$modelEmployee = new EmployeeModel(); // Load model
		$user_type = getUserTypeByCode($this->empType);
		$filter_data = array(
			'removed' => 0,
			'user_type' => $user_type['type_id'],
			'status' => 1
		);
		$nationalHeadArray = array();
		$nationalHeads = $modelEmployee->getEmployees($filter_data);
		if ($nationalHeads) {
			foreach ($nationalHeads as $nationalHead) {
				$nationalHeadArray[] = array(
					'id' => (int)$nationalHead->nationalHead_id,
					'name' => html_entity_decode($nationalHead->first_name . '' . $nationalHead->last_name),
				);
			}
		}
		$response = array(
			'status' => 'success',
			'employee' => [
				'type' => $this->empType,
				'data' => $nationalHeadArray
			]
		);

		return $this->setResponseFormat("json")->respond($response, 200);
	}
}
