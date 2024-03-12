<?php

namespace App\Controllers\Admin;

use CodeIgniter\RESTful\ResourceController;
use App\Libraries\Auth\User as AuthUser;
use Config\Database;
use App\Models\Employee\ChecklistModel;
use App\Models\Employee\ChecklistTaskModel;


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