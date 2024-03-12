<?php

namespace App\Controllers\Admin;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use CodeIgniter\RESTful\ResourceController;
use App\Libraries\Auth\User as AuthUser;
use App\Models\Admin\RegionModel;
use App\Models\Admin\EmployeeModel;

class Region extends ResourceController
{

	function __construct()
	{
	}

	public function index()
	{

		$this->validatePermission('view_area');	// Check permission
		$modelRegion = new RegionModel(); // Load model

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

		$total_regions = $modelRegion->getTotalRegions($filter_data);
		$regions = $modelRegion->getRegions($filter_data);

		//upload data exixt
		$uploadRegion = $modelRegion->cancelUpload();
		//end

		if ($regions) {
			$response = array(
				'status' => 'success',
				'message' => lang('Region.success_list'),
				'regions' => $regions,
				'pagination' => array(
					'total' => (int)$total_regions,
					'length' => $limit,
					'start' => $start,
					'records' => count($regions)
				)
			);
			return $this->setResponseFormat("json")->respond($response, 200);
		} else {
			$response = array(
				'status' => 'success',
				'message' => lang('Region.error_list')
			);
			return $this->setResponseFormat("json")->respond($response, 201);
		}
	}

	public function getRegion()
	{
		$response = array();
		$this->validatePermission('view_region');	// Check permission

		$modelRegion = new RegionModel(); // Load model

		$region_id = $this->request->getVar('region_id');
		$region = $modelRegion->getRegion($region_id);
		if ($region) {
			$response['status'] = 'success';
			$response['message'] = lang('Region.success_detail');
			$response['region'] = $region;
			return $this->setResponseFormat("json")->respond($response, 200);
		} else {
			$response['status'] = 'error';
			$response['message'] = lang('Region.error_detail');
			return $this->setResponseFormat("json")->respond($response, 201);
		}
	}

	public function addRegion()
	{
		$response = array();
		$this->validatePermission('add_region');	// Check permission

		$rules = [
			"region_name" => "required",
			"region_code" => "required"
		];

		$messages = [
			"region_name" => [
				"required" => "region name is required"
			],
			"region_code" => [
				"required" => "region code is required"
			]
		];
		if ($this->validate($rules, $messages)) {
			$modelRegion = new RegionModel(); // Load model
			$region_data = array(
				'name' => $this->request->getPost('region_name'),
				'code' => $this->request->getPost('region_code'),
				'is_exist' => 0,
				'status' => 1
			);
			$filter_data = array('removed' => 0, $region_data['code'],);
			$region_name = $modelRegion->getRegionByName($region_data['name'], $filter_data);
			if ($region_name) {
				$response['status'] = 'error';
				$response['message'] = lang('Name already exist');
				return $this->setResponseFormat("json")->respond($response, 201);
			} else {
				$add = $modelRegion->addRegion($region_data);
				if ($add) {
					$response['status'] = 'success';
					$response['message'] = lang('Region.success_add');
					return $this->setResponseFormat("json")->respond($response, 200);
				} else {
					$response['status'] = 'error';
					$response['message'] = lang('Region.error_add');
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

	public function editRegion()
	{
		$response = array();

		$this->validatePermission('edit_region');	// Check permission

		$rules = [
			"region_name" => "required",
			"region_code" => "required"
		];

		$messages = [
			"region_name" => [
				"required" => "region name is required"
			],
			"region_code" => [
				"required" => "region code is required"
			]
		];
		if ($this->validate($rules, $messages)) {
			$modelRegion = new RegionModel(); // Load model

			$region_id = $this->request->getVar('region_id');

			$region = $modelRegion->getRegion($region_id);
			if ($region) {
				$region_data = array(
					'name' => $this->request->getPost('region_name'),
					'code' => $this->request->getPost('region_code'),
					'status' => $region->status
				);
				$filter_data = array('removed' => 0, $region_data['code'], 'except' => [$region_id]);
				$region_name = $modelRegion->getRegionByName($region_data['name'], $filter_data);
				if ($region_name) {
					$response['status'] = 'error';
					$response['message'] = lang('Name already exist');
					return $this->setResponseFormat("json")->respond($response, 201);
				} else {
					$edit = $modelRegion->editRegion($region_id, $region_data);
					if ($edit) {
						$response['status'] = 'success';
						$response['message'] = lang('Region.success_edit');
						return $this->setResponseFormat("json")->respond($response, 200);
					} else {
						$response['status'] = 'error';
						$response['message'] = lang('Region.error_edit');
						return $this->setResponseFormat("json")->respond($response, 201);
					}
				}
			} else {
				$response['status'] = 'error';
				$response['message'] = lang('Region.error_detail');
				return $this->setResponseFormat("json")->respond($response, 201);
			}
		} else {
			$response = [
				'status' => 'error',
				'message' => $this->validator->getErrors()
			];
		}
	}

	public function deleteRegion()
	{
		$response = array();

		$this->validatePermission('edit_region');	// Check permission
		$modelRegion = new RegionModel(); // Load model
		$modelEmployee = new EmployeeModel(); // Load model

		$region_id = $this->request->getVar('region_id');
		$filter_data = array(
			'removed' => 0,
			'region_id' => $region_id
		);
		$employees = $modelEmployee->getEmployees($filter_data);
		if ($employees) {
			$response['status'] = 'error';
			$response['message'] = lang('Region.taged_to_employee');
			return $this->setResponseFormat("json")->respond($response, 201);
		} else {
			$region = $modelRegion->getRegion($region_id);
			if ($region) {
				$remove = $modelRegion->removeRegion($region_id);
				if ($remove) {
					$response['status'] = 'success';
					$response['message'] = lang('Region.success_removed');
					return $this->setResponseFormat("json")->respond($response, 200);
				} else {
					$response['status'] = 'error';
					$response['message'] = lang('Region.error_removed');
					return $this->setResponseFormat("json")->respond($response, 201);
				}
			} else {
				$response['status'] = 'error';
				$response['message'] = lang('Region.error_detail');
				return $this->setResponseFormat("json")->respond($response, 201);
			}
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
		$this->validatePermission('view_region');	// Check permission
		$modelRegion = new RegionModel(); // Load model

		$filter_data = array(
			'removed' => 0,
			'status' => 1
		);
		$regionArray = array();
		$regions = $modelRegion->getRegions($filter_data);
		if ($regions) {
			foreach ($regions as $region) {
				$regionArray[] = array(
					'id' => (int)$region->region_id,
					'name' => html_entity_decode($region->name)
				);
			}
		}
		$response = array(
			'status' => 'success',
			'regions' => $regionArray,
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
			$modelRegion = new RegionModel(); // Load model
			if ($upload['status'] == 'success') {

				$file = fopen(WRITEPATH . "uploads/" . $upload['image'], "r");
				$i = 0;
				$numberOfFields = 2;
				$csvArr = array();

				while (($filedata = fgetcsv($file, 1000, ",")) !== FALSE) {
					$num = count($filedata);
					if ($i > 0 && $num == $numberOfFields) {

						$csvArr[$i]['name'] = isset($filedata[0]) ? trim($filedata[0]) : '';
						$csvArr[$i]['code'] = isset($filedata[1]) ? trim($filedata[1]) : '';
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
						'code' => $userdata['code']
					);

					$rdetails = $modelRegion->getRegionByCodeExist($dfilter_data);

					if (!empty($rdetails)) {
						$modelRegion->updateRegion($rdetails->region_id, $dfilter_data);
					}

					if ($userdata['code'] && $userdata['name']) {
						$add = $modelRegion->addRegion($userdata);
					}

					$nfilter_data = array(
						'removed' => 0,
						'status' => 1,
						'is_exist' => 2,
						'code' => $userdata['code']
					);

					$fdetails = $modelRegion->getRegionByCodeExist1($nfilter_data);

					if ($fdetails) {
						$modelRegion->updateRegionDetails($fdetails->code);
					}

					$rfilter_data = array(
						'removed' => 0,
						'is_exist' => 0
					);

					$final = $modelRegion->getRegionList($rfilter_data);
				}


				$path_to_file = WRITEPATH . "uploads/" . $upload['image'];
				unlink($path_to_file);

				$response = array(
					'status' => 'success',
					'upload_regions' => $final
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

	public function saveRegion()
	{
		$response = [];
		$modelRegion = new RegionModel(); // Load model
		$add = $modelRegion->updateUploadRegion();

		if ($add) {
			$response['status'] = 'success';
			$response['message'] = lang('Region.success_add');
			return $this->setResponseFormat("json")->respond($response, 200);
		} else {
			$response['status'] = 'error';
			$response['message'] = lang('Region.error_add');
			return $this->setResponseFormat("json")->respond($response, 201);
		}
	}

	public function cancel()
	{
		$response = [];
		$modelRegion = new RegionModel(); // Load model
		$add = $modelRegion->cancelUpload();

		if ($add) {
			$response['status'] = 'success';
			$response['message'] = lang('Region.success_upload_cancel');
			return $this->setResponseFormat("json")->respond($response, 200);
		} else {
			$response['status'] = 'error';
			$response['message'] = lang('Region.error_add');
			return $this->setResponseFormat("json")->respond($response, 201);
		}
	}
}
