<?php

namespace App\Controllers\Employee\rsd;

use CodeIgniter\RESTful\ResourceController;
use App\Libraries\Auth\User as AuthUser;
use App\Models\Employee\ContractJobModel;
use App\Models\Employee\ContractJobPPMModel;
use App\Models\Employee\EmployeeModel;

class ContractJob extends ResourceController
{
	protected $empType = 'rsd_head';

	public function __construct()
	{
		helper('user');
	}

	public function index()
	{
		$this->validatePermission('view_contract_job');	// Check permission
		$modelContractJob = new ContractJobModel(); // Load model
		$modelEmp = new EmployeeModel(); // Load model

		$user_type = getUserTypeByCode($this->empType);
		$userLoginId = AuthUser::getId();
		$empId = $modelEmp->getEmployeeByID($userLoginId);

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
			// 'removed' => 0,
			'search' => $search,
			'start' => ($start - 1),
			'limit' => $limit,
			'sort' => $sort,
			'order' => $order,
			'status' => 1,
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

	// Get list of Contract/jobs
	public function logs()
	{

		$this->validatePermission('view_contract_job');	// Check permission
		$modelContractJob = new ContractJobModel(); // Load model

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
		$contract_job_id = $this->request->getGet('contract_job_id');
		if ($contract_job_id) {
			$contract_job_id = $contract_job_id;
		} else {
			$contract_job_id = '';
		}

		$job = $modelContractJob->getContractJob($contract_job_id);

		$filter_data = array(
			'search' => $search,
			'start' => ($start - 1),
			'limit' => $limit,
			'sort' => $sort,
			'order' => $order,
			'status' => 0,
			'contract_job_id' => explode(",", $job->parent_path),
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

	// Get PPM Frequency of Contract/job
	public function getContractJobPPMFrquencies()
	{
		$response = array();
		$this->validatePermission('view_contract_job');	// Check permission

		$modelContractJob = new ContractJobModel(); // Load model
		$modelContractJobPPM = new ContractJobPPMModel();	// Load model

		$contract_job_id = $this->request->getVar('contract_job_id');
		$job = $modelContractJob->getContractJob($contract_job_id);
		if ($job) {
			$frequencies = [];
			$jobPPMs = $modelContractJobPPM->getPPMFrequencies($contract_job_id, ['order' => 'ASC']);
			if ($jobPPMs) {
				foreach ($jobPPMs as $key => $value) {
					$PPM_status = getPPMFrequencyStatusById($value->status);
					if ($PPM_status) {
						$jobPPMs[$key]->status = $PPM_status;
					}
				}
				$response['status'] = 'success';
				$response['message'] = lang('ContractJob.PPM.success_detail');
				$response['contract_job_ppm_frequencies'] = $jobPPMs;
				return $this->setResponseFormat("json")->respond($response, 200);
			} else {
				$response['status'] = 'error';
				$response['message'] = lang('ContractJob.PPM.error_detail');
				return $this->setResponseFormat("json")->respond($response, 201);
			}
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
