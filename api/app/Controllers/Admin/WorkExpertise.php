<?php

namespace App\Controllers\Admin;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use CodeIgniter\RESTful\ResourceController;
use App\Libraries\Auth\User as AuthUser;
use App\Models\Admin\WorkExpertiseModel;

class WorkExpertise extends ResourceController
{

	function __construct()
	{
	}

	public function index()
	{

		$this->validatePermission('view_area');	// Check permission
		$modelWorkExpertise = new WorkExpertiseModel(); // Load model

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

		$total_work_expertises = $modelWorkExpertise->getTotalWorkExpertises($filter_data);
		$work_expertises = $modelWorkExpertise->getWorkExpertises($filter_data);

		//upload data exixt
		$uploadWorkExpertise = $modelWorkExpertise->cancelUpload();
		//end

		if ($work_expertises) {
			$response = array(
				'status' => 'success',
				'message' => lang('WorkExpertise.success_list'),
				'work_expertises' => $work_expertises,
				'pagination' => array(
					'total' => (int)$total_work_expertises,
					'length' => $limit,
					'start' => $start,
					'records' => count($work_expertises)
				)
			);
			return $this->setResponseFormat("json")->respond($response, 200);
		} else {
			$response = array(
				'status' => 'success',
				'message' => lang('WorkExpertise.error_list')
			);
			return $this->setResponseFormat("json")->respond($response, 201);
		}
	}

	public function getWorkExpertise()
	{
		$response = array();
		$this->validatePermission('view_work_expertise');	// Check permission

		$modelWorkExpertise = new WorkExpertiseModel(); // Load model

		$work_expertise_id = $this->request->getVar('work_expertise_id');
		$work_expertise = $modelWorkExpertise->getWorkExpertise($work_expertise_id);
		if ($work_expertise) {
			$response['status'] = 'success';
			$response['message'] = lang('WorkExpertise.success_detail');
			$response['work_expertise'] = $work_expertise;
			return $this->setResponseFormat("json")->respond($response, 200);
		} else {
			$response['status'] = 'error';
			$response['message'] = lang('WorkExpertise.error_detail');
			return $this->setResponseFormat("json")->respond($response, 201);
		}
	}

	public function addWorkExpertise()
	{
		$response = array();
		$this->validatePermission('add_work_expertise');	// Check permission

		$rules = [
			"work_expertise_name" => "required",
			// "work_expertise_code" => "required"
		];

		$messages = [
			"work_expertise_name" => [
				"required" => "work_expertise name is required"
			],
			// "work_expertise_code" => [
			// 	"required" => "work_expertise code is required"
			// ]
		];
		if ($this->validate($rules, $messages)) {
			$modelWorkExpertise = new WorkExpertiseModel(); // Load model
			$work_expertise_data = array(
				'name' => $this->request->getPost('work_expertise_name'),
				// 'code' => $this->request->getPost('work_expertise_code'),
				'is_exist' => 0,
				'status' => 1
			);
			$filter_data = array('removed' => 0);
			$work_expertise = $modelWorkExpertise->getWorkExpertiseByName($work_expertise_data['name'], $filter_data);
			if ($work_expertise) {
				$response['status'] = 'error';
				$response['message'] = lang('WorkExpertise.error_exist');
				return $this->setResponseFormat("json")->respond($response, 201);
			} else {
				$add = $modelWorkExpertise->addWorkExpertise($work_expertise_data);
				if ($add) {
					$response['status'] = 'success';
					$response['message'] = lang('WorkExpertise.success_add');
					return $this->setResponseFormat("json")->respond($response, 200);
				} else {
					$response['status'] = 'error';
					$response['message'] = lang('WorkExpertise.error_add');
					return $this->setResponseFormat("json")->respond($response, 201);
				}
			}
		} else {
			$response = [
				'status' => 'error',
				'message' => $this->validator->getErrors()
			];
		}
	}

	public function editWorkExpertise()
	{
		$response = array();

		$this->validatePermission('edit_work_expertise');	// Check permission

		$rules = [
			"work_expertise_name" => "required",
			// "work_expertise_code" => "required"
		];

		$messages = [
			"work_expertise_name" => [
				"required" => "work_expertise name is required"
			],
			// "work_expertise_code" => [
			// 	"required" => "work_expertise code is required"
			// ]
		];
		if ($this->validate($rules, $messages)) {
			$modelWorkExpertise = new WorkExpertiseModel(); // Load model

			$work_expertise_id = $this->request->getVar('work_expertise_id');

			$work_expertise = $modelWorkExpertise->getWorkExpertise($work_expertise_id);
			if ($work_expertise) {
				$work_expertise_data = array(
					'name' => $this->request->getPost('work_expertise_name'),
					// 'code' => $this->request->getPost('work_expertise_code'),
					'status' => $work_expertise->status
				);
				$filter_data = array('removed' => 0, 'except' => [$work_expertise_id]);
				$work_expertise_name = $modelWorkExpertise->getWorkExpertiseByName($work_expertise_data['name'], $filter_data);
				if ($work_expertise_name) {
					$response['status'] = 'error';
					$response['message'] = lang('WorkExpertise.error_exist');
					return $this->setResponseFormat("json")->respond($response, 201);
				} else {
					$edit = $modelWorkExpertise->editWorkExpertise($work_expertise_id, $work_expertise_data);
					if ($edit) {
						$response['status'] = 'success';
						$response['message'] = lang('WorkExpertise.success_edit');
						return $this->setResponseFormat("json")->respond($response, 200);
					} else {
						$response['status'] = 'error';
						$response['message'] = lang('WorkExpertise.error_edit');
						return $this->setResponseFormat("json")->respond($response, 201);
					}
				}
			} else {
				$response['status'] = 'error';
				$response['message'] = lang('WorkExpertise.error_detail');
				return $this->setResponseFormat("json")->respond($response, 201);
			}
		} else {
			$response = [
				'status' => 'error',
				'message' => $this->validator->getErrors()
			];
		}
	}

	public function deleteWorkExpertise()
	{
		$response = array();

		$this->validatePermission('edit_work_expertise');	// Check permission
		$modelWorkExpertise = new WorkExpertiseModel(); // Load model

		$work_expertise_id = $this->request->getVar('work_expertise_id');
		$work_expertise = $modelWorkExpertise->getWorkExpertise($work_expertise_id);
		if ($work_expertise) {
			$remove = $modelWorkExpertise->removeWorkExpertise($work_expertise_id);
			if ($remove) {
				$response['status'] = 'success';
				$response['message'] = lang('WorkExpertise.success_removed');
				return $this->setResponseFormat("json")->respond($response, 200);
			} else {
				$response['status'] = 'error';
				$response['message'] = lang('WorkExpertise.error_removed');
				return $this->setResponseFormat("json")->respond($response, 201);
			}
		} else {
			$response['status'] = 'error';
			$response['message'] = lang('WorkExpertise.error_detail');
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
		$this->validatePermission('view_work_expertise');	// Check permission
		$modelWorkExpertise = new WorkExpertiseModel(); // Load model

		$filter_data = array(
			'removed' => 0,
			'status' => 1
		);
		$work_expertiseArray = array();
		$work_expertises = $modelWorkExpertise->getWorkExpertises($filter_data);
		if ($work_expertises) {
			foreach ($work_expertises as $work_expertise) {
				$work_expertiseArray[] = array(
					'id' => (int)$work_expertise->work_expertise_id,
					'name' => html_entity_decode($work_expertise->name)
				);
			}
		}
		$response = array(
			'status' => 'success',
			'work_expertises' => $work_expertiseArray,
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
			$modelWorkExpertise = new WorkExpertiseModel(); // Load model
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

					$rdetails = $modelWorkExpertise->getWorkExpertiseExist($dfilter_data);

					if (!empty($rdetails)) {
						$modelWorkExpertise->updateWorkExp($rdetails->work_expertise_id, $dfilter_data);
						// print_r($modelWorkExpertise->updateWorkExp($rdetails->work_expertise_id, $dfilter_data));
						// exit;
					}

					if ($userdata['name']) {
						$add = $modelWorkExpertise->addWorkExpertise($userdata);
					}

					$nfilter_data = array(
						'removed' => 0,
						'status' => 1,
						'is_exist' => 2,
						'name' => $userdata['name']
					);

					$fdetails = $modelWorkExpertise->getWorkExpertiseExists($nfilter_data);

					if ($fdetails) {
						$modelWorkExpertise->updateWorkExpertiseDetails($fdetails->name);
					}

					$rfilter_data = array(
						'removed' => 0
					);

					$final = $modelWorkExpertise->getWorkExpertiseList($rfilter_data);
				}

				$path_to_file = WRITEPATH . "uploads/" . $upload['image'];
				unlink($path_to_file);

				$response = array(
					'status' => 'success',
					'upload_WorkExpertises' => $final
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


	public function saveWorkExpertise()
	{
		$response = [];
		$modelWorkExpertise = new WorkExpertiseModel(); // Load model
		$add = $modelWorkExpertise->updateUploadWorkExp();

		if ($add) {
			$response['status'] = 'success';
			$response['message'] = lang('WorkExpertise.success_add');
			return $this->setResponseFormat("json")->respond($response, 200);
		} else {
			$response['status'] = 'error';
			$response['message'] = lang('WorkExpertise.error_add');
			return $this->setResponseFormat("json")->respond($response, 201);
		}
	}

	public function cancelUpload()
	{
		$response = [];
		$modelWorkExpertise = new WorkExpertiseModel(); // Load model
		$add = $modelWorkExpertise->cancelUpload();

		if ($add) {
			$response['status'] = 'success';
			$response['message'] = lang('WorkExpertise.success_upload_cancel');
			return $this->setResponseFormat("json")->respond($response, 200);
		} else {
			$response['status'] = 'error';
			$response['message'] = lang('WorkExpertise.error_add');
			return $this->setResponseFormat("json")->respond($response, 201);
		}
	}
}
