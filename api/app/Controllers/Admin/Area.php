<?php

namespace App\Controllers\Admin;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use CodeIgniter\RESTful\ResourceController;
use App\Libraries\Auth\User as AuthUser;
use App\Models\Admin\AreaModel;
use App\Models\Admin\BranchModel;
use App\Models\Admin\RegionModel;
use App\Models\Admin\EmployeeModel;

class Area extends ResourceController
{
	public function index()
	{

		$this->validatePermission('view_area');	// Check permission
		$modelArea = new AreaModel(); // Load model

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

		$total_areas = $modelArea->getTotalAreas($filter_data);
		$areas = $modelArea->getAreas($filter_data);

		//upload data exixt
		$uploadArea = $modelArea->cancelUpload();
		//end

		if ($areas) {
			$response = array(
				'status' => 'success',
				'message' => lang('Area.success_list'),
				'areas' => $areas,
				'pagination' => array(
					'total' => (int)$total_areas,
					'length' => $limit,
					'start' => $start,
					'records' => count($areas)
				)
			);
			return $this->setResponseFormat("json")->respond($response, 200);
		} else {
			$response = array(
				'status' => 'error',
				'message' => lang('Area.error_list')
			);
			return $this->setResponseFormat("json")->respond($response, 201);
		}
	}

	public function getArea()
	{
		$response = array();
		$this->validatePermission('view_area');	// Check permission

		$modelArea = new AreaModel(); // Load model

		$area_id = $this->request->getVar('area_id');
		$area = $modelArea->getArea($area_id);
		if ($area) {
			$response['status'] = 'success';
			$response['message'] = lang('Area.success_detail');
			$response['area'] = $area;
			return $this->setResponseFormat("json")->respond($response, 200);
		} else {
			$response['status'] = 'error';
			$response['message'] = lang('Area.error_detail');
			return $this->setResponseFormat("json")->respond($response, 201);
		}
	}

	public function addArea()
	{
		$response = array();
		$this->validatePermission('add_area');	// Check permission

		$rules = [
			"region_id" => "required",
			"branch_id" => "required",
			"area_name" => "required",
			"area_code" => "required"
		];

		$messages = [
			"region_id" => [
				"required" => "region is required"
			],
			"branch_id" => [
				"required" => "branch is required"
			],
			"area_name" => [
				"required" => "area name is required"
			],
			"area_code" => [
				"required" => "area code is required"
			]
		];
		if ($this->validate($rules, $messages)) {
			$modelArea = new AreaModel(); // Load model
			$area_name = $this->request->getPost('area_name');
			$area_code = $this->request->getPost('area_code');
			$region_id = $this->request->getPost('region_id');
			$branch_id = $this->request->getPost('branch_id');
			$area_data = array(
				'region_id' => $region_id,
				'branch_id' => $branch_id,
				'name' => $area_name,
				'code' => $area_code,
				'is_exist' => 0,
				'status' => 1
			);
			$filter_data = array(
				'region_id' => $region_id,
				'branch_id' => $branch_id,
				'code' => $area_code,
				'removed' => 0
			);
			$area_name = $modelArea->getAreaByName($area_name, $filter_data);
			$area_code = $modelArea->getAreaByCode($area_code, $filter_data);

			if ($area_name) {
				$response['status'] = 'error';
				$response['message'] = lang('Area.error_exist');
				return $this->setResponseFormat("json")->respond($response, 201);
			} else {
				$add = $modelArea->addArea($area_data);
				if ($add) {
					$response['status'] = 'success';
					$response['message'] = lang('Area.success_add');
					return $this->setResponseFormat("json")->respond($response, 200);
				} else {
					$response['status'] = 'error';
					$response['message'] = lang('Area.error_add');
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

	public function editArea()
	{
		$response = array();

		$this->validatePermission('edit_area');	// Check permission

		$rules = [
			"region_id" => "required",
			"area_name" => "required",
			"area_code" => "required"
		];

		$messages = [
			"region_id" => [
				"required" => "region is required"
			],
			"area_name" => [
				"required" => "area name is required"
			],
			"area_code" => [
				"required" => "area code is required"
			]
		];
		if ($this->validate($rules, $messages)) {
			$modelArea = new AreaModel(); // Load model

			$area_id = $this->request->getVar('area_id');

			$area = $modelArea->getArea($area_id);
			$region_id = $this->request->getPost('region_id');

			if ($area) {
				$area_data = array(
					'region_id' => $this->request->getPost('region_id'),
					'branch_id' => $this->request->getPost('branch_id'),
					'name' => $this->request->getPost('area_name'),
					'code' => $this->request->getPost('area_code'),
					'status' => 1
				);
				$filter_data = array(
					'removed' => 0,
					'region_id' => $area_data['region_id'],
					'branch_id' => $area_data['branch_id'],
					'code' => $area_data['code'],
					'except' => [$area_id]
				);
				$area_name = $modelArea->getAreaByName($area_data['name'], $filter_data);
				// $area_code = $modelArea->getAreaByCode($area_data['code'], $filter_data);
				// print_r($area_name);
				if ($area_name) {
					$response['status'] = 'error';
					$response['message'] = lang('Area.error_exist');
					return $this->setResponseFormat("json")->respond($response, 201);
				} else {
					$edit = $modelArea->editArea($area_id, $area_data);
					if ($edit) {
						$response['status'] = 'success';
						$response['message'] = lang('Area.success_edit');
						return $this->setResponseFormat("json")->respond($response, 200);
					} else {
						$response['status'] = 'error';
						$response['message'] = lang('Area.error_edit');
						return $this->setResponseFormat("json")->respond($response, 201);
					}
				}
			} else {
				$response['status'] = 'error';
				$response['message'] = lang('Area.error_detail');
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

	public function deleteArea()
	{
		$response = array();

		$this->validatePermission('edit_area');	// Check permission
		$modelArea = new AreaModel(); // Load model
		$modelEmployee = new EmployeeModel(); // Load model

		$area_id = $this->request->getVar('area_id');
		$filter_data = array(
			'removed' => 0,
			'area_id' => $area_id
		);
		$employees = $modelEmployee->getEmployees($filter_data);
		if ($employees) {
			$response['status'] = 'error';
			$response['message'] = lang('Branch.taged_to_employee');
			return $this->setResponseFormat("json")->respond($response, 201);
		} else {
		$area = $modelArea->getArea($area_id);
		if ($area) {
			$remove = $modelArea->removeArea($area_id);
			if ($remove) {
				$response['status'] = 'success';
				$response['message'] = lang('Area.success_removed');
				return $this->setResponseFormat("json")->respond($response, 200);
			} else {
				$response['status'] = 'error';
				$response['message'] = lang('Area.error_removed');
				return $this->setResponseFormat("json")->respond($response, 201);
			}
		} else {
			$response['status'] = 'error';
			$response['message'] = lang('Area.error_detail');
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
		$this->validatePermission('view_area');	// Check permission
		$modelArea = new AreaModel(); // Load model

		$branch_id = $this->request->getVar('branch_id');
		$filter_data = array(
			'branch_id' => $branch_id ?? 0,
			'removed' => 0,
			'status' => 1
		);
		$areaArray = array();
		$areas = $modelArea->getAreas($filter_data);
		if ($areas) {
			foreach ($areas as $area) {
				$areaArray[] = array(
					'id' => (int)$area->area_id,
					'name' => html_entity_decode($area->name)
				);
			}
		}
		$response = array(
			'status' => 'success',
			'areas' => $areaArray,
		);

		return $this->setResponseFormat("json")->respond($response, 200);
	}

	// sample file download
	public function downloadSample()
	{
		$content = $this->createExcel();
		$response = array(
			'status' => 'success',
			'message' => lang('Area.success_sample_download'),
			'content' => 'data:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;base64,' . base64_encode($content),
		);

		return $this->setResponseFormat("json")->respond($response, 200);
	}

	/** create excel 
	 *** return excel content  
	 **/
	protected function createExcel()
	{
		$modelRegion = new RegionModel(); // Load model
		$modelBranch = new BranchModel(); // Load model

		$rfilter_data = array(
			'removed' => 0,
			'status' => 1
		);

		$regions = $modelRegion->getRegions($rfilter_data);
		$branches = $modelBranch->getBranches($rfilter_data);

		// Create a new Spreadsheet object
		$spreadsheet = new Spreadsheet();

		// Retrieve the current active worksheet
		$sheet = $spreadsheet->getActiveSheet();

		$configs = '"';
		foreach ($regions as $config) {
			$configs .= $config->name . '-' . $config->region_id . ', ';
		}
		$configs .= '"';


		$configs1 = '"';
		foreach ($branches as $config1) {
			$configs1 .= $config1->name . '-' . $config1->branch_id . ', ';
		}
		$configs1 .= '"';

		$sheet->setCellValue('A1', 'Region');
		$sheet->setCellValue('B1', 'Branch');
		$sheet->setCellValue('C1', 'Name');
		$sheet->setCellValue('D1', 'Code');

		for ($x = 2; $x < 100; $x++) {

			$validation = $sheet->getCell('A' . $x)->getDataValidation();

			$validation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);

			$validation->setFormula1($configs);

			$validation->setAllowBlank(false);

			$validation->setShowDropDown(true);

			$validation->setShowInputMessage(true);

			$validation->setPromptTitle('Regions');

			$validation->setPrompt('Choose Region');

			$validation->setShowErrorMessage(true);

			$validation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP);

			$validation->setErrorTitle('Invalid option');

			$validation->setError('Select one from the drop down list.');
		}

		for ($x = 2; $x < 30; $x++) {

			$validation1 = $sheet->getCell('B' . $x)->getDataValidation();
			$validation1->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
			$validation1->setFormula1($configs1);
			$validation1->setAllowBlank(false);
			$validation1->setShowDropDown(true);
			$validation1->setShowInputMessage(true);
			$validation1->setPromptTitle('Branchs');
			$validation1->setPrompt('Choose Branch');
			$validation1->setShowErrorMessage(true);
			$validation1->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP);
			$validation1->setErrorTitle('Invalid option');
			$validation1->setError('Select one from the drop down list.');
		}

		$fileName = 'area-' . date('YmdHis') . '.xlsx';
		$writer = new Xlsx($spreadsheet);

		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename="' . urlencode($fileName) . '"');

		ob_start();
		$writer->save('php://output');
		$xlxsData = ob_get_contents();
		ob_end_clean();

		return $xlxsData;
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

			$modelArea = new AreaModel(); // Load model

			if ($upload['status'] == 'success') {

				$file = fopen(WRITEPATH . "uploads/" . $upload['image'], "r");
				$i = 0;
				$numberOfFields = 4;
				$csvArr = array();
				$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
				$reader->setReadDataOnly(true);
				$path = (WRITEPATH . "uploads/" . $upload['image']);
				$excel = $reader->load($path);
				$sheet = $excel->setActiveSheetIndex(0);
				$allDataInSheet = $excel->getActiveSheet()->toArray(null, true, true, true);
				$arrayCount = count($allDataInSheet);  // Here get total count of row in that Excel sheet
				$areaArr = array();

				for ($i = 2; $i <= $arrayCount; $i++) {
					$reg =  explode('-', $allDataInSheet[$i]["A"]);
					$bId =  explode('-', $allDataInSheet[$i]["B"]);
					$areaArr[$i]["name"] =  isset($allDataInSheet[$i]["C"]) ? trim($allDataInSheet[$i]["C"]) : '';
					$areaArr[$i]["code"] = isset($allDataInSheet[$i]["D"]) ? trim($allDataInSheet[$i]["D"]) : '';
					$areaArr[$i]["region_id"] = $reg[1] ?? 0;
					$areaArr[$i]["branch_id"] = $bId[1] ?? 0;
					$areaArr[$i]['status'] = 0;
					$areaArr[$i]['is_exist'] = 3;
					$areaArr[$i]['removed'] = 0;
					$areaArr[$i]['created_datetime'] = date('Y-m-d H:i:s');
				}
				fclose($file);
				foreach ($areaArr as $userdata) {
					$dfilter_data = array(
						'removed' => 0,
						'status' => 1,
						'is_exist' => 0,
						'code' => $userdata['code']
					);

					$rdetails = $modelArea->getAreaExist($dfilter_data);

					if (!empty($rdetails)) {
						$modelArea->updateArea($rdetails->area_id, $dfilter_data);
					}

					if ($userdata['code'] && $userdata['name'] && $userdata['region_id'] && $userdata['branch_id']) {
						$add = $modelArea->addArea($userdata);
					}
					$nfilter_data = array(
						'removed' => 0,
						'status' => 1,
						'is_exist' => 2,
						'code' => $userdata['code']
					);

					$fdetails = $modelArea->getAreaExists($nfilter_data);

					if ($fdetails) {
						$modelArea->updateAreaDetails($fdetails->code);
					}

					$rfilter_data = array(
						'removed' => 0,
						'is_exist' => 0,
						'type' => 'upload'
					);

					$final = $modelArea->getAreaList($rfilter_data);
				}

				$path_to_file = WRITEPATH . "uploads/" . $upload['image'];
				unlink($path_to_file);

				$response = array(
					'status' => 'success',
					'upload_areas' => $final
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


	public function saveArea()
	{
		$response = [];
		$modelArea = new areaModel(); // Load model

		$ids = $modelArea->getEmptyAreaList();
		if (!empty($ids)) {
			foreach ($ids as $val) {
				$area = $val->area_id;

				$modelArea->removeEmptyAreaList($area);
			}
		}

		$add = $modelArea->updateUploadArea();

		if ($add) {
			$response['status'] = 'success';
			$response['message'] = lang('area.success_add');
			return $this->setResponseFormat("json")->respond($response, 200);
		} else {
			$response['status'] = 'error';
			$response['message'] = lang('area.error_add');
			return $this->setResponseFormat("json")->respond($response, 201);
		}
	}

	public function cancel()
	{
		$response = [];
		$modelArea = new areaModel(); // Load model 
		$add = $modelArea->cancelUpload();

		if ($add) {
			$response['status'] = 'success';
			$response['message'] = lang('area.success_upload_cancel');
			return $this->setResponseFormat("json")->respond($response, 200);
		} else {
			$response['status'] = 'error';
			$response['message'] = lang('area.error_add');
			return $this->setResponseFormat("json")->respond($response, 201);
		}
	}
}
