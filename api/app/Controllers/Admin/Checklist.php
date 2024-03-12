<?php

namespace App\Controllers\Admin;

use CodeIgniter\RESTful\ResourceController;
use App\Libraries\Auth\User as AuthUser;
use Config\Database;
use App\Models\Admin\ChecklistModel;


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

        if ($this->request->getGet('group')) {
            $group = $this->request->getGet('group');
		} else {
            $group = '';
        }
		
        $limit = 2;
        $start = $limit * ($page - 1);
        $modelChecklist = new ChecklistModel();
        $filterData = [
            'removed' => 0,
            'start' => $start, 
            'limit' => $limit
        ];
        if ($group) {
            $filterData['group'] = $group;
        }

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

    public function addChecklist()
	{
		$response = array();
		
        $rules = [
            "checklist_name" => "required",
            "checklist_group" => "required",
            "checklist_type" => "required",
            "checklist_status" => "required"
        ];

        $messages = [
            "checklist_name" => [
                "required" => "checklist name is required"
            ],
            "checklist_group" => [
                "required" => "checklist group is required"
            ],
            "checklist_type" => [
                "required" => "checklist type is required"
            ],
            "checklist_status" => [
                "required" => "checklist status is required"
            ]
        ];

        if ($this->validate($rules, $messages)) {
            $modelChecklist = new ChecklistModel();
                            
            $checklist_data = array(
                'name' => $this->request->getPost('checklist_name'),
                'group' => $this->request->getPost('checklist_group'),
                'description' => $this->request->getPost('checklist_description'),
                'type' => $this->request->getPost('checklist_type'),
                'status' => $this->request->getPost('checklist_status')
            );
            $add = $modelChecklist->addchecklist($checklist_data);
            if ($add) {
                $response['status'] = 'success';
                $response['message'] = lang('Checklist.success_add');
            } else {
                $response['status'] = 'error';
                $response['message'] = lang('Checklist.error_add');
            }
        } else {
            $response = [
                'status' => 'error',
                'message' => $this->validator->getErrors()
            ];
        }
		

		return $this->setResponseFormat("json")->respond($response);

	}

	public function editchecklist()
	{
		$response = array();
		
		$checklist_id = $this->request->getVar('checklist_id');

        $rules = [
            "checklist_name" => "required",
            "checklist_group" => "required",
            "checklist_type" => "required",
            "checklist_status" => "required"
        ];

        $messages = [
            "checklist_name" => [
                "required" => "checklist name is required"
            ],
            "checklist_group" => [
                "required" => "checklist group is required"
            ],
            "checklist_type" => [
                "required" => "checklist type is required"
            ],
            "checklist_status" => [
                "required" => "checklist status is required"
            ]
        ];

        if ($this->validate($rules, $messages)) {
            $checklist_data = array(
                'name' => $this->request->getPost('checklist_name'),
                'group' => $this->request->getPost('checklist_group'),
                'description' => $this->request->getPost('checklist_description'),
                'type' => $this->request->getPost('checklist_type'),
                'status' => $this->request->getPost('checklist_status')
            );

            $modelChecklist = new ChecklistModel();
            $checklist = $modelChecklist->getChecklist($checklist_id);
            if ($checklist) {
                $edit = $modelChecklist->editChecklist($checklist_id, $checklist_data);
                if ($edit) {
                    $response['status'] = 'success';
                    $response['message'] =lang('Checklist.success_edit');
                } else {
                    $response['status'] = 'error';
                    $response['message'] =lang('Checklist.error_edit');
                }
                
            } else {
                $response['status'] = 'error';
                $response['message'] = lang('Checklist.error_detail');
            }
        } else {
            $response['status'] = 'error';
            $response['message'] = $this->validator->getErrors();
        }
		
		return $this->setResponseFormat("json")->respond($response);
	}

	public function deletechecklist()
	{
		$response = array();
		$modelChecklist = new ChecklistModel();

		$checklist_id = $this->request->getVar('checklist_id');
        $checklist = $modelChecklist->getChecklist($checklist_id);
        if ($checklist) {
            $remove = $modelChecklist->removeChecklist($checklist_id);
            if ($remove) {
                $response['status'] = 'success';
                $response['message'] = lang('Checklist.success_removed');
            } else {
                $response['status'] = 'error';
                $response['message'] = lang('Checklist.error_removed');
            }
        } else {
            $response['status'] = 'error';
            $response['message'] = lang('Checklist.error_detail');
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

    public function autocomplete()
	{
		$this->validatePermission('view_checklist');	// Check permission
		$modelChecklist = new ChecklistModel(); // Load model

		$filter_data = array(
			'removed' => 0,
			'status' => 1
		);
        $limit = $this->request->getGet('limit');
        if ($limit) {
            $filter_data['limit'] = (int)$limit;
        }
        $checklist_id = $this->request->getGet('checklist_id');
        if ($checklist_id) {
            $filter_data['checklist_id'] = $checklist_id;
        }
        $checklist_group = $this->request->getGet('group');
        if ($checklist_group) {
            $filter_data['group'] = $checklist_group;
		}
		$checklistArray = array();
		$checklists = $modelChecklist->getChecklists($filter_data);
		if ($checklists) {
			foreach ($checklists as $checklist) {
				$checklistArray[] = array(
					'id' => (int)$checklist->checklist_id,
					'name' => html_entity_decode($checklist->name)
				);
			}
		}
		$response = array(
			'status' => 'success',
			'checklists' => $checklistArray,
		);

		return $this->setResponseFormat("json")->respond($response, 200);
	}
}