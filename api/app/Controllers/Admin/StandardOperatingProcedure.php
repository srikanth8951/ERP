<?php

namespace App\Controllers\Admin;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use CodeIgniter\RESTful\ResourceController;
use App\Libraries\Auth\User as AuthUser;
use App\Models\Admin\StandardOperatingProcedureModel AS OperatingProcedureModel;

class StandardOperatingProcedure extends ResourceController
{

	public function index()
	{

		$this->validatePermission('view_area');	// Check permission
		$modelOperatingProcedure = new OperatingProcedureModel(); // Load model

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
			'is_exist' => 0,
			'order' => $order
		);
        $operatingProcedures = [];
		$total_operating_procedures = $modelOperatingProcedure->getTotalProcedures($filter_data);
		$operatingProceduress = $modelOperatingProcedure->getProcedures($filter_data);

		if ($operatingProceduress) {
            foreach ($operatingProceduress as $pkey => $operatingProcedure) {
                $description = $operatingProcedure->description ?? '';
                $operatingProcedures[] = [
                    'standard_operating_procedure_id' => (int)$operatingProcedure->standard_operating_procedure_id,
                    'title' => esc($operatingProcedure->title),
                    'description' => $description ? html_entity_decode($description) : '',
                    'status' => (int)$operatingProcedure->status,
                    'created_datetime' => $operatingProcedure->created_datetime,
                    'updated_datetime' => $operatingProcedure->updated_datetime
                ];
            }
			$response = array(
				'status' => 'success',
				'message' => lang('StandardOperatingProcedure.success_list'),
				'standard_operating_procedures' => $operatingProcedures,
				'pagination' => array(
					'total' => (int)$total_operating_procedures,
					'length' => $limit,
					'start' => $start,
					'records' => count($operatingProcedures)
				)
			);

			return $this->setResponseFormat("json")->respond($response, 200);
		} else {
			$response = array(
				'status' => 'error',
				'message' => lang('StandardOperatingProcedure.error_list')
			);
			return $this->setResponseFormat("json")->respond($response, 201);
		}
	}

	public function getProcedure()
	{
		$response = array();
		$this->validatePermission('view_operating_procedure');	// Check permission

		$modelOperatingProcedure = new OperatingProcedureModel(); // Load model

		$id = $this->request->getVar('id');
		$operatingProcedurez = $modelOperatingProcedure->getProcedure($id);
		if ($operatingProcedurez) {
            
            $description = $operatingProcedurez->description ?? '';
            $operatingProcedure = [
                'standard_operating_procedure_id' => (int)$operatingProcedurez->standard_operating_procedure_id,
                'title' => esc($operatingProcedurez->title),
                'description' => $description ? html_entity_decode($description) : '',
                'status' => (int)$operatingProcedurez->status,
                'created_datetime' => $operatingProcedurez->created_datetime,
                'updated_datetime' => $operatingProcedurez->updated_datetime
            ];
            
			$response['status'] = 'success';
			$response['message'] = lang('StandardOperatingProcedure.success_detail');
			$response['standard_operating_procedure'] = $operatingProcedure;
			return $this->setResponseFormat("json")->respond($response, 200);
		} else {
			$response['status'] = 'error';
			$response['message'] = lang('StandardOperatingProcedure.error_no_detail');
			return $this->setResponseFormat("json")->respond($response, 201);
		}
	}

	public function addProcedure()
	{
		$response = array();
		$this->validatePermission('add_operating_procedure');	// Check permission

		$rules = [
			"title" => "required"
		];

		$messages = [
			"title" => [
				"required" => "operating procedure name is required"
			]
		];
		if ($this->validate($rules, $messages)) {
			$modelOperatingProcedure = new OperatingProcedureModel(); // Load model
			$operatingProcedure_data = array(
				'title' => $this->request->getPost('title'),
				'description' => $this->request->getPost('description'),
				'is_exist' => 0,
				'status' => 1
			);
			$filter_data = array('removed', 0);
			$operatingProcedure = $modelOperatingProcedure->getProcedureByTitle($operatingProcedure_data['title'], $filter_data);
			if ($operatingProcedure) {
				$response['status'] = 'error';
				$response['message'] = lang('StandardOperatingProcedure.error_exist');
				return $this->setResponseFormat("json")->respond($response, 201);
			} else {
				$add = $modelOperatingProcedure->addProcedure($operatingProcedure_data);
				if ($add) {
					$response['status'] = 'success';
					$response['message'] = lang('StandardOperatingProcedure.success_add');
					return $this->setResponseFormat("json")->respond($response, 200);
				} else {
					$response['status'] = 'error';
					$response['message'] = lang('StandardOperatingProcedure.error_add');
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

	public function editProcedure()
	{
		$response = array();

		$this->validatePermission('edit_operating_procedure');	// Check permission

		$rules = [
			"title" => "required",
		];

		$messages = [
			"title" => [
				"required" => "operatingProcedure name is required"
			]
		];
		if ($this->validate($rules, $messages)) {
			$modelOperatingProcedure = new OperatingProcedureModel(); // Load model
			$operatingProcedure_data = array(
				'title' => $this->request->getPost('title'),
				'description' => $this->request->getPost('description'),
				'status' => 1
			);
			$id = $this->request->getVar('id');

			$operatingProcedure = $modelOperatingProcedure->getProcedure($id);
			if ($operatingProcedure) {
				$filter_data = array('removed', 0, 'except' => [$id]);
				$operatingProcedure_name = $modelOperatingProcedure->getProcedureByTitle($operatingProcedure_data['title'], $filter_data);
				if ($operatingProcedure_name) {
					$response['status'] = 'error';
					$response['message'] = lang('StandardOperatingProcedure.error_exist');
					return $this->setResponseFormat("json")->respond($response, 201);
				} else {
					$edit = $modelOperatingProcedure->editProcedure($id, $operatingProcedure_data);
					if ($edit) {
						$response['status'] = 'success';
						$response['message'] = lang('StandardOperatingProcedure.success_edit');
						return $this->setResponseFormat("json")->respond($response, 200);
					} else {
						$response['status'] = 'error';
						$response['message'] = lang('StandardOperatingProcedure.error_edit');
						return $this->setResponseFormat("json")->respond($response, 201);
					}
				}
			} else {
				$response['status'] = 'error';
				$response['message'] = lang('StandardOperatingProcedure.error_no_detail');
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

	public function deleteProcedure()
	{
		$response = array();

		$this->validatePermission('edit_operating_procedure');	// Check permission
		$modelOperatingProcedure = new OperatingProcedureModel(); // Load model

		$id = $this->request->getVar('id');
		$operatingProcedure = $modelOperatingProcedure->getProcedure($id);
		if ($operatingProcedure) {
			$remove = $modelOperatingProcedure->removeProcedure($id);
			if ($remove) {
				$response['status'] = 'success';
				$response['message'] = lang('success_removed');
				return $this->setResponseFormat("json")->respond($response, 200);
			} else {
				$response['status'] = 'error';
				$response['message'] = lang('error_removed');
				return $this->setResponseFormat("json")->respond($response, 201);
			}
		} else {
			$response['status'] = 'error';
			$response['message'] = lang('StandardOperatingProcedure.error_no_detail');
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

	public function autocomplete()
	{
		$this->validatePermission('view_operating_procedure');	// Check permission
		$modelOperatingProcedure = new OperatingProcedureModel(); // Load model

		$filter_data = array(
			'removed' => 0,
			'status' => 1
		);
		$operatingProcedureArray = array();
		$operatingProcedures = $modelOperatingProcedure->getStandardOperatingProcedures($filter_data);
		if ($operatingProcedures) {
			foreach ($operatingProcedures as $operatingProcedure) {
				$operatingProcedureArray[] = array(
					'id' => (int)$operatingProcedure->standard_operating_procedure_id,
					'name' => esc($operatingProcedure->title)
				);
			}
		}
		$response = array(
			'status' => 'success',
			'standard_operating_procedures' => $operatingProcedureArray,
		);

		return $this->setResponseFormat("json")->respond($response, 200);
	}

}
