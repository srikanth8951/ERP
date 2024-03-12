<?php

namespace App\Controllers\Employee;

use CodeIgniter\RESTful\ResourceController;
use App\Libraries\Auth\User as AuthUser;
use Config\Database;
use App\Models\Employee\ChecklistModel;


class Checklist extends ResourceController
{	
	private $user_id;
	private $error = [];
	
	function __construct()
	{
        // Load common helper
        helper('common'); 
        
        // Validate user
		$this->validateUser();
	}

	public function index()
	{
        helper('default');

		if ((int)$this->request->getGet('page')) {
            $page = $this->request->getGet('page');
		} else {
			$page = 1;
		}
		
        $limit = 20;
        $start = $limit * ($page - 1);
        $modelChecklist = new ChecklistModel();
        $filterData = [
            'removed' => 0,
            'start' => $start, 
            'limit' => $limit
        ];
        $checklists = $modelChecklist->getChecklists($filterData);
        $total_checklists = $modelChecklist->getTotalChecklists($filterData);
        if ($checklists) {
            foreach ($checklists as $cKey => $checklist) {
                $checklists[$cKey]->type = getChecklistTypeById($checklist->type);
            }

            $response = [
                'status' => 'success',
                'message' => lang('Checklist.success_list'),
                'checklists' => $checklists,
                'pagination' => [
                    'total' => $total_checklists,
                    'limit' => $limit,
                    'page' => $page
                ]
            ];
        } else {
            $response = [
                'status' => 'success',
                'message' => lang('Checklist.error_list')
            ];
        }

		return $this->setResponseFormat("json")->respond($response);
	}

	public function getChecklist()
	{
		helper('default');
		$checklist_id = $this->request->getVar('checklist_id');
		
        $modelChecklist = new ChecklistModel();
        $checklist = $modelChecklist->getChecklist($checklist_id);

        if ($checklist) {
            $checklist->type = getChecklistTypeById($checklist->type);
            $response = array(
                'status' => 'success',
                'checklist' => $checklist,
            );
        } else {
            $response = array(
                'status' => 'error',
                'message' => lang('Checklist.error_detail'),
            );
        }
		

		return $this->setResponseFormat("json")->respond($response);			
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