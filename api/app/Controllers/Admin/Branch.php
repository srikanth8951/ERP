<?php

namespace App\Controllers\Admin;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use CodeIgniter\RESTful\ResourceController;
use App\Libraries\Auth\User as AuthUser;
use App\Models\Admin\BranchModel;
use App\Models\Admin\RegionModel;
use App\Models\Admin\EmployeeModel;

class Branch extends ResourceController
{


	public function index()
	{

		$this->validatePermission('view_branch');	// Check permission
		$modelBranch = new BranchModel(); // Load model

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

		$total_branches = $modelBranch->getTotalBranches($filter_data);
		$branches = $modelBranch->getBranches($filter_data);

		$modelRegion = new RegionModel(); // Load model

		$rfilter_data = array(
			'removed' => 0,
			'status' => 1
		);

		//upload data exixt
		$uploadBranch = $modelBranch->cancelUpload();
		//end


		if ($branches) {
			$response = array(
				'status' => 'success',
				'message' => lang('Branch.success_list'),
				'branches' => $branches,
				'pagination' => array(
					'total' => (int)$total_branches,
					'length' => $limit,
					'start' => $start,
					'records' => count($branches)
				)
			);
			return $this->setResponseFormat("json")->respond($response, 200);
		} else {
			$response = array(
				'status' => 'error',
				'message' => lang('Branch.error_list')
			);
			return $this->setResponseFormat("json")->respond($response, 201);
		}
	}

	public function getBranch()
	{
		$response = array();
		$this->validatePermission('view_branch');	// Check permission

		$modelBranch = new BranchModel(); // Load model

		$branch_id = $this->request->getVar('branch_id');
		$branch = $modelBranch->getBranch($branch_id);
		if ($branch) {
			$response['status'] = 'success';
			$response['message'] = lang('Branch.success_detail');
			$response['branch'] = $branch;
			return $this->setResponseFormat("json")->respond($response, 200);
		} else {
			$response['status'] = 'error';
			$response['message'] = lang('Branch.error_detail');
			return $this->setResponseFormat("json")->respond($response, 201);
		}
	}

	public function addBranch()
	{
		$response = array();
		$this->validatePermission('add_branch');	// Check permission

		$rules = [
			"region_id"   => "required",
			// 'area_id'     => "required",
			"branch_name" => "required",
			"branch_code" => "required"
		];

		$messages = [
			"region_id" => [
				"required" => "region is required"
			],
			// "area_id" => [
			// 	"required" => "area is required"
			// ],
			"branch_name" => [
				"required" => "branch name is required"
			],
			"branch_code" => [
				"required" => "branch code is required"
			]
		];
		if ($this->validate($rules, $messages)) {
			$modelBranch = new BranchModel(); // Load model
			$branch_name = $this->request->getPost('branch_name');
			$region_id = $this->request->getPost('region_id');
			// $area_id = $this->request->getPost('area_id');
			$branch_code = $this->request->getPost('branch_code');
			$branch_data = array(
				'region_id' => $region_id,
				// 'area_id' => $area_id,
				'name' => $branch_name,
				'code' => $branch_code,
				'is_exist' => 0,
				'status' => 1
			);
			$filter_data = array(
				'region_id' => $region_id,
				'code' => $branch_data['code'], 'removed' => 0
			);
			$branch = $modelBranch->getBranchByName($branch_name, $filter_data);
			if ($branch) {
				$response['status'] = 'error';
				$response['message'] = lang('Branch.error_exist');
				return $this->setResponseFormat("json")->respond($response, 201);
			} else {
				$add = $modelBranch->addBranch($branch_data);
				if ($add) {
					$response['status'] = 'success';
					$response['message'] = lang('Branch.success_add');
					return $this->setResponseFormat("json")->respond($response, 200);
				} else {
					$response['status'] = 'error';
					$response['message'] = lang('Branch.error_add');
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

	public function editBranch()
	{
		$response = array();

		$this->validatePermission('edit_branch');	// Check permission

		$rules = [
			"region_id" => "required",
			// 'area_id' => "required",
			"branch_name" => "required",
			"branch_code" => "required"
		];

		$messages = [
			"region_id" => [
				"required" => "region is required"
			],
			// "area_id" => [
			// 	"required" => "area is required"
			// ],
			"branch_name" => [
				"required" => "branch name is required"
			],
			"branch_code" => [
				"required" => "branch code is required"
			]
		];
		if ($this->validate($rules, $messages)) {
			$modelBranch = new BranchModel(); // Load model

			$branch_id = $this->request->getVar('branch_id');

			$branch = $modelBranch->getBranch($branch_id);
			if ($branch) {
				$branch_data = array(
					'region_id' => $this->request->getPost('region_id'),
					'name' => $this->request->getPost('branch_name'),
					'code' => $this->request->getPost('branch_code'),
					'status' => $branch->status
				);
				$filter_data = array(
					'removed' => 0,
					'code' => $branch_data['code'],
					'region_id' => $branch_data['region_id'],
					'except' => [$branch_id]
				);
				$branch_name = $modelBranch->getBranchByName($branch_data['name'], $filter_data);
				if ($branch_name) {
					$response['status'] = 'error';
					$response['message'] = lang('Branch.error_exist');
					return $this->setResponseFormat("json")->respond($response, 201);
				} else {
					$edit = $modelBranch->editBranch($branch_id, $branch_data);
					if ($edit) {
						$response['status'] = 'success';
						$response['message'] = lang('Branch.success_edit');
						return $this->setResponseFormat("json")->respond($response, 200);
					} else {
						$response['status'] = 'error';
						$response['message'] = lang('Branch.error_edit');
						return $this->setResponseFormat("json")->respond($response, 201);
					}
				}
			} else {
				$response['status'] = 'error';
				$response['message'] = lang('Branch.error_detail');
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

	public function deleteBranch()
	{
		$response = array();

		$this->validatePermission('edit_branch');	// Check permission
		$modelBranch = new BranchModel(); // Load model
		$modelEmployee = new EmployeeModel(); // Load model

		$branch_id = $this->request->getVar('branch_id');

		$filter_data = array(
			'removed' => 0,
			'branch_id' => $branch_id
		);
		$employees = $modelEmployee->getEmployees($filter_data);
		if ($employees) {
			$response['status'] = 'error';
			$response['message'] = lang('Branch.taged_to_employee');
			return $this->setResponseFormat("json")->respond($response, 201);
		} else {
			$branch = $modelBranch->getBranch($branch_id);
			if ($branch) {
				$remove = $modelBranch->removeBranch($branch_id);
				if ($remove) {
					$response['status'] = 'success';
					$response['message'] = lang('Branch.success_removed');
					return $this->setResponseFormat("json")->respond($response, 200);
				} else {
					$response['status'] = 'error';
					$response['message'] = lang('Branch.error_removed');
					return $this->setResponseFormat("json")->respond($response, 201);
				}
			} else {
				$response['status'] = 'error';
				$response['message'] = lang('Branch.error_detail');
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
		$this->validatePermission('view_branch');	// Check permission
		$modelBranch = new BranchModel(); // Load model

		$region_id = $this->request->getVar('region_id');
		$filter_data = array(
			'region_id' => $region_id ?? 0,
			'removed' => 0,
			'status' => 1
		);
		$branchArray = array();
		$branches = $modelBranch->getBranches($filter_data);
		//print_r($branches);
		if ($branches) {
			foreach ($branches as $branch) {
				$branchArray[] = array(
					'id' => (int)$branch->branch_id,
					'name' => html_entity_decode($branch->name)
				);
			}
		}
		$response = array(
			'status' => 'success',
			'branches' => $branchArray,
		);

		return $this->setResponseFormat("json")->respond($response, 200);
	}

	// sample file download
	public function downloadSample()
	{
		$content = $this->createExcel();
		$response = array(
			'status' => 'success',
			'message' => lang('Branch.success_sample_download'),
			'content' => 'data:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;base64,' . base64_encode($content),
		);

		return $this->setResponseFormat("json")->respond($response, 200);
	}

	/** create excel 
	 *** return excel content  
	 **/
	public function createExcel()
	{
		$modelRegion = new RegionModel(); // Load model

		$rfilter_data = array(
			'removed' => 0,
			'status' => 1
		);

		$regionArray = array();
		$regions = $modelRegion->getRegions($rfilter_data);
		// Create a new Spreadsheet object
		$spreadsheet = new Spreadsheet();

		// Retrieve the current active worksheet
		$sheet = $spreadsheet->getActiveSheet();

		$configs = '"';
		foreach ($regions as $config) {
			$configs .= $config->name . '-' . $config->region_id . ', ';
		}
		$configs .= '"';

		$sheet->setCellValue('A1', 'Region');
		$sheet->setCellValue('B1', 'Name');
		$sheet->setCellValue('C1', 'Code');

		for ($x = 2; $x < 30; $x++) {

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

		$fileName = 'branch-' . date('YmdHis') . '.xlsx';
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
			//echo WRITEPATH . 'uploads';
			$modelbranch = new branchModel(); // Load model
			if ($upload['status'] == 'success') {

				$file = fopen(WRITEPATH . "uploads/" . $upload['image'], "r");
				$i = 0;
				$numberOfFields = 3;
				$csvArr = array();
				$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();

				// $reader= PHPExcel_IOFactory::createReader('Excel2007');
				$reader->setReadDataOnly(true);
				$path = (WRITEPATH . "uploads/" . $upload['image']);
				$excel = $reader->load($path);
				$sheet = $excel->setActiveSheetIndex(0);
				$allDataInSheet = $excel->getActiveSheet()->toArray(null, true, true, true);
				$arrayCount = count($allDataInSheet);  // Here get total count of row in that Excel sheet
				$branchArr = array();

				for ($i = 2; $i <= $arrayCount; $i++) {
					$reg =  explode('-', $allDataInSheet[$i]["A"]);
					$branchArr[$i]["name"] =  isset($allDataInSheet[$i]["B"]) ? trim($allDataInSheet[$i]["B"]) : '';
					$branchArr[$i]["code"] = isset($allDataInSheet[$i]["C"]) ? trim($allDataInSheet[$i]["C"]) : '';
					$branchArr[$i]["region_id"] = $reg[1] ?? 0;
					$branchArr[$i]['status'] = 0;
					$branchArr[$i]['is_exist'] = 3;
					$branchArr[$i]['removed'] = 0;
					$branchArr[$i]['created_datetime'] = date('Y-m-d H:i:s');
				}
				fclose($file);
				foreach ($branchArr as $userdata) {
					$dfilter_data = array(
						'removed' => 0,
						'status' => 1,
						'is_exist' => 0,
						'code' => $userdata['code']
					);

					$rdetails = $modelbranch->getBranchByCodeExist($dfilter_data);

					if (!empty($rdetails)) {
						$modelbranch->updateBranch($rdetails->branch_id, $dfilter_data);
					}

					if ($userdata['code'] && $userdata['name'] && $userdata['region_id']) {
						$add = $modelbranch->addBranch($userdata);
					}

					$nfilter_data = array(
						'removed' => 0,
						'status' => 1,
						'is_exist' => 2,
						'code' => $userdata['code']
					);

					$fdetails = $modelbranch->getBranchByCodeExists($nfilter_data);

					if ($fdetails) {
						$modelbranch->updateBranchDetails($fdetails->code);
					}

					$rfilter_data = array(
						'removed' => 0,
						'is_exist' => 0
					);

					$final = $modelbranch->getBranchList($rfilter_data);
				}

				$path_to_file = WRITEPATH . "uploads/" . $upload['image'];
				unlink($path_to_file);

				$response = array(
					'status' => 'success',
					'upload' => $final
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


	public function saveBranch()
	{
		$response = [];
		$modelbranch = new branchModel(); // Load model

		$ids = $modelbranch->getEmptyBranchList();
		if (!empty($ids)) {
			foreach ($ids as $val) {
				$branch = array('remove_branch' => $val->branch_id);
				$modelbranch->removeEmptyBranchList($branch);
			}
		}

		$add = $modelbranch->updateUploadBranch();

		if ($add) {
			$response['status'] = 'success';
			$response['message'] = lang('branch.success_add');
			return $this->setResponseFormat("json")->respond($response, 200);
		} else {
			$response['status'] = 'error';
			$response['message'] = lang('branch.error_add');
			return $this->setResponseFormat("json")->respond($response, 201);
		}
	}

	public function cancel()
	{
		$response = [];
		$modelbranch = new branchModel(); // Load model

		$add = $modelbranch->cancelUpload();

		if ($add) {
			$response['status'] = 'success';
			$response['message'] = lang('branch.success_upload_cancel');
			return $this->setResponseFormat("json")->respond($response, 200);
		} else {
			$response['status'] = 'error';
			$response['message'] = lang('branch.error_add');
			return $this->setResponseFormat("json")->respond($response, 201);
		}
	}
}
