<?php

namespace App\Controllers\Admin;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use CodeIgniter\RESTful\ResourceController;
use App\Libraries\Auth\User as AuthUser;
use App\Models\Admin\DepartmentModel;

class Department extends ResourceController
{

	function __construct()
	{
	}

	public function index()
	{

		$this->validatePermission('view_department');	// Check permission
		$modelDepartment = new DepartmentModel(); // Load model

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

		$total_departments = $modelDepartment->getTotalDepartments($filter_data);
		$departments = $modelDepartment->getDepartments($filter_data);

		//upload data exixt
		$uploadDepartment = $modelDepartment->cancelUpload();
		//end

		if ($departments) {
			$response = array(
				'status' => 'success',
				'message' => lang('Department.success_list'),
				'departments' => $departments,
				'pagination' => array(
					'total' => (int)$total_departments,
					'length' => $limit,
					'start' => $start,
					'records' => count($departments)
				)
			);

			return $this->setResponseFormat("json")->respond($response, 200);
		} else {
			$response = array(
				'status' => 'success',
				'message' => lang('Department.error_list')
			);

			return $this->setResponseFormat("json")->respond($response, 201);
		}
	}

	public function getDepartment()
	{
		$response = array();
		$this->validatePermission('view_department');	// Check permission

		$modelDepartment = new DepartmentModel(); // Load model

		$department_id = $this->request->getVar('department_id');
		$department = $modelDepartment->getDepartment($department_id);
		if ($department) {
			$response['status'] = 'success';
			$response['message'] = lang('Department.success_detail');
			$response['department'] = $department;
			return $this->setResponseFormat("json")->respond($response, 200);
		} else {
			$response['status'] = 'error';
			$response['message'] = lang('Department.error_detail');
			return $this->setResponseFormat("json")->respond($response, 201);
		}
	}

	public function addDepartment()
	{
		$response = array();
		$this->validatePermission('add_department');	// Check permission

		$rules = [
			"department_name" => "required"
		];

		$messages = [
			"department_name" => [
				"required" => "department name is required"
			]
		];
		if ($this->validate($rules, $messages)) {
			$modelDepartment = new DepartmentModel(); // Load model
			$department_name = $this->request->getPost('department_name');
			$department_data = array(
				'name' => $department_name,
				'is_exist' => 0,
				'status' => 1
			);
			$filter_data = array('removed' => 0);
			$department = $modelDepartment->getDepartmentByName($department_name, $filter_data);
			if ($department) {
				$response['status'] = 'error';
				$response['message'] = lang('Department.error_exist');
				return $this->setResponseFormat("json")->respond($response, 201);
			} else {
				$add = $modelDepartment->addDepartment($department_data);
				if ($add) {
					$response['status'] = 'success';
					$response['message'] = lang('Department.success_add');
					return $this->setResponseFormat("json")->respond($response, 200);
				} else {
					$response['status'] = 'error';
					$response['message'] = lang('Department.error_add');
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

	public function editDepartment()
	{
		$response = array();

		$this->validatePermission('edit_department');	// Check permission

		$rules = [
			"department_name" => "required"
		];

		$messages = [
			"department_name" => [
				"required" => "department name is required"
			]
		];
		if ($this->validate($rules, $messages)) {
			$modelDepartment = new DepartmentModel(); // Load model

			$department_id = $this->request->getVar('department_id');

			$department = $modelDepartment->getDepartment($department_id);
			if ($department) {
				$department_data = array(
					'name' => $this->request->getPost('department_name'),
					'status' => $department->status
				);
				$filter_data = array('removed' => 0, 'except' => [$department_id]);
				$department_name = $modelDepartment->getDepartmentByName($department_data['name'], $filter_data);
				if ($department_name) {
					$response['status'] = 'error';
					$response['message'] = lang('Department.error_exist');
					return $this->setResponseFormat("json")->respond($response, 201);
				} else {
					$edit = $modelDepartment->editDepartment($department_id, $department_data);
					if ($edit) {
						$response['status'] = 'success';
						$response['message'] = lang('Department.success_edit');
						return $this->setResponseFormat("json")->respond($response, 200);
					} else {
						$response['status'] = 'error';
						$response['message'] = lang('Department.error_edit');
						return $this->setResponseFormat("json")->respond($response, 201);
					}
				}
			} else {
				$response['status'] = 'error';
				$response['message'] = lang('Department.error_detail');
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

	public function deleteDepartment()
	{
		$response = array();

		$this->validatePermission('edit_department');	// Check permission
		$modelDepartment = new DepartmentModel(); // Load model

		$department_id = $this->request->getVar('department_id');
		$department = $modelDepartment->getDepartment($department_id);
		if ($department) {
			$remove = $modelDepartment->removeDepartment($department_id);
			if ($remove) {
				$response['status'] = 'success';
				$response['message'] = lang('Department.success_removed');
				return $this->setResponseFormat("json")->respond($response, 200);
			} else {
				$response['status'] = 'error';
				$response['message'] = lang('Department.error_removed');
				return $this->setResponseFormat("json")->respond($response, 201);
			}
		} else {
			$response['status'] = 'error';
			$response['message'] = lang('Department.error_detail');
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
		$this->validatePermission('view_department');	// Check permission
		$modelDepartment = new DepartmentModel(); // Load model

		$filter_data = array(
			'removed' => 0,
			'status' => 1
		);
		$departmentArray = array();
		$departments = $modelDepartment->getDepartments($filter_data);
		if ($departments) {
			foreach ($departments as $department) {
				$departmentArray[] = array(
					'id' => (int)$department->department_id,
					'name' => html_entity_decode($department->name)
				);
			}
		}
		$response = array(
			'status' => 'success',
			'departments' => $departmentArray,
		);

		return $this->setResponseFormat("json")->respond($response, 200);
	}

	public function upload()
	{
		$input = $this->validate([
			'file' => 'uploaded[file]|max_size[file,2048]|ext_in[file,csv,xls,xlsx],'
		]);
		if (!$input) {
			$response = array(
				'status' => 'error',
				'message' => $this->validator->getErrors()
			);
			return $this->setResponseFormat("json")->respond($response, 201);
		} else {
			$upload['status'] = 'error';
			$upload['message'] = '';

			// Upload file
			if (!empty($this->request->getFile('file'))) {
				$imageFile = $this->request->getFile('file');
				$upload = $this->saveFile($imageFile, '',  'blob');
			}

			$modeldepartment = new departmentModel(); // Load model
			if ($upload['status'] == 'success') {

				$file = fopen(WRITEPATH . "uploads/" . $upload['image'], "r");
				$i = 0;
				$numberOfFields = 1;
				$csvArr = array();

				while (($filedata = fgetcsv($file, 1000, ",")) !== FALSE) {
					$num = count($filedata);
					if ($i > 0 && $num == $numberOfFields) {
						$string = str_replace(' ', '-', $filedata[0]);
						$dname = preg_replace('/[^A-Za-z0-9\-]/', '', $string);
						$csvArr[$i]['name'] = $dname ?? '';
						$csvArr[$i]['status'] = 0;
						$csvArr[$i]['is_exist'] = 3;
						$csvArr[$i]['removed'] = 0;
						$csvArr[$i]['created_datetime'] = date('Y-m-d H:i:s');
					}
					$i++;
				}
				fclose($file);

				foreach ($csvArr as $userdata) {
					$dfilter_data = array(
						'removed' => 0,
						'status' => 1,
						'is_exist' => 0,
						'name' => $userdata['name']
					);

					$rdetails = $modeldepartment->getDepartmentExist($dfilter_data);

					if (!empty($rdetails)) {
						$modeldepartment->updatedepartment($rdetails->department_id, $dfilter_data);
					}

					if ($userdata['name']) {
						$add = $modeldepartment->adddepartment($userdata);
					}

					$nfilter_data = array(
						'removed' => 0,
						'status' => 1,
						'is_exist' => 2,
						'name' => $userdata['name']
					);

					$fdetails = $modeldepartment->getDepartmentExists($nfilter_data);

					if ($fdetails) {
						$modeldepartment->updateDepartmentDetails($fdetails->name);
					}

					$rfilter_data = array('removed' => 0);


					$final = $modeldepartment->getDepartmentList($rfilter_data);
				}

				$path_to_file = WRITEPATH . "uploads/" . $upload['image'];
				unlink($path_to_file);

				$response = array(
					'status' => 'success',
					'upload_departments' => $final
				);
				return $this->setResponseFormat("json")->respond($response, 200);
			} else {
				$response = array(
					'status' => 'error',
					'message' => $upload['message']
				);
				return $this->setResponseFormat("json")->respond($response, 201);
			}
		}
	}

	//Save file
	private function saveFile($uploadFile, $thumb = '', $uploadType = 'file')
	{
		$json = array();
		$json['status'] = false;
		$file_upload = false;

		if (!empty($uploadFile)) {

			// Load file storage library
			$fileStorage = new \App\Libraries\Storage\DefaultStorage();

			$filename = $uploadFile->getClientName();
			$file_data = array(
				'file' => $uploadFile,
				'newName' => $filename . 'file' . date('YmdHis'),
				'uploadPath' => WRITEPATH . 'uploads'
			);

			$file_upload = $fileStorage->uploadFile($file_data);

			$file_upload_status = isset($file_upload['status']) ? $file_upload['status'] : false;
			if ($file_upload_status) {
				$json['status'] = 'success';
				$json['message'] = 'File uploaded';
				$json['image'] = $file_upload['name'];
			} else {
				$json['status'] = 'error';
				$json['message'] = $file_upload['message'];
			}
		} else {
			$json['status'] = 'error';
			$json['message'] = 'Please upload valid file!';
		}

		return $json;
	}

	public function saveDepartment()
	{
		$response = [];
		$modeldepartment = new departmentModel(); // Load model

		$add = $modeldepartment->updateUploadDepartment();

		if ($add) {
			$response['status'] = 'success';
			$response['message'] = lang('department.success_add');
			return $this->setResponseFormat("json")->respond($response, 200);
		} else {
			$response['status'] = 'error';
			$response['message'] = lang('department.error_add');
			return $this->setResponseFormat("json")->respond($response, 201);
		}
	}

	public function cancel()
	{
		$response = [];
		$modeldepartment = new departmentModel(); // Load model
		$add = $modeldepartment->cancelUpload();

		if ($add) {
			$response['status'] = 'success';
			$response['message'] = lang('department.success_upload_cancel');
			return $this->setResponseFormat("json")->respond($response, 200);
		} else {
			$response['status'] = 'error';
			$response['message'] = lang('department.error_add');
			return $this->setResponseFormat("json")->respond($response, 201);
		}
	}
}
