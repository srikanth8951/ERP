<?php

namespace App\Controllers\Admin;

use CodeIgniter\RESTful\ResourceController;
use App\Libraries\Auth\User as AuthUser;
use Config\Database;
use App\Models\Admin\ChecklistModel;
use App\Models\Admin\ChecklistTaskModel;


class ChecklistTask extends ResourceController
{	
	private $user_id;
	private $error = [];
	
	function __construct()
	{
		helper(['default', 'common']);
		$this->validateUser();
	}

	// Division
	public function getDivisionList()
	{
		if ((int)$this->request->getGet('page')) {
            $page = $this->request->getGet('page');
		} else {
			$page = 1;
		}
		$checklist_id = $this->request->getVar('checklist_id');

		if ($checklist = $this->validateChecklist()) {
			$limit = 20;
			$start = $limit * ($page - 1);
			$modelChecklistTask = new ChecklistTaskModel();
			$filterData = [
				'removed' => 0,
				'start' => $start, 
				'limit' => $limit,
				'order' => 'ASC'
			];
			$checklist_divisions = $modelChecklistTask->getTaskDivisions($checklist_id, $filterData);
			$total_checklist_divisions = $modelChecklistTask->getTotalTaskDivisions($checklist_id);
			if ($checklist_divisions) {
				
				$response = [
					'status' => 'success',
					'message' => lang('Checklist.division.success_list'),
					'checklist' => $checklist,
					'checklist_divisions' => $checklist_divisions,
					'pagination' => [
						'total' => $total_checklist_divisions,
						'limit' => $limit,
						'page' => $page
					]
				];
			} else {
				$response = [
					'status' => 'success',
					'message' => lang('Checklist.division.error_list'),
					'checklist' => $checklist
				];
			}
		} else {
			$response = [
				'status' => 'error',
				'message' => lang('Checklist.error_detail')
			];
		}

		return $this->setResponseFormat("json")->respond($response);
	}

	public function addDivision()
	{
		$response = array();

		if ($this->validateChecklist()) {
			$rules = [
				"division_name" => "required"
			];

			$messages = [
				"division_name" => [
					"required" => "Division name is required"
				]
			];

			if ($this->validate($rules, $messages)) {
				$modelChecklistTask = new ChecklistTaskModel();
								
				$division_data = array(
					'checklist_id' => $this->request->getVar('checklist_id'),
					'division_name' => $this->request->getPost('division_name'),
				);
				$add = $modelChecklistTask->addTaskDivision($division_data);
				if ($add) {
					$response['status'] = 'success';
					$response['message'] = lang('Checklist.task.success_add');
				} else {
					$response['status'] = 'error';
					$response['message'] = lang('Checklist.task.error_add');
				}
			} else {
				$response = [
					'status' => 'error',
					'message' => $this->validator->getErrors()
				];
			}
		} else {
			$response = [
				'status' => 'error',
				'message' => lang('Checklist.error_detail')
			];
		}

		return $this->setResponseFormat("json")->respond($response);

	}

	public function deleteDivision()
	{
		$response = array();
		$modelChecklistTask = new ChecklistTaskModel();

		$checklist_id = $this->request->getVar('checklist_id');
		$checklist_division_id = $this->request->getVar('checklist_division_id');
		if ($this->validateChecklist()) {
			$checklistdivision = $modelChecklistTask->getTaskDivision($checklist_division_id);
			if ($checklistdivision) {
				$remove = $modelChecklistTask->removeTaskDivision($checklist_division_id);
				if ($remove) {
					$response['status'] = 'success';
					$response['message'] = lang('Checklist.division.success_removed');
				} else {
					$response['status'] = 'error';
					$response['message'] = lang('Checklist.division.error_removed');
				}
			} else {
				$response['status'] = 'error';
				$response['message'] = lang('Checklist.division.error_detail');
			}
		} else {
			$response = [
				'status' => 'error',
				'message' => lang('checklist.error_detail')
			];
		}
		return $this->setResponseFormat("json")->respond($response);
	}

	public function getDivisionTaskList()
	{
		if ((int)$this->request->getGet('page')) {
            $page = $this->request->getGet('page');
		} else {
			$page = 1;
		}
		$checklist_id = $this->request->getVar('checklist_id');

		if ($checklist = $this->validateChecklist()) {
			$limit = 20;
			$start = $limit * ($page - 1);
			$modelChecklistTask = new ChecklistTaskModel();
			$filterData = [
				'removed' => 0,
				'start' => $start, 
				'limit' => $limit,
				'order' => 'ASC',
				'division' => $this->request->getVar('checklist_division_id')
			];

			$checklist_tasks = $modelChecklistTask->getTasks($checklist_id, $filterData);
			$total_checklist_tasks = $modelChecklistTask->getTotalTasks($checklist_id, $filterData);
			
			if ($checklist_tasks) {
				foreach ($checklist_tasks as $ctKey => $checklist_task) {
					$checklist_tasks[$ctKey]->type = getChecklistTaskTypeById($checklist_task->type);
				}

				$response = [
					'status' => 'success',
					'message' => lang('Checklist.task.success_list'),
					'checklist' => $checklist,
					'checklist_tasks' => $checklist_tasks,
					'pagination' => [
						'total' => $total_checklist_tasks,
						'limit' => $limit,
						'page' => $page
					]
				];
			} else {
				$response = [
					'status' => 'success',
					'message' => lang('Checklist.task.error_list'),
					'checklist' => $checklist
				];
			}
		} else {
			$response = [
				'status' => 'error',
				'message' => lang('checklist.error_detail')
			];
		}

		return $this->setResponseFormat("json")->respond($response);
	}
	
    public function addDivisionTask()
	{
		$response = array();

		if ($this->validateChecklist()) {
			$rules = [
				"task_name" => "required",
				"task_type" => "required"
			];

			$messages = [
				"task_name" => [
					"required" => "task name is required"
				],
				"task_type" => [
					"required" => "task type is required"
				]
			];

			if ($this->validate($rules, $messages)) {
				$modelChecklistTask = new ChecklistTaskModel();
								
				$task_data = array(
					'checklist_id' => $this->request->getVar('checklist_id'),
					'task_name' => $this->request->getPost('task_name'),
					'task_type' => $this->request->getPost('task_type'),
					'division' => $this->request->getVar('checklist_division_id')
				);
				
				$add = $modelChecklistTask->addTask($task_data);
				if ($add) {
					$response['status'] = 'success';
					$response['message'] = lang('Checklist.task.success_add');
				} else {
					$response['status'] = 'error';
					$response['message'] = lang('Checklist.task.error_add');
				}
			} else {
				$response = [
					'status' => 'error',
					'message' => $this->validator->getErrors()
				];
			}
		} else {
			$response = [
				'status' => 'error',
				'message' => lang('Checklist.error_detail')
			];
		}

		return $this->setResponseFormat("json")->respond($response);

	}

	public function deleteDivisionTask()
	{
		$response = array();
		$modelChecklistTask = new ChecklistTaskModel();

		$checklist_id = $this->request->getVar('checklist_id');
		$checklist_division_id = $this->request->getVar('checklist_division_id');
		$checklist_task_id = $this->request->getVar('checklist_task_id');
		if ($this->validateChecklist()) {
			$checklisttask = $modelChecklistTask->getTask($checklist_task_id, [
				'checklist_id' => $checklist_id,
				'checklist_division_id' => $checklist_division_id
			]);
			if ($checklisttask) {
				$remove = $modelChecklistTask->removeTask($checklist_task_id);
				if ($remove) {
					$response['status'] = 'success';
					$response['message'] = lang('Checklist.task.success_removed');
				} else {
					$response['status'] = 'error';
					$response['message'] = lang('Checklist.task.error_removed');
				}
			} else {
				$response['status'] = 'error';
				$response['message'] = lang('Checklist.task.error_detail');
			}
		} else {
			$response = [
				'status' => 'error',
				'message' => lang('checklist.error_detail')
			];
		}
		return $this->setResponseFormat("json")->respond($response);
	}

	// Task
	public function getTaskList()
	{
		if ((int)$this->request->getGet('page')) {
            $page = $this->request->getGet('page');
		} else {
			$page = 1;
		}
		$checklist_id = $this->request->getVar('checklist_id');

		if ($checklist = $this->validateChecklist()) {
			$limit = 20;
			$start = $limit * ($page - 1);
			$modelChecklistTask = new ChecklistTaskModel();
			$filterData = [
				'removed' => 0,
				'start' => $start, 
				'limit' => $limit,
				'order' => 'ASC',
			];

			$checklist_tasks = $modelChecklistTask->getTasks($checklist_id, $filterData);
			$total_checklist_tasks = $modelChecklistTask->getTotalTasks($checklist_id, $filterData);
			if ($checklist_tasks) {
				foreach ($checklist_tasks as $ctKey => $checklist_task) {
					$checklist_tasks[$ctKey]->type = getChecklistTaskTypeById($checklist_task->type);
				}

				$response = [
					'status' => 'success',
					'message' => lang('Checklist.task.success_list'),
					'checklist' => $checklist,
					'checklist_tasks' => $checklist_tasks,
					'pagination' => [
						'total' => $total_checklist_tasks,
						'limit' => $limit,
						'page' => $page
					]
				];
			} else {
				$response = [
					'status' => 'success',
					'message' => lang('Checklist.task.error_list'),
					'checklist' => $checklist
				];
			}
		} else {
			$response = [
				'status' => 'error',
				'message' => lang('checklist.error_detail')
			];
		}

		return $this->setResponseFormat("json")->respond($response);
	}

	public function getTask()
	{
		
		$checklist_id = $this->request->getVar('checklist_id');
		$checklist_task_id = $this->request->getVar('checklist_task_id');

		if ($this->validateChecklist()) {
			$modelChecklistTask = new ChecklistTaskModel();
			$checklist_task = $modelChecklistTask->getTask($checklist_task_id);

			if ($checklist_task) {
				$checklist_task->type = getChecklistTaskTypeById($checklist_task->type);
				$response = array(
					'status' => 'success',
					'checklist_task' => $checklist_task,
				);
			} else {
				$response = array(
					'status' => 'error',
					'message' => lang('Checklist.task.error_detail'),
				);
			}
		} else {
			$response = [
				'status' => 'error',
				'message' => lang('checklist.error_detail')
			];
		}

		return $this->setResponseFormat("json")->respond($response);			
	}

    public function addTask()
	{
		$response = array();

		if ($this->validateChecklist()) {
			$rules = [
				"task_name" => "required",
				"task_type" => "required"
			];

			$messages = [
				"task_name" => [
					"required" => "task name is required"
				],
				"task_type" => [
					"required" => "task type is required"
				]
			];

			if ($this->validate($rules, $messages)) {
				$modelChecklistTask = new ChecklistTaskModel();
								
				$task_data = array(
					'checklist_id' => $this->request->getVar('checklist_id'),
					'task_name' => $this->request->getPost('task_name'),
					'task_type' => $this->request->getPost('task_type')
				);
				
				$add = $modelChecklistTask->addTask($task_data);
				if ($add) {
					$response['status'] = 'success';
					$response['message'] = lang('Checklist.task.success_add');
				} else {
					$response['status'] = 'error';
					$response['message'] = lang('Checklist.task.error_add');
				}
			} else {
				$response = [
					'status' => 'error',
					'message' => $this->validator->getErrors()
				];
			}
		} else {
			$response = [
				'status' => 'error',
				'message' => lang('Checklist.error_detail')
			];
		}

		return $this->setResponseFormat("json")->respond($response);

	}

	public function editTask()
	{
		$response = array();
		$checklist_id = $this->request->getVar('checklist_id');
		$checklist_task_id = $this->request->getVar('checklist_task_id');

		if ($this->validateChecklist()) {
			$rules = [
				"checklist_task_name" => "required",
				"checklist_task_contact_mobile" => "required",
				"checklist_task_location" => "required",
				"checklist_task_status" => "required"
			];

			$messages = [
				"checklist_task_name" => [
					"required" => "site name is required"
				],
				"checklist_task_contact_mobile" => [
					"required" => "site contact mobile number is required"
				],
				"checklist_task_location" => [
					"required" => "site location is required"
				],
				"checklist_task_status" => [
					"required" => "site status is required"
				],
			];

			if ($this->validate($rules, $messages)) {
				$site_expiry_date = $this->request->getPost('checklist_expiry_date');
				$task_data = array(
					'site_name' => $this->request->getPost('checklist_task_name'),
					'site_contact_mobile' => $this->request->getPost('checklist_task_contact_mobile'),
					'site_location' => $this->request->getPost('checklist_task_location'),
					'site_status' => $this->request->getPost('checklist_task_status')
				);

				$modelChecklistTask = new ChecklistTaskModel();
				$checklisttask = $modelChecklistTask->getTask($checklist_task_id);
				if ($checklisttask) {
					$edit = $modelChecklistTask->editTask($checklist_task_id, $task_data);
					if ($edit) {
						$response['status'] = 'success';
						$response['message'] =lang('Checklist.task.success_edit');
					} else {
						$response['status'] = 'error';
						$response['message'] =lang('Checklist.task.error_edit');
					}
					
				} else {
					$response['status'] = 'error';
					$response['message'] = lang('Checklist.task.error_detail');
				}
			} else {
				$response['status'] = 'error';
				$response['message'] = $this->validator->getErrors();
			}
		} else {
			$response = [
				'status' => 'error',
				'message' => lang('checklist.error_detail')
			];
		}

		return $this->setResponseFormat("json")->respond($response);
	}

	public function deleteTask()
	{
		$response = array();
		$modelChecklistTask = new ChecklistTaskModel();

		$checklist_id = $this->request->getVar('checklist_id');
		$checklist_task_id = $this->request->getVar('checklist_task_id');
		if ($this->validateChecklist()) {
			$checklisttask = $modelChecklistTask->getTask($checklist_task_id);
			if ($checklisttask) {
				$remove = $modelChecklistTask->removeTask($checklist_task_id);
				if ($remove) {
					$response['status'] = 'success';
					$response['message'] = lang('Checklist.task.success_removed');
				} else {
					$response['status'] = 'error';
					$response['message'] = lang('Checklist.task.error_removed');
				}
			} else {
				$response['status'] = 'error';
				$response['message'] = lang('Checklist.task.error_detail');
			}
		} else {
			$response = [
				'status' => 'error',
				'message' => lang('checklist.error_detail')
			];
		}
		return $this->setResponseFormat("json")->respond($response);
	}

	protected function validateChecklist()
	{
		$modelChecklist = new ChecklistModel();
		$checklist_id = $this->request->getVar('checklist_id');
		$checklist = $modelChecklist->getChecklist($checklist_id);
		if ($checklist) {
			$checklist->type = (object)getChecklistTypeById($checklist->type);
			return $checklist;
		} else {
			return false;
		}
	} 

	protected function validateUser()
	{
		$this->user_id = AuthUser::isLogged();
		if (! $this->user_id) {
			$response = array(
				'status' => 'error',
				'message' => lang('Common.error_login')
			);
		
			return $this->setResponseFormat("json")->respond($response, 401);
		}
	} 

	protected function validatePermission($permission_name)
	{
		$permission = AuthUser::checkPermission($permission_name);
		if (! $permission) {
			$response = array(
				'status' => lang('status_error'),
				'message' => lang('Common.error_permission')
			);
		
			return $this->setResponseFormat("json")->respond($response, 201);
		}
	}
}