<?php

namespace App\Controllers\Employee\engineer;

use CodeIgniter\RESTful\ResourceController;
use App\Libraries\Auth\User as AuthUser;
use App\Libraries\Auth\Employee as AuthEmployee;
use App\Models\Employee\ContractJobModel;
use App\Models\Employee\ContractJobAssetModel;
use App\Models\Employee\ContractJobPPMModel;
use App\Models\Employee\EmployeeModel;
use App\Models\Employee\ChecklistModel;
use App\Libraries\AppLog;
use App\Libraries\AppJobManager;
use App\Libraries\AppStorage;

class ContractJob extends ResourceController
{
	protected $empType = 'engineer';
	protected $employeeId;

	public function __construct()
	{
		helper(['default', 'user', 'common']);
		AppLog::initLog(); // Init log
	}

	public function index()
	{
		// Check employee
		if (!$this->isEmployee()) {
			$response = array(
				'status' => 'error',
				'message' => lang('Common.error_employee_login')
			);

			return $this->setResponseFormat("json")->respond($response, 201);
		}

		$this->validatePermission('view_contract_job');	// Check permission
		$modelContractJob = new ContractJobModel(); // Load model
		$modelEmp = new EmployeeModel(); // Load model
		$empId = $modelEmp->getEmployee($this->employeeId);

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
			'status' => 1,
			'engineer_id' => $empId->employee_id
			// 'region' => $empId->region_id
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
		$modelContractJobPPM = new ContractJobPPMModel(); // Load model

		$contract_job_id = $this->request->getVar('contract_job_id');
		$job = $modelContractJob->getContractJob($contract_job_id);
		if ($job) {
			// Get PPM
			$jobPPMs = $modelContractJobPPM->getPPMFrequencies($contract_job_id, ['order' => 'ASC']);
			if ($jobPPMs) {
				$job->ppms = $jobPPMs;
			} else {
				$job->ppms = [];
			}
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

	// Get Assets of Contract/job
	public function getContractJobAssets()
	{
		$response = array();
		$this->validatePermission('view_contract_job');	// Check permission

		$modelContractJob = new ContractJobModel(); // Load model
		$modelContractJobAsset = new ContractJobAssetModel();	// Load model

		$contract_job_id = $this->request->getVar('contract_job_id');
		$job = $modelContractJob->getContractJob($contract_job_id);
		if ($job) {
			$jobAssets = $modelContractJobAsset->getAssets($contract_job_id);
			if ($jobAssets) {
				$response['status'] = 'success';
				$response['message'] = lang('ContractJob.Asset.success_detail');
				$response['contract_job_assets'] = $jobAssets;
				return $this->setResponseFormat("json")->respond($response, 200);
			} else {
				$response['status'] = 'error';
				$response['message'] = lang('ContractJob.Asset.error_detail');
				return $this->setResponseFormat("json")->respond($response, 201);
			}
		} else {
			$response['status'] = 'error';
			$response['message'] = lang('ContractJob.error_detail');
			return $this->setResponseFormat("json")->respond($response, 201);
		}
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
			$remove = $modelContractJob->setContractJobStatus($contract_job_id, $status);
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

	// Get Checklists of Contract/job Asset Checklists
	public function getContractJobAssetChecklists()
	{
		$response = array();
		$this->validatePermission('view_contract_job');	// Check permission

		$modelContractJob = new ContractJobModel(); // Load model
		$modelContractJobAsset = new ContractJobAssetModel();	// Load model
		$assetChecklistss = [];
		$contract_job_asset_id = $this->request->getVar('contract_job_asset_id');

		$asset = $modelContractJobAsset->getAssetById($contract_job_asset_id);
		if ($asset) {
			$modelContractJobPPM = new ContractJobPPMModel();	// Load model
			$ppm = $modelContractJobPPM->getCurrentPPMFrequency($asset->contract_job_id);

			$ppmId = $ppm->contract_job_ppm_id ?? 0;
			$assetChecklists = $modelContractJobAsset->getAssetChecklists($contract_job_asset_id, [
				'contract_job_ppm_id' => $ppmId,
				'order' => 'ASC'
			]);
			if ($assetChecklists) {
				// Get checklist type
				foreach ($assetChecklists as $assetChecklist) {
					$assetChecklistType = getChecklistTypeById($assetChecklist->type);
					$assetChecklistss[] = [
						'track_id' => $assetChecklist->track_id,
						'track_status' => $assetChecklist->track_status,
						'name' => $assetChecklist->name,
						'description' => $assetChecklist->name,
						'type' => $assetChecklistType,
						'created_datetime' => $assetChecklist->created_datetime,
					];
				}

				$response['status'] = 'success';
				$response['message'] = lang('ContractJob.AssetChecklist.success_detail');
				$response['asset_checklists'] = $assetChecklistss;
				return $this->setResponseFormat("json")->respond($response, 200);
			} else {
				$response['status'] = 'error';
				$response['message'] = lang('ContractJob.AssetChecklist.error_detail');
				return $this->setResponseFormat("json")->respond($response, 201);
			}
		} else {
			$response['status'] = 'error';
			$response['message'] = lang('ContractJob.error_detail');
			return $this->setResponseFormat("json")->respond($response, 201);
		}
	}

	// Asset checklist
	public function getContractJobAssetChecklist()
	{
		$response = array();
		$this->validatePermission('view_contract_job');	// Check permission

		$modelContractJob = new ContractJobModel(); // Load model
		$modelContractJobAsset = new ContractJobAssetModel();	// Load model
		$modelChecklist = new ChecklistModel();	// Load model

		$checklist_track_id = $this->request->getVar('checklist_track_id');
		
		$assetChecklist = $modelContractJobAsset->getAssetChecklist($checklist_track_id);
		if ($assetChecklist) {
			$checklist_id = $assetChecklist->checklist_id;
			// Get tasks based on checklist type
			$checklistType = getChecklistTypeById($assetChecklist->type);
			$assetChecklist->type = $checklistType;
			$checklistTypeCode = $checklistType['code'] ?? '';
			// print_r($checklistTypeCode);
			switch ($checklistTypeCode) {
				case 'task';
					$divisions = [];
					$tasks = $modelChecklist->getTasks($checklist_id, [
						'division_id' => 0,
						'order' => 'ASC'
					]);

					break;
				case 'task_with_division';
					$divisions = $modelChecklist->getTaskDivisions($checklist_id, [
						'order' => 'ASC'
					]);
					if ($divisions) {
						foreach ($divisions as $division) {
							$tasks = $modelChecklist->getTasks($checklist_id, [
								'division_id' => $division->checklist_division_id,
								'order' => 'ASC'
							]);
						}
					}
					break; 
				default:
					$divisions = [];
					$tasks = [];
			}

			$checklistTasks = [];
			if ($tasks) {
				foreach ($tasks as $task) {
					$track = $modelChecklist->getChecklistTaskTrack($checklist_track_id, $task->checklist_task_id);
					$trackValue = $track->task_value ?? "";
					$trackCreatedDatetime = $track->created_datetime ?? NULL;
					$checklistTasks[] = [
						'checklist_task_id' => $task->checklist_task_id,
						'checklist_id' => $task->checklist_id, 
						'division_id' => $task->division_id, 
						'name' => $task->name, 
						'type' => $task->type,
						'value' => $trackValue, 
						'created_datetime' => $trackCreatedDatetime, 
						'updated_datetime' => $trackCreatedDatetime
					];
				}
			}

			// Get attachments
			$attachments = [];
			$attachmentsz = $assetChecklist->attachments;
			$attachmentFiles = $attachmentsz ? explode(',', $attachmentsz) : [];
			if ($attachmentFiles) {
				foreach ($attachmentFiles as $attachmentFile) {
					$path = ROOTPATH . 'public/uploads/checklist/';
					if (is_readable($path . $attachmentFile)) {
						$attachments[] = [
							'name' => $attachmentFile,
							'link' => base_url('uploads/checklist/' . $attachmentFile)
						];
					}
				}
			}
			$response['status'] = 'success';
			$response['message'] = lang('ContractJob.AssetChecklist.success_detail');
			$response['checklist'] = [
				'track_id' => $assetChecklist->track_id,
				'track_status' => $assetChecklist->track_status,
				'name' => $assetChecklist->name,
				'description' => $assetChecklist->description,
				'type' => $assetChecklist->type,
				'attachments' => $attachments,
				'status' => $assetChecklist->status,
				'created_datetime' => $assetChecklist->created_datetime,
				'divisions' => $divisions,
				'tasks' => $checklistTasks
			];
			return $this->setResponseFormat("json")->respond($response, 200);
		} else {
			$response['status'] = 'error';
			$response['message'] = lang('ContractJob.AssetChecklist.error_detail');
			return $this->setResponseFormat("json")->respond($response, 201);
		}
		
	}

	// Checklist track
	public function addChecklistTrack()
	{
		$response = array();
		$this->validatePermission('view_contract_job');	// Check permission

		$rules = [
            'checklist_track_id' => 'required',
            'tasks' => 'required',
			'geolocation' => 'required'
        ];

        $messages = [
            'checklist_track_id' => [
                'required' => 'Checklist track id is required',
            ],
            'tasks' => [
                'required' => 'Tasks is required',
            ],
			'geolocation' => [
                'required' => 'Geolocation is required',
            ],
        ];
		// Check attachments
		$attachmentFiles = $this->request->getFiles();
		$attachments = $attachmentFiles['attachments'] ?? [];
		if ($attachments) {
			$rules['attachments'] = 'uploaded[attachments]|ext_in[attachments,png,jpg,gif,docx,doc,xlsx,pdf]';
		}
        if ($this->validate($rules, $messages)) {
			$modelContractJob = new ContractJobModel(); // Load model
			$modelContractJobAsset = new ContractJobAssetModel();	// Load model
			$modelChecklist = new ChecklistModel();	// Load model

			$checklist_track_id = $this->request->getVar('checklist_track_id');
			$tasks = $this->request->getPost('tasks');
			$checklist_track_status = $this->request->getPost('checklist_track_status');
			
			$geolocation = $this->request->getPost('geolocation');
			
			$userGeoCoordinates = [];
			if ($geolocation) {
				$location = explode(',', $geolocation);
				$userGeoCoordinates = [
					'latitude' => $location[0],
					'longitude' => $location[1] ?? 0
				];
			}

			// Log
			if (! is_array($tasks)) {
				$tasks = json_decode($tasks, true);
			} 	
			
			$assetChecklist = $modelContractJobAsset->getAssetChecklist($checklist_track_id);		
			if ($assetChecklist) {
				// Check user range	
				$range = $this->checkUserWithInRange($assetChecklist->contract_job_id, $userGeoCoordinates);
				if ($range['status'] == 'inside') {
					$tracks = $modelContractJobAsset->addChecklistTasksTrack($assetChecklist->track_id, [
						'tasks' => $tasks
					]);
					if ($tracks) {
						$modelContractJobAsset->setChecklistTrackStatus($assetChecklist->track_id, $checklist_track_status);
						
						// update ppm frequency status
						AppJobManager::run('cron/ContractJob/updatePPMFrquencyStatus', [
							'contract_job_id' => $assetChecklist->contract_job_id,
							'contract_job_ppm_id' => $assetChecklist->contract_job_ppm_id
						]);

						// Upload attachment
						$uploadedFilenames = [];
						if ($attachments) {
							// Init storage
							AppStorage::init([
								'storageType' => 'default',
								'uploadPath' => ROOTPATH . 'public/uploads/checklist/'
							]);
							foreach ($attachments as $attachment) {
								// Save file to storage
								$uploadResponse = AppStorage::saveFile($attachment, 'formdata');
								$uploadResponseStatus = $uploadResponse['status'] ?? 'error';
								if ($uploadResponseStatus == 'success') {
									array_push($uploadedFilenames, $uploadResponse['filename']);
								}
							}

							// Set/Save attachments filename to checklist
							if ($uploadedFilenames) {
								$modelContractJobAsset->setChecklistTrackAttachments($checklist_track_id, $uploadedFilenames);
							}

							// Delete existing attachments
							if ($assetChecklist->attachments) {
								$attachmentFilesz = explode(',', $assetChecklist->attachments);
								AppStorage::deleteFiles($attachmentFilesz);
							}
							
						}
						
						$response['status'] = 'success';
						$response['message'] = lang('ContractJob.AssetChecklistTrack.success_add');
						return $this->setResponseFormat("json")->respond($response, 200);
					} else {
						$response['status'] = 'error';
						$response['message'] = lang('ContractJob.AssetChecklistTrack.error_add');
						return $this->setResponseFormat("json")->respond($response, 201);
					}
				} else {
					$response['status'] = 'error';
					$response['message'] = lang('ContractJob.AssetChecklistTrack.error_user_range');
					return $this->setResponseFormat("json")->respond($response, 201);
				}
			} else {
				$response['status'] = 'error';
				$response['message'] = lang('ContractJob.AssetChecklistTrack.error_detail');
				return $this->setResponseFormat("json")->respond($response, 201);
			}
		} else {
			$response = [
                'status' => 'error',
                'message' => $this->validator->getErrors(),
            ];
            return $this->setResponseFormat('json')->respond($response, 201);
		}
	}

	protected function checkUserWithInRange($contract_job_id, $userGeoCoordinates)
	{
		$modelContractJob = new ContractJobModel(); // Load model
		$job = $modelContractJob->getContractJob($contract_job_id);
		if ($job) {
			$range = $job->geolocation_range;
			$jobGeoCoordinates = [
				'latitude' => $job->geolocation_lattitude,
				'longitude' => $job->geolocation_longitude
			];
			
			$distance = getDistance($jobGeoCoordinates, $userGeoCoordinates, 'm', false);
			if ((float)$distance <= (float)$range) {
				return [
					'status' => 'inside',
					'job_range' => $range . ' m',
					'user_distance' => $distance . ' m'
				];
			} else {
				return [
					'status' => 'outside',
					'job_range' => $range . ' m',
					'user_distance' => $distance . ' m'
				];
			}
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

	protected function isEmployee()
	{
		$this->userId = AuthUser::getId();
		if (AuthEmployee::isValid($this->empType)) {
			$this->employeeId = AuthEmployee::getId();
		}
		return $this->employeeId;
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
