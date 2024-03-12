<?php

namespace App\Controllers\Customer;

use CodeIgniter\RESTful\ResourceController;
use App\Libraries\Auth\User as AuthUser;
use App\Models\Customer\ContractJobModel;
use App\Models\Customer\CustomerModel;

class ContractJob extends ResourceController
{
	protected $empType = 'customer';
	
	public function __construct()
	{
		helper('user');
	}
	
    public function index()
	{
		$this->validatePermission('view_contract_job');	// Check permission
		$modelContractJob = new ContractJobModel(); // Load model
		$modelCustomer = new CustomerModel(); // Load model

		$user_type = getUserTypeByCode($this->empType);
		$userLoginId = AuthUser::getId();
		$empId = $modelCustomer->getEmployeeByID($userLoginId);

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


		$filter_data = array(
			'removed' => 0,
			'search' => $search,
			'start' => ($start - 1),
			'limit' => $limit,
			'sort' => $sort,
			'order' => $order,
			'region' => $empId->region_id
		);

		$total_contract_jobs = $modelContractJob->getTotalContractJobs($filter_data);
		$jobs = $modelContractJob->getContractJobs($filter_data);
		
		if ($jobs) {
			$response = array(
				'status' => 'success',
				'message' => lang('ContractJob.success_list'),
				'contract_jobs' => [
					'data' => $jobs,
					'pagination' => array(
						'total' => (int)$total_contract_jobs,
						'length' => $limit,
						'start' => $start,
						'records' => count($jobs)
					)
				]
			);

			return $this->setResponseFormat("json")->respond($response, 200);
		} else {
			$response = array(
				'status' => 'success',
				'message' => lang('ContractJob.error_list')
			);

			return $this->setResponseFormat("json")->respond($response, 201);
		}
	}

	public function getContractJob()
	{
		$response = array();
		$this->validatePermission('view_contract_job');	// Check permission

		$modelContractJob = new ContractJobModel(); // Load model

		$contract_job_id = $this->request->getVar('contract_job_id');
		$job = $modelContractJob->getContractJob($contract_job_id);
		if ($job) {
			$response['status'] = 'success';
			$response['message'] = lang('ContractJob.success_detail');
			$response['contract_job'] = $job;
			return $this->setResponseFormat("json")->respond($response, 200);
		} else {
			$response['status'] = 'error';
			$response['message'] = lang('ContractJob.error_detail');
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
}