<?php

namespace App\Controllers\Employee\dmt;;

use CodeIgniter\RESTful\ResourceController;
use App\Libraries\Auth\User as AuthUser;
use App\Models\Employee\ContractJobModel;
use App\Models\Admin\ContractJobAssetModel;
use App\Models\Employee\ContractJobPPMModel;
use App\Libraries\AppJobManager;

class ContractJob extends ResourceController
{
	protected $empType = 'data_management';

	private $PPMFrequencyDates = [];

	public function __construct()
	{
		helper(['user', 'contract_job']);	// Loading user & contract_job helpers
	}

	public function index()
	{
		$this->validatePermission('view_contract_job');	// Check permission
		$modelContractJob = new ContractJobModel(); // Load model

		$user_type = getUserTypeByCode($this->empType);

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
			'status' => 1
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
				'status' => 'error',
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

	// Get Assets of Contract/job
	public function getContractJobAssets()
	{
		$response = array();
		$this->validatePermission('view_contract_job');	// Check permission

		$modelContractJob = new ContractJobModel(); // Load model
		$modelContractJobAsset = new ContractJobAssetModel(); // Load model

		$contract_job_id = $this->request->getVar('contract_job_id');
		$job = $modelContractJob->getContractJob($contract_job_id);
		if ($job) {
			$jobAssets = $modelContractJobAsset->getAssets($contract_job_id);
			if ($jobAssets) {
				$response['status'] = 'success';
				$response['message'] = lang('ContractJob.Assets.success_detail');
				$response['contract_job_assets'] = $jobAssets;
				return $this->setResponseFormat("json")->respond($response, 200);
			} else {
				$response['status'] = 'error';
				$response['message'] = lang('ContractJob.Assets.error_detail');
				return $this->setResponseFormat("json")->respond($response, 201);
			}
		} else {
			$response['status'] = 'error';
			$response['message'] = lang('ContractJob.error_detail');
			return $this->setResponseFormat("json")->respond($response, 201);
		}
	}

	// Get Checklists of Contract/job Assets
	public function getContractJobAssetChecklists()
	{
		$response = array();
		$this->validatePermission('view_contract_job');	// Check permission

		$modelContractJob = new ContractJobModel(); // Load model
		$modelContractJobAsset = new ContractJobAssetModel(); // Load model

		$asset_id = $this->request->getVar('asset_id');
		$asset = $modelContractJobAsset->getAssetByAsset($asset_id);
		if ($asset) {
			$assetChecklists = $modelContractJobAsset->getAssetChecklistsByAsset($asset_id);
			if ($assetChecklists) {
				$response['status'] = 'success';
				$response['message'] = lang('ContractJob.AssetChecklist.success_detail');
				$response['asset_checklists'] = $assetChecklists;
				return $this->setResponseFormat("json")->respond($response, 200);
			} else {
				$response['status'] = 'error';
				$response['message'] = lang('ContractJob.AssetChecklist.error_detail');
				return $this->setResponseFormat("json")->respond($response, 201);
			}
		} else {
			$response['status'] = 'error';
			$response['message'] = lang('ContractJob.Asset.error_detail');
			return $this->setResponseFormat("json")->respond($response, 201);
		}
	}

	public function addContractJob()
	{
		$response = array();
		$this->validatePermission('add_contract_job');	// Check permission

		$ctype = $this->request->getPost('customer_type');
		if ($ctype == 'exist') {
			$rules = [
				"job_title" => "required",
				"sap_job_number" => "required"
			];
		} else {
			$rules = [
				"job_title" => "required",
				"sap_job_number" => "required",
			];
		}


		$messages = [
			"job_title" => [
				"required" => "Job title is required"
			],
			"sap_job_number" => [
				"required" => "SAP job number is required"
			],
		];
		$user_type = getUserTypeByCode($this->empType);
		$customer_user_type = getUserTypeByCode('customer');
		if ($this->validate($rules, $messages)) {
			// Validate ppm frequency
			if (! $this->validatePPMFrequency()) {
				$response = [
					'status' => 'error',
					'message' => 'PPM frequencies not created between start and end dates'
				];
				return $this->setResponseFormat("json")->respond($response, 201);
			}

			$modelContractJob = new ContractJobModel(); // Load model
			$modelContractJobPPM = new ContractJobPPMModel(); // Load model
			//Get job prefix
			$contract_type = $this->request->getPost('contract_type');
			$contract_nature = $this->request->getPost('contract_nature');
			$contractJobPrefix = $this->getContractJobPrefix($contract_type, $contract_nature);

			$postDatas = $this->request->getPost();
			// Merge post datas with default data
			$job_data = $postDatas;
			$job_data['created_user'] = AuthUser::getId();
			$job_data['status'] = 1;
			$job_data['type'] = 'new';
			$job_data['job_prefix'] = $contractJobPrefix;
			$job_data['user_type'] = $customer_user_type['type_id'];
			
			if ($postDatas['customer_type'] == 'new') {
				$filter_data = array(
					'removed' => 0,
					'status' => 1,
					'username' => $postDatas['customer_username'],
				);
				$customer = $modelContractJob->getCustomerValidation($filter_data);
			} else {
				$customer = false;
			}
			if ($customer) {
				$response['status'] = 'error';
				$response['message'] = 'Username or email already exits';
				return $this->setResponseFormat("json")->respond($response, 201);
			} else {
				$filter_data = array('removed' => 0);
				$add = $modelContractJob->addContractJob($job_data);
				if ($add) {
					// Add PPM Frequencies
					$modelContractJobPPM->addPPMFrequencies($add, $this->PPMFrequencyDates);

					// add checklist tracks
					AppJobManager::run('cron/ContractJob/addChecklistsTracksz', [
						'contract_job_id' => $add
					]);

					$response['status'] = 'success';
					$response['message'] = lang('ContractJob.success_add');
					return $this->setResponseFormat("json")->respond($response, 200);
				} else {
					$response['status'] = 'error';
					$response['message'] = lang('ContractJob.error_add');
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

	// Edit/Update Contract/job
	public function updateContractJob()
	{
		$response = array();
		// Check permission
		$this->validatePermission('update_contract_job');

		$rules = [
			"job_title" => "required",
			"sap_job_number" => "required",
		];

		$messages = [
			"job_title" => [
				"required" => "Job title is required"
			],
			"sap_job_number" => [
				"required" => "SAP job number is required"
			],

		];
		if ($this->validate($rules, $messages)) {

			// Validate ppm frequency
			if (! $this->validatePPMFrequency()) {
				$response = [
					'status' => 'error',
					'message' => 'PPM frequencies not created between start and end dates'
				];
				return $this->setResponseFormat("json")->respond($response, 201);
			}

			$modelContractJob = new ContractJobModel(); // Load model
			$modelContractJobPPM = new ContractJobPPMModel(); // Load model
			$modelContractType = new \App\Models\Admin\ContractTypeModel(); // Load model

			$user_type = getUserTypeByCode('customer');
			$contractType = $modelContractType->getContractType($this->request->getPost('contract_type'));
			$postDatas = $this->request->getPost();

			$contract_job_id = $this->request->getVar('contract_job_id');
			$job = $modelContractJob->getContractJob($contract_job_id);

			if ($job) {
				// Merge post datas with default data
				$job_data = $postDatas;
				$job_data['created_user'] = AuthUser::getId();
				$job_data['type'] = 'update';
				$job_data['parent_job'] = $job;

				if ($postDatas['customer_type'] == 'new') {
					$filter_data = array(
						'removed' => 0,
						'status' => 1,
						'username' => $postDatas['customer_username'],
					);
					$customer = $modelContractJob->getCustomerValidation($filter_data);
				} else {
					$customer = false;
				}
				if ($customer) {
					$response['status'] = 'error';
					$response['message'] = 'Username or email already exits';
					return $this->setResponseFormat("json")->respond($response, 201);
				} else {
					$update = $modelContractJob->updateContractJob($job_data);
					if ($update) {
						// Add PPM Frequencies
						$modelContractJobPPM->addPPMFrequencies($update, $this->PPMFrequencyDates);

						// Disable Existing contract job
						$modelContractJob->disableContractJob($job->contract_job_id);

						// add checklist tracks
						$command = AppJobManager::run('cron/ContractJob/addChecklistsTracksz', [
							'contract_job_id' => $update
						]);

						$response['status'] = 'success';
						$response['message'] = lang('ContractJob.success_update');
						return $this->setResponseFormat("json")->respond($response, 200);
					} else {
						$response['status'] = 'error';
						$response['message'] = lang('ContractJob.error_update');
						return $this->setResponseFormat("json")->respond($response, 201);
					}
				}
			} else {
				$response['status'] = 'error';
				$response['message'] = lang('ContractJob.error_detail');
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

	// Renew Contract/job
	public function renewContractJob()
	{
		$response = array();
		// Check permission
		$this->validatePermission('renew_contract_job');

		$rules = [
			"job_title" => "required",
			"sap_job_number" => "required"
		];

		$messages = [
			"job_title" => [
				"required" => "Job title is required"
			],
			"sap_job_number" => [
				"required" => "SAP job number is required"
			]

		];
		if ($this->validate($rules, $messages)) {
			// Validate ppm frequency
			if (! $this->validatePPMFrequency()) {
				$response = [
					'status' => 'error',
					'message' => 'PPM frequencies  not created between start and end dates'
				];
				return $this->setResponseFormat("json")->respond($response, 201);
			}

			$modelContractJob = new ContractJobModel(); // Load model
			$modelContractType = new \App\Models\Admin\ContractTypeModel(); // Load model

			$user_type = getUserTypeByCode('customer');
			$postDatas = $this->request->getPost();

			//Get job prefix
			$contract_type = $this->request->getPost('contract_type');
			$contract_nature = $this->request->getPost('contract_nature');
			$contractJobPrefix = $this->getContractJobPrefix($contract_type, $contract_nature);

			$contract_job_id = $this->request->getVar('contract_job_id');
			$job = $modelContractJob->getContractJob($contract_job_id);
			if ($job) {
				// Find job parent
				$jobParent = $job->contract_job_id;
				$jobParentPath = '';
				$paths = [];
				if ($job->parent_path) {
					$paths = explode(',', $job->parent_path);
				}
				array_push($paths, $jobParent);
				$jobParentPath = implode(',', $paths);

				$job_data = $postDatas;
				$job_data['created_user'] = AuthUser::getId();
				$job_data['type'] = 'renew';
				$job_data['job_prefix'] = $contractJobPrefix;
				$job_data['parent_job'] = $job;

				$filter_data = array(
					'removed' => 0,
					'status' => 1,
					'username' => $postDatas['customer_username'],
					'except' => [$job->user_id]
				);
				$customer = $modelContractJob->getCustomerValidation($filter_data);
				if ($customer) {
					$response['status'] = 'error';
					$response['message'] = 'Username or email already exits';
					return $this->setResponseFormat("json")->respond($response, 201);
				} else {
					$add = $modelContractJob->updateContractJob($job_data);
					if ($add) {
						// Add PPM Frequencies
						$modelContractJob->addPPMFrequencies($add, $this->PPMFrequencyDates);

						// add checklist tracks
						AppJobManager::run('cron/ContractJob/addChecklistsTracksz', [
							'contract_job_id' => $add
						]);

						$response['status'] = 'success';
						$response['message'] = lang('ContractJob.success_update');
						return $this->setResponseFormat("json")->respond($response, 200);
					} else {
						$response['status'] = 'error';
						$response['message'] = lang('ContractJob.error_update');
						return $this->setResponseFormat("json")->respond($response, 201);
					}
				}
			} else {
				$response['status'] = 'error';
				$response['message'] = lang('ContractJob.error_detail');
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

	protected function getContractJobPrefix($contract_type, $contract_nature)
	{
		$modelContractType = new \App\Models\Admin\ContractTypeModel(); // Load model
		$contractType = $modelContractType->getContractType($contract_type);
		$contractTypeName = isset($contractType->name) ? strtolower($contractType->name) : '';
		$contractTypePrefix = $contractType->job_prefix ?? '';

		if (in_array($contractTypeName, ['lamc', 'camc'])) {
			$modelContractNature = new \App\Models\Admin\ContractNatureModel(); // Load model
			$contractNature = $modelContractNature->getContractNature($contract_nature);
			$contractNatureCode = $contractNature->code ?? '';
			$contractJobPrefix = $contractTypePrefix . $contractNatureCode . '-';
		} else {
			$contractJobPrefix = $contractTypePrefix;
		}

		return $contractJobPrefix;
	}
	
	public function setContractJobStatus()
	{
		$response = array();

		//$this->validatePermission('edit_contract_job');	// Check permission
		$modelContractJob = new ContractJobModel();

		$contract_job_id = $this->request->getVar('contract_job_id');
		$status = $this->request->getVar('status');
		$job = $modelContractJob->getContractJob($contract_job_id);
		if ($job) {
			// $remove = $modelContractJob->setContractJobStatus($contract_job_id, $status);
			$remove = $modelContractJob->removeContractJob($contract_job_id, $status);
			if ($remove) {
				$response['status'] = 'success';
				$response['message'] = lang('ContractJob.success_status');
				return $this->setResponseFormat("json")->respond($response, 200);
			} else {
				$response['status'] = 'error';
				$response['message'] = lang('ContractJob.error_removed');
				return $this->setResponseFormat("json")->respond($response, 201);
			}
		} else {
			$response['status'] = 'error';
			$response['message'] = lang('ContractJob.error_detail');
			return $this->setResponseFormat("json")->respond($response, 201);
		}
	}

	public function deleteContractJob()
	{
		$response = array();

		$this->validatePermission('edit_contract_job');	// Check permission
		$modelContractJob = new ContractJobModel(); // Load model

		$contract_job_id = $this->request->getVar('contract_job_id');
		$job = $modelContractJob->getContractJob($contract_job_id);
		if ($job) {
			$remove = $modelContractJob->removeContractJob($contract_job_id);
			if ($remove) {
				$response['status'] = 'success';
				$response['message'] = lang('ContractJob.success_removed');
				return $this->setResponseFormat("json")->respond($response, 200);
			} else {
				$response['status'] = 'error';
				$response['message'] = lang('ContractJob.error_removed');
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

	protected function validatePPMFrequency()
	{
		$proceed = false;
		$dateContainer = [];
		$frquencyDates = [];
		$ppm_frequency = $this->request->getPost('ppm_frequency');
		$start_date = dbdate_format($this->request->getPost('period_fromdate'));
		$end_date = dbdate_format($this->request->getPost('period_todate'));
		$ppmFrequency = getPPMFrequencyByCode($ppm_frequency);
		if ($ppmFrequency) {
			$frequency = (int)$ppmFrequency['frequency'];
			$monthsPerYear = 12 / $frequency;
			$startDateString = $start_date . ' 00:00:00';
			$endDateString = $end_date . ' 00:00:00';
			$monthString = $monthsPerYear > 9 ? "{$monthsPerYear} months" : "{$monthsPerYear} month";
			$dcount = 1;
			// Create new start date with duration & stored in array
			while ($start_date < $end_date) {
				$startdate = $start_date;
				$startDateString = $start_date . ' 00:00:00';
				// Set new start date
				$startDate = (new \DateTime($startDateString))->modify("+{$monthString}");
				if ($dcount == 1) {
					$start_date = $startDate->modify('-1 day')->format('Y-m-d');
					$frequencyStartDate =  $startdate;
					$frequencyEndDate = $start_date;
				} else {
					$start_date = $startDate->format('Y-m-d');
					$frequencyStartDate = (new \DateTime($startdate . ' 00:00:00'))->modify('+1 day')->format('Y-m-d');
					$frequencyEndDate = $start_date;
				}

				//Push start date in array for next use
				array_push($dateContainer, $start_date);

				// Push frequency dates in array
				array_push($frquencyDates, [
					'start' => $frequencyStartDate,
					'end' => $frequencyEndDate
				]);
				$dcount++;
			}
			// check end date is reside in array
			$dateLength = count($dateContainer);
			$last_date = $dateContainer[($dateLength - 1)] ?? '';
			if ($last_date == $end_date) {
				$proceed = true;
			} else {
				$proceed = false;
			}
		} else {
			$proceed = false;
		}

		$this->PPMFrequencyDates = $frquencyDates;
		return $proceed;
	}
}
