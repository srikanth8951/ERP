<?php

namespace App\Controllers\Employee\rsd\Users;

use CodeIgniter\RESTful\ResourceController;
use App\Libraries\Auth\User as AuthUser;
use App\Libraries\Auth\Employee as AuthEmployee;
use App\Models\Employee\EmployeeModel;

class AreaHead extends ResourceController
{
	protected $userType = 'area_head';
	protected $empType = 'rsd_head';
	protected $employeeId;

	public function __construct()
	{
		helper('user');
	}

	public function index()
	{

		$this->validatePermission('view_areaHead');	// Check permission

		if (!$this->isEmployee()) {
			$response = array(
				'status' => 'error',
				'message' => lang('Common.error_employee_login')
			);

			return $this->setResponseFormat("json")->respond($response, 201);
		}

		$modelEmployee = new EmployeeModel(); // Load model
		$empDetail = $modelEmployee->getEmployee($this->employeeId);

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

		$user_type = getUserTypeByCode($this->userType);

		$filter_data = array(
			'removed' => 0,
			'search' => $search,
			'start' => ($start - 1),
			'limit' => $limit,
			'sort' => $sort,
			'order' => $order,
			'is_exist' => 0,
			'user_type' => $user_type['type_id'],
			'region_id' => $empDetail->region_id,
		);

		$total_areaHeads = $modelEmployee->getTotalEmployees($filter_data);
		$areaHeads = $modelEmployee->getEmployees($filter_data);

		if ($areaHeads) {
			$response = array(
				'status' => 'success',
				'message' => lang('Employee.AreaHead.success_list'),
				'employees' => [
					'type' => $this->empType,
					'data' => $areaHeads,
					'pagination' => array(
						'total' => (int)$total_areaHeads,
						'length' => $limit,
						'start' => $start,
						'records' => count($areaHeads)
					)
				]
			);
			return $this->setResponseFormat("json")->respond($response, 200);
		} else {
			$response = array(
				'status' => 'error',
				'message' => lang('Employee.AreaHead.error_list')
			);
			return $this->setResponseFormat("json")->respond($response, 201);
		}
	}

	public function getEmployee()
	{
		$response = array();
		$this->validatePermission('view_areaHead');	// Check permission

		if (!$this->isEmployee()) {
			$response = array(
				'status' => 'error',
				'message' => lang('Common.error_employee_login')
			);

			return $this->setResponseFormat("json")->respond($response, 201);
		}

		$modelEmployee = new EmployeeModel(); // Load model

		$areaHead_id = $this->request->getVar('employee_id');
		$areaHead = $modelEmployee->getEmployee($areaHead_id);
		if ($areaHead) {
			$response['status'] = 'success';
			$response['message'] = lang('Employee.AreaHead.success_detail');
			$response['employee'] = [
				'type' => $this->empType,
				'data' => $areaHead
			];
			return $this->setResponseFormat("json")->respond($response, 200);
		} else {
			$response['status'] = 'error';
			$response['message'] = lang('Employee.AreaHead.error_detail');
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

	protected function isEmployee()
    {
        $this->userId = AuthUser::getId();
        if (AuthEmployee::isValid($this->empType)) {
            $this->employeeId = AuthEmployee::getId();
        }
        return $this->employeeId;
    }

}
