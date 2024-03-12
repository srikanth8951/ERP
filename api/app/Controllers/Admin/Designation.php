<?php

namespace App\Controllers\Admin;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use CodeIgniter\RESTful\ResourceController;
use App\Libraries\Auth\User as AuthUser;
use App\Models\Admin\DesignationModel;

class Designation extends ResourceController
{

	public function index()
	{

		$this->validatePermission('view_designation');	// Check permission
		$modelDesignation = new DesignationModel(); // Load model

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

		$total_designations = $modelDesignation->getTotaldesignations($filter_data);
		$designations = $modelDesignation->getDesignations($filter_data);

		//upload data exixt
		$uploadDesignation = $modelDesignation->cancelUpload();
		//end

		if ($designations) {
			$response = array(
				'status' => 'success',
				'message' => lang('Designation.success_list'),
				'designations' => $designations,
				'pagination' => array(
					'total' => (int)$total_designations,
					'length' => $limit,
					'start' => $start,
					'records' => count($designations)
				)
			);

			return $this->setResponseFormat("json")->respond($response, 200);
		} else {
			$response = array(
				'status' => 'error',
				'message' => lang('Designation.error_list')
			);

			return $this->setResponseFormat("json")->respond($response, 201);
		}
	}

	public function getDesignation()
	{
		$response = array();
		$this->validatePermission('view_designation');	// Check permission

		$modelDesignation = new DesignationModel(); // Load model

		$designation_id = $this->request->getVar('designation_id');
		$designation = $modelDesignation->getDesignation($designation_id);
		if ($designation) {
			$response['status'] = 'success';
			$response['message'] = lang('Designation.success_detail');
			$response['designation'] = $designation;
			return $this->setResponseFormat("json")->respond($response, 200);
		} else {
			$response['status'] = 'error';
			$response['message'] = lang('Designation.error_detail');
			return $this->setResponseFormat("json")->respond($response, 201);
		}
	}

	public function addDesignation()
	{
		$response = array();
		$this->validatePermission('add_designation');	// Check permission

		$rules = [
			"designation_name" => "required"
		];

		$messages = [
			"designation_name" => [
				"required" => "Designation name is required"
			]
		];
		if ($this->validate($rules, $messages)) {
			$modelDesignation = new DesignationModel(); // Load model
			$designation_name = $this->request->getPost('designation_name');
			$designation_data = array(
				'name' => $designation_name,
				'is_exist' => 0,
				'status' => 1
			);
			$filter_data = array('removed' => 0);
			$designation = $modelDesignation->getDesignationByName($designation_name, $filter_data);
			if ($designation) {
				$response['status'] = 'error';
				$response['message'] = lang('Designation.error_exist');
				return $this->setResponseFormat("json")->respond($response, 201);
			} else {
				$add = $modelDesignation->addDesignation($designation_data);
				if ($add) {
					$response['status'] = 'success';
					$response['message'] = lang('Designation.success_add');
					return $this->setResponseFormat("json")->respond($response, 200);
				} else {
					$response['status'] = 'error';
					$response['message'] = lang('Designation.error_add');
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

	public function editDesignation()
	{
		$response = array();

		$this->validatePermission('edit_designation');	// Check permission

		$rules = [
			"designation_name" => "required"
		];

		$messages = [
			"designation_name" => [
				"required" => "Designation name is required"
			]
		];
		if ($this->validate($rules, $messages)) {
			$modelDesignation = new DesignationModel(); // Load model

			$designation_id = $this->request->getVar('designation_id');

			$designation = $modelDesignation->getDesignation($designation_id);
			if ($designation) {
				$designation_data = array(
					'name' => $this->request->getPost('designation_name'),
					'status' => $designation->status
				);
				$filter_data = array('removed' => 0, 'except' => [$designation->designation_id]);
				$designation_name = $modelDesignation->getDesignationByName($designation_data['name'], $filter_data);
				if ($designation_name) {
					$response['status'] = 'error';
					$response['message'] = lang('Designation.error_exist');
					return $this->setResponseFormat("json")->respond($response, 201);
				} else {
					$edit = $modelDesignation->editDesignation($designation_id, $designation_data);
					if ($edit) {
						$response['status'] = 'success';
						$response['message'] = lang('Designation.success_edit');
						return $this->setResponseFormat("json")->respond($response, 200);
					} else {
						$response['status'] = 'error';
						$response['message'] = lang('Designation.error_edit');
						return $this->setResponseFormat("json")->respond($response, 201);
					}
				}
			} else {
				$response['status'] = 'error';
				$response['message'] = lang('Designation.error_detail');
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

	public function deleteDesignation()
	{
		$response = array();

		$this->validatePermission('edit_designation');	// Check permission
		$modelDesignation = new DesignationModel(); // Load model

		$designation_id = $this->request->getVar('designation_id');
		$designation = $modelDesignation->getDesignation($designation_id);
		if ($designation) {
			$remove = $modelDesignation->removeDesignation($designation_id);
			if ($remove) {
				$response['status'] = 'success';
				$response['message'] = lang('Designation.success_removed');
				return $this->setResponseFormat("json")->respond($response, 200);
			} else {
				$response['status'] = 'error';
				$response['message'] = lang('Designation.error_removed');
				return $this->setResponseFormat("json")->respond($response, 201);
			}
		} else {
			$response['status'] = 'error';
			$response['message'] = lang('Designation.error_detail');
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
		$this->validatePermission('view_designation');	// Check permission
		$modelDesignation = new DesignationModel(); // Load model

		$filter_data = array(
			'removed' => 0,
			'status' => 1
		);
		$designationArray = array();
		$designations = $modelDesignation->getDesignations($filter_data);
		if ($designations) {
			foreach ($designations as $designation) {
				$designationArray[] = array(
					'id' => (int)$designation->designation_id,
					'name' => html_entity_decode($designation->name)
				);
			}
		}
		$response = array(
			'status' => 'success',
			'designations' => $designationArray,
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
			//echo WRITEPATH . 'uploads';
			$modelDesignation = new DesignationModel(); // Load model
			if ($upload['status'] == 'success') {

				$file = fopen(WRITEPATH . "uploads/" . $upload['image'], "r");
				$i = 0;
				$numberOfFields = 1;
				$csvArr = array();

				while (($filedata = fgetcsv($file, 1000, ",")) !== FALSE) {
					$num = count($filedata);
					if ($i > 0 && $num == $numberOfFields) {

						$csvArr[$i]['name'] = $filedata[0] ?? '';
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

					$rdetails = $modelDesignation->getDesignationExist($dfilter_data);

					if (!empty($rdetails)) {
						$modelDesignation->updateDesignation($rdetails->designation_id, $dfilter_data);
					}
					if ($userdata['name']) {
						$add = $modelDesignation->addDesignation($userdata);
					}

					$nfilter_data = array(
						'removed' => 0,
						'status' => 1,
						'is_exist' => 2,
						'name' => $userdata['name']
					);

					$fdetails = $modelDesignation->getDesignationExists($nfilter_data);

					if ($fdetails) {
						$modelDesignation->updateDesignationDetails($fdetails->name);
					}

					$rfilter_data = array(
						'removed' => 0,
						'is_exist' => 0
					);

					$final = $modelDesignation->getDesignationList($rfilter_data);
				}


				$path_to_file = WRITEPATH . "uploads/" . $upload['image'];
				unlink($path_to_file);


				$response = array(
					'status' => 'success',
					'upload_designations' => $final
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


	public function saveDesignation()
	{
		$response = [];
		$modelDesignation = new DesignationModel(); // Load model
		$add = $modelDesignation->updateUploadDesignation();

		if ($add) {
			$response['status'] = 'success';
			$response['message'] = lang('Designation.success_add');
			return $this->setResponseFormat("json")->respond($response, 200);
		} else {
			$response['status'] = 'error';
			$response['message'] = lang('Designation.error_add');
			return $this->setResponseFormat("json")->respond($response, 201);
		}
	}

	public function cancel()
	{
		$response = [];
		$modelDesignation = new DesignationModel(); // Load model
		$add = $modelDesignation->cancelUpload();

		if ($add) {
			$response['status'] = 'success';
			$response['message'] = lang('Designation.success_upload_cancel');
			return $this->setResponseFormat("json")->respond($response, 200);
		} else {
			$response['status'] = 'error';
			$response['message'] = lang('Designation.error_add');
			return $this->setResponseFormat("json")->respond($response, 201);
		}
	}
}
