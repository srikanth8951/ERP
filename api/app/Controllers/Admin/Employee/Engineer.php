<?php

namespace App\Controllers\Admin\Employee;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use CodeIgniter\RESTful\ResourceController;
use App\Libraries\Auth\User as AuthUser;
use App\Models\Admin\UserModel;
use App\Models\Admin\EmployeeModel;
use App\Models\Admin\LocalisationModel;
use App\Models\Admin\DepartmentModel;
use App\Models\Admin\DesignationModel;
use App\Models\Admin\WorkExpertiseModel;
use App\Models\Admin\RegionModel;
use App\Models\Admin\BranchModel;
use App\Models\Admin\AreaModel;

class Engineer extends ResourceController
{
	protected $empType = 'engineer';

	public function __construct()
	{
		helper('user');
	}


	public function index()
	{

		$this->validatePermission('view_engineer');	// Check permission
		$modelEmployee = new EmployeeModel(); // Load model

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

		$user_type = getUserTypeByCode($this->empType);

		$filter_data = array(
			'removed' => 0,
			'search' => $search,
			'start' => ($start - 1),
			'limit' => $limit,
			'sort' => $sort,
			'order' => $order,
			'is_exist' => 0,
			'user_type' => $user_type['type_id']
		);

		$total_engineers = $modelEmployee->getTotalEmployees($filter_data);
		$engineers = $modelEmployee->getEmployees($filter_data);

		if ($engineers) {
			$response = array(
				'status' => 'success',
				'message' => lang('Employee.Engineer.success_list'),
				'employees' => [
					'type' => $this->empType,
					'data' => $engineers,
					'pagination' => array(
						'total' => (int)$total_engineers,
						'length' => $limit,
						'start' => $start,
						'records' => count($engineers)
					)
				]
			);

			return $this->setResponseFormat("json")->respond($response, 200);
		} else {
			$response = array(
				'status' => 'error',
				'message' => lang('Employee.Engineer.error_list')
			);
			return $this->setResponseFormat("json")->respond($response, 201);
		}
	}

	public function getEmployee()
	{
		$response = array();
		$this->validatePermission('view_engineer');	// Check permission

		$modelEmployee = new EmployeeModel(); // Load model

		$employee_id = $this->request->getVar('employee_id');
		$engineer = $modelEmployee->getEmployee($employee_id);
		if ($engineer) {
			$response['status'] = 'success';
			$response['message'] = lang('Employee.Engineer.success_detail');
			$response['employee'] = [
				'type' => $this->empType,
				'data' => $engineer
			];
			return $this->setResponseFormat("json")->respond($response, 200);
		} else {
			$response['status'] = 'error';
			$response['message'] = lang('Employee.Engineer.error_detail');
			return $this->setResponseFormat("json")->respond($response, 201);
		}
	}

	public function addEmployee()
	{
		$response = array();
		$this->validatePermission('add_engineer');	// Check permission

		$rules = [
			"first_name" => "required",
			"email" => "required|valid_email",
			"mobile" => "required|numeric"
		];

		$messages = [
			"first_name" => [
				"required" => "First name is required"
			],
			"email" => [
				"required" => "Email is required",
				"valid_emil" => "Invalid email"
			],
			"mobile" => [
				"required" => "Mobile number is required",
				"numeric" => "Mobile number must be numeric"
			]
		];
		if ($this->validate($rules, $messages)) {

			$user_type = getUserTypeByCode($this->empType);
			$modelEmployee = new EmployeeModel(); // Load model

			$engineer_data = array_merge($this->request->getPost(null), [
				'user_type' => $user_type['type_id'],
				'is_exist' => 0
			]);

			$filter_data = array(
				'removed' => 0,
				'status' => 1,
				'username' => $engineer_data['username'],
				'email' => $engineer_data['email']
			);

			$engineer = $modelEmployee->getEmployeeByValidation($filter_data);
			if ($engineer) {
				$response['status'] = 'error';
				$response['message'] = lang('Employee.Engineer.error_exist');
				return $this->setResponseFormat("json")->respond($response, 201);
			} else {
				$add = $modelEmployee->addEmployee($engineer_data);
				if ($add) {
					$response['status'] = 'success';
					$response['message'] = lang('Employee.Engineer.success_add');
					return $this->setResponseFormat("json")->respond($response, 200);
				} else {
					$response['status'] = 'error';
					$response['message'] = lang('Employee.Engineer.error_add');
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

	public function editEmployee()
	{
		$response = array();

		$this->validatePermission('edit_engineer');	// Check permission

		$user_type = getUserTypeByCode($this->empType);

		$rules = [
			"first_name" => "required",
			"email" => "required|valid_email",
			"mobile" => "required|numeric"
		];

		$messages = [
			"first_name" => [
				"required" => "First name is required"
			],
			"email" => [
				"required" => "Email is required",
				"valid_emil" => "Invalid email"
			],
			"mobile" => [
				"required" => "mobile is required",
				"numeric" => "Mobile number must be numeric"
			]
		];
		if ($this->validate($rules, $messages)) {
			$modelEmployee = new EmployeeModel(); // Load model

			$engineer_data = array_merge($this->request->getPost(null), [
				'user_type' => $user_type['type_id']
			]);

			$employee_id = $this->request->getVar('employee_id');

			$engineer_detail = $modelEmployee->getEmployee($employee_id);

			if ($engineer_detail) {
				$filter_data = array(
					'removed' => 0,
					'status' => 1,
					'username' => $engineer_data['username'],
					'email' => $engineer_data['email'],
					'except' => [$engineer_detail->user_id]
				);

				$engineer = $modelEmployee->getEmployeeByValidation($filter_data);
				if ($engineer) {
					$response['status'] = 'error';
					$response['message'] = lang('Employee.Engineer.error_exist');
					return $this->setResponseFormat("json")->respond($response, 201);
				} else {
					$edit = $modelEmployee->editEmployee($employee_id, $engineer_data);
					if ($edit) {
						$response['status'] = 'success';
						$response['message'] = lang('Employee.Engineer.success_edit');
						return $this->setResponseFormat("json")->respond($response, 200);
					} else {
						$response['status'] = 'error';
						$response['message'] = lang('Employee.Engineer.error_edit');
						return $this->setResponseFormat("json")->respond($response, 201);
					}
				}
			} else {
				$response['status'] = 'error';
				$response['message'] = lang('Employee.Engineer.error_detail');
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

	public function deleteEmployee()
	{
		$response = array();

		$this->validatePermission('edit_engineer');	// Check permission
		$modelEmployee = new EmployeeModel(); // Load model

		$employee_id = $this->request->getVar('employee_id');
		$engineer = $modelEmployee->getEmployee($employee_id);
		if ($engineer) {
			//$remove = $modelEmployee->removeEmployee($employee_id);
			$remove = $modelEmployee->removeEmployee($employee_id, $engineer->user_id);

			if ($remove) {
				$response['status'] = 'success';
				$response['message'] = lang('Employee.Engineer.success_removed');
				return $this->setResponseFormat("json")->respond($response, 200);
			} else {
				$response['status'] = 'error';
				$response['message'] = lang('Employee.Engineer.error_removed');
				return $this->setResponseFormat("json")->respond($response, 201);
			}
		} else {
			$response['status'] = 'error';
			$response['message'] = lang('Employee.Engineer.error_detail');
			return $this->setResponseFormat("json")->respond($response, 201);
		}
	}

	public function setEmployeeStatus()
	{
		$response = array();

		//$this->validatePermission('edit_AISDHead');	// Check permission
		$modelUsers = new UserModel(); // Load model
		$modelEmployee = new EmployeeModel();

		$employee_id = $this->request->getVar('employee_id');
		$status = $this->request->getVar('status');
		$engineer = $modelEmployee->getEmployee($employee_id);
		if ($engineer) {
			$remove = $modelEmployee->setEmployeeStatus($employee_id, $status, $engineer->user_id);
			if ($remove) {
				$response['status'] = 'success';
				$response['message'] = lang('Employee.AISDHead.success_status');
				return $this->setResponseFormat("json")->respond($response, 200);
			} else {
				$response['status'] = 'error';
				$response['message'] = lang('Employee.AISDHead.error_removed');
				return $this->setResponseFormat("json")->respond($response, 201);
			}
		} else {
			$response['status'] = 'error';
			$response['message'] = lang('Employee.AISDHead.error_detail');
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
		$this->validatePermission('view_engineer');	// Check permission
		$modelEmployee = new EmployeeModel(); // Load model
		$user_type = getUserTypeByCode($this->empType);

		$camID = $this->request->getVar('cam_id');

		$filter_data = array(
			'removed' => 0,
			'user_type' => $user_type['type_id'],
			'status' => 1,
			'cam_id' => $camID
		);

		$engineerArray = array();
		$engineers = $modelEmployee->getEmployees($filter_data);
		// print_r($engineers);
		if ($engineers) {
			foreach ($engineers as $engineer) {
				$engineerArray[] = array(
					'id' => (int)$engineer->employee_id,
					'name' => html_entity_decode($engineer->first_name . ' ' . $engineer->last_name),
				);
			}
		}
		$response = array(
			'status' => 'success',
			'employee' => [
				'type' => $this->empType,
				'data' => $engineerArray
			]
		);

		return $this->setResponseFormat("json")->respond($response, 200);
	}

	// sample file download
	public function downloadSample()
	{
		$content = $this->createExcel();
		$response = array(
			'status' => 'success',
			'message' => lang('Employee.Engineer.success_sample_download'),
			'content' => 'data:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;base64,' . base64_encode($content),
		);

		return $this->setResponseFormat("json")->respond($response, 200);
	}

	/** create excel 
	 *** return excel content  
	 **/
	public function createExcel()
	{
		$modelLocalisation = new LocalisationModel(); // Load model
		$modelDepartment = new DepartmentModel(); // Load model
		$modelDesignation = new DesignationModel(); // Load model
		$modelWorkExpertise = new WorkExpertiseModel(); // Load model
		$modelRegion = new RegionModel(); // Load model
		$modelBranch = new BranchModel(); // Load model
		$modelArea = new AreaModel(); // Load model

		$afilter_data = array(
			// 'branch_id' => $branch_id ?? 0,
			'removed' => 0,
			'status' => 1
		);

		$cfilter_data = array(
			'removed' => 0,
			'status' => 1,
			'sort' => 'country_id',
			'order' => 'asc'
		);
		$filter_data = array(
			'removed' => 0,
			'status' => 1
		);
		$rfilter_data = array(
			'region_id' => $region_id ?? 0,
			'removed' => 0,
			'status' => 1
		);

		$departments = $modelDepartment->getDepartments($filter_data);
		$state = $modelLocalisation->getStatesList($filter_data);
		$country = $modelLocalisation->getCountries($cfilter_data);
		$designations = $modelDesignation->getDesignations($filter_data);
		$work_expertises = $modelWorkExpertise->getWorkExpertises($filter_data);
		$regions = $modelRegion->getRegions($rfilter_data);
		$branches = $modelBranch->getBranches($filter_data);
		$areas = $modelArea->getAreas($afilter_data);

		// Create a new Spreadsheet object
		$spreadsheet = new Spreadsheet();

		// Retrieve the current active worksheet
		$sheet = $spreadsheet->getActiveSheet();

		$sheet1 = $spreadsheet->getActiveSheet();

		$configs = '"';
		foreach ($departments as $config) {
			$configs .= $config->name . '-' . $config->department_id . ', ';
		}
		$configs .= '"';

		$configs2 = '"';
		foreach ($regions as $config2) {
			$configs2 .= $config2->name . '-' . $config2->region_id . ', ';
		}
		$configs2 .= '"';

		$configs3 = '"';
		foreach ($country as $config3) {
			$configs3 .= $config3->name . '-' . $config3->country_id . ', ';
		}
		$configs3 .= '"';


		$configs1 = '"';
		foreach ($state as $config1) {
			$configs1 .= $config1->name . '-' . $config1->state_id . ', ';
		}
		$configs1 .= '"';

		$configs4 = '"';
		foreach ($designations as $config4) {
			$configs4 .= $config4->name . '-' . $config4->designation_id . ', ';
		}
		$configs4 .= '"';

		$configs5 = '"';
		foreach ($work_expertises as $config5) {
			$configs5 .= $config5->name . '-' . $config5->work_expertise_id . ', ';
		}
		$configs5 .= '"';

		$configs6 = '"';
		foreach ($branches as $config6) {
			$configs6 .= $config6->name . '-' . $config6->branch_id . ', ';
		}
		$configs6 .= '"';

		$configs7 = '"';
		foreach ($areas as $config7) {
			$configs7 .= $config7->name . '-' . $config7->area_id . ', ';
		}
		$configs7 .= '"';

		$sheet->setCellValue('A1', 'First Name');
		$sheet->setCellValue('B1', 'Last Name');
		$sheet->setCellValue('C1', 'Email ID');
		$sheet->setCellValue('D1', 'Country');
		$sheet->setCellValue('E1', 'State');
		$sheet->setCellValue('F1', 'City');
		$sheet->setCellValue('G1', 'Mobile');
		$sheet->setCellValue('H1', 'Address');
		$sheet->setCellValue('I1', 'Pincode');
		$sheet->setCellValue('J1', 'Username');
		$sheet->setCellValue('K1', 'Password');
		$sheet->setCellValue('L1', 'Employee ID');
		$sheet->setCellValue('M1', 'Region');
		$sheet->setCellValue('N1', 'Branch');
		$sheet->setCellValue('O1', 'City');
		$sheet->setCellValue('P1', 'Department');
		$sheet->setCellValue('Q1', 'Designation');
		$sheet->setCellValue('R1', 'Work Expertise');
		$sheet->setCellValue('S1', 'Date of Joining');

		$spreadsheet->createSheet();
		// Create a new worksheet
		$myWorkSheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'Country List');
		$myWorkSheet1 = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'State List');

		// Attach the "My Data" worksheet as the first worksheet in the Spreadsheet object
		$spreadsheet->addSheet($myWorkSheet, 1);
		$spreadsheet->getSheet(1);
		$spreadsheet->addSheet($myWorkSheet1, 2);
		$spreadsheet->getSheet(2);

		$i = 1;
		foreach ($country as $provider) {
			//$sheet->setCellValue('T'.$i, $provider->country_id);
			$myWorkSheet->setCellValue('A' . $i, $provider->name . '-' . $provider->country_id);
			$i++;
		}

		$nbOfProvider = $sheet->getHighestRow('A');

		for ($j = 1; $j < 30; $j++) {
			$dropdownlist = $myWorkSheet->getCell('A' . $j)->getDataValidation();
			$dropdownlist->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST)
				->setFormula1('=\'PROVIDERS\'!$A$3:$A$' . $nbOfProvider);
		}

		//for state list
		$i = 1;
		foreach ($state as $provider) {
			$myWorkSheet1->setCellValue('A' . $i, $provider->name . '-' . $provider->state_id);
			$i++;
		}

		$nbOfProvider = $sheet->getHighestRow('A');
		for ($j = 1; $j < 30; $j++) {
			$dropdownlist = $myWorkSheet1->getCell('A' . $j)->getDataValidation();
			$dropdownlist->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST)
				->setFormula1('=\'STATES\'!$A$3:$A$' . $nbOfProvider);
		}

		for ($x = 2; $x < 30; $x++) {

			$validation2 = $sheet->getCell('M' . $x)->getDataValidation();
			$validation2->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
			$validation2->setFormula1($configs2);
			$validation2->setAllowBlank(false);
			$validation2->setShowDropDown(true);
			$validation2->setShowInputMessage(true);
			$validation2->setPromptTitle('Regions');
			$validation2->setPrompt('Choose Region');
			$validation2->setShowErrorMessage(true);
			$validation2->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP);
			$validation2->setErrorTitle('Invalid option');
			$validation2->setError('Select one from the drop down list.');

			$validation6 = $sheet->getCell('N' . $x)->getDataValidation();
			$validation6->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
			$validation6->setFormula1($configs6);
			$validation6->setAllowBlank(false);
			$validation6->setShowDropDown(true);
			$validation6->setShowInputMessage(true);
			$validation6->setPromptTitle('Branches');
			$validation6->setPrompt('Choose Branch');
			$validation6->setShowErrorMessage(true);
			$validation6->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP);
			$validation6->setErrorTitle('Invalid option');
			$validation6->setError('Select one from the drop down list.');

			$validation7 = $sheet->getCell('O' . $x)->getDataValidation();
			$validation7->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
			$validation7->setFormula1($configs7);
			$validation7->setAllowBlank(false);
			$validation7->setShowDropDown(true);
			$validation7->setShowInputMessage(true);
			$validation7->setPromptTitle('City');
			$validation7->setPrompt('Choose City');
			$validation7->setShowErrorMessage(true);
			$validation7->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP);
			$validation7->setErrorTitle('Invalid option');
			$validation7->setError('Select one from the drop down list.');

			$validation = $sheet->getCell('P' . $x)->getDataValidation();
			$validation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
			$validation->setFormula1($configs);
			$validation->setAllowBlank(false);
			$validation->setShowDropDown(true);
			$validation->setShowInputMessage(true);
			$validation->setPromptTitle('Departments');
			$validation->setPrompt('Choose Department');
			$validation->setShowErrorMessage(true);
			$validation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP);
			$validation->setErrorTitle('Invalid option');
			$validation->setError('Select one from the drop down list.');

			$validation1 = $sheet->getCell('Q' . $x)->getDataValidation();
			$validation1->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
			$validation1->setFormula1($configs4);
			$validation1->setAllowBlank(false);
			$validation1->setShowDropDown(true);
			$validation1->setShowInputMessage(true);
			$validation1->setPromptTitle('Designation');
			$validation1->setPrompt('Choose Designation');
			$validation1->setShowErrorMessage(true);
			$validation1->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP);
			$validation1->setErrorTitle('Invalid option');
			$validation1->setError('Select one from the drop down list.');

			$validation4 = $sheet->getCell('R' . $x)->getDataValidation();
			$validation4->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
			$validation4->setFormula1($configs5);
			$validation4->setAllowBlank(false);
			$validation4->setShowDropDown(true);
			$validation4->setShowInputMessage(true);
			$validation4->setPromptTitle('Work Expertises');
			$validation4->setPrompt('Choose Work Expertise');
			$validation4->setShowErrorMessage(true);
			$validation4->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP);
			$validation4->setErrorTitle('Invalid option');
			$validation4->setError('Select one from the drop down list.');
		}
		$protection = $sheet->getProtection(false);


		$fileName = 'engineer-' . date('YmdHis') . '.xlsx';
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

			$modelEmployee = new EmployeeModel(); // Load model

			if ($upload['status'] == 'success') {
				$user_type = getUserTypeByCode($this->empType);

				$file = fopen(WRITEPATH . "uploads/" . $upload['image'], "r");
				$i = 0;

				$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
				$reader->setLoadSheetsOnly(["Worksheet"]);

				$reader->setReadDataOnly(true);
				$path = (WRITEPATH . "uploads/" . $upload['image']);
				$excel = $reader->load($path);
				$sheet = $excel->setActiveSheetIndex(0);
				$allDataInSheet = $excel->getActiveSheet()->toArray(null, true, true, true);
				$arrayCount = count($allDataInSheet);  // Here get total count of row in that Excel sheet
				$EmployeeArr = array();

				for ($i = 2; $i <= $arrayCount; $i++) {
					$country =  explode('-', $allDataInSheet[$i]["D"]);
					$state 	 =  explode('-', $allDataInSheet[$i]["E"]);
					$region  =  explode('-', $allDataInSheet[$i]["M"]);
					$branch  =  explode('-', $allDataInSheet[$i]["N"]);
					$city  	 =  explode('-', $allDataInSheet[$i]["O"]);
					$dept 	 =  explode('-', $allDataInSheet[$i]["P"]);
					$degn    =  explode('-', $allDataInSheet[$i]["Q"]);
					$work    =  explode('-', $allDataInSheet[$i]["R"]);

					$EmployeeArr[$i]["first_name"] =  $allDataInSheet[$i]["A"] ?? 0;
					$EmployeeArr[$i]["last_name"] = $allDataInSheet[$i]["B"] ?? 0;
					$EmployeeArr[$i]["email"] =  $allDataInSheet[$i]["C"] ?? 0;
					$EmployeeArr[$i]["mobile"] = $allDataInSheet[$i]["G"] ?? 0;
					$EmployeeArr[$i]["address"] =  $allDataInSheet[$i]["H"] ?? 0;
					$EmployeeArr[$i]["city"] = $allDataInSheet[$i]["F"] ?? 0;
					$EmployeeArr[$i]["region_id"] = $region ?? 0;
					$EmployeeArr[$i]["branch_id"] = $branch ?? 0;
					$EmployeeArr[$i]["area_id"] = $city ?? 0;
					$EmployeeArr[$i]["department_id"] =  $dept ?? 0;
					$EmployeeArr[$i]["designation_id"] = $degn ?? 0;
					$EmployeeArr[$i]["work_expertise"] = $work ?? 0;
					$EmployeeArr[$i]["pincode"] =  $allDataInSheet[$i]["I"] ?? 0;
					$EmployeeArr[$i]["joining_date"] = $allDataInSheet[$i]["S"] ?? 0;
					$EmployeeArr[$i]["username"] = $allDataInSheet[$i]["J"] ?? 0;
					$EmployeeArr[$i]["password"] = $allDataInSheet[$i]["K"] ?? 0;
					$EmployeeArr[$i]["country_id"] = $country[1] ?? 0;
					$EmployeeArr[$i]["state_id"] = $state[1] ?? 0;
					$EmployeeArr[$i]['work_status'] = 0;
					$EmployeeArr[$i]['is_exist'] = 3;
					$EmployeeArr[$i]['removed'] = 0;
					$EmployeeArr[$i]["user_type"] = $user_type['type_id'];
					$EmployeeArr[$i]['created_datetime'] = date('Y-m-d H:i:s');
				}
				fclose($file);
				foreach ($EmployeeArr as $userdata) {
					$dfilter_data = array(
						'removed' => 0,
						'status' => 1,
						'is_exist' => 0,
						'email' => $userdata['email']
					);

					$rdetails = $modelEmployee->getEmployeeExist($dfilter_data);

					if (!empty($rdetails)) {
						$modelEmployee->updateEmployee($rdetails->employee_id, $dfilter_data);
					}

					$add = $modelEmployee->addEmployee($userdata);

					$nfilter_data = array(
						'removed' => 0,
						'status' => 1,
						'is_exist' => 2,
						'email' => $userdata['email']
					);

					$fdetails = $modelEmployee->getEmployeeExists($nfilter_data);

					if ($fdetails) {
						$modelEmployee->updateEmployeeDetails($fdetails->email);
					}

					$rfilter_data = array(
						'removed' => 0,
						'is_exist' => 0,
						'user_type' => $user_type['type_id']
					);

					$final = $modelEmployee->getEmployees23($rfilter_data);
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


	public function saveEmployee()
	{
		$response = [];
		$modelEmployee = new EmployeeModel(); // Load model
		$add = $modelEmployee->updateUploadEmployee();

		if ($add) {
			$response['status'] = 'success';
			$response['message'] = lang('Employee.success_add');
			return $this->setResponseFormat("json")->respond($response, 200);
		} else {
			$response['status'] = 'error';
			$response['message'] = lang('Employee.error_add');
			return $this->setResponseFormat("json")->respond($response, 201);
		}
	}

	public function cancel()
	{
		$response = [];
		$modelEmployee = new EmployeeModel(); // Load model 
		$add = $modelEmployee->cancelUpload();

		if ($add) {
			$response['status'] = 'success';
			$response['message'] = lang('Employee.success_upload_cancel');
			return $this->setResponseFormat("json")->respond($response, 200);
		} else {
			$response['status'] = 'error';
			$response['message'] = lang('Employee.error_add');
			return $this->setResponseFormat("json")->respond($response, 201);
		}
	}

	public function getEmployeeDetails()
	{
		$response = array();
		$this->validatePermission('view_engineer');	// Check permission

		$modelEmployee = new EmployeeModel(); // Load model

		$employee_id = $this->request->getVar('engineer_id');
		$engineer = $modelEmployee->getEmployeeDetails($employee_id);
		if ($engineer) {
			$filter_data = array(
				'removed' => 0,
				'status' => 1,
				''
			);
			$asd = $modelEmployee->getEmployeeByTypes(221, ['removed' => 0, 'status' => 1, 'region_id' => $engineer->region_id, 'branch_id' => $engineer->branch_id]);
			$rsd = $modelEmployee->getEmployeeByTypes(220, ['removed' => 0, 'status' => 1, 'region_id' => $engineer->region_id]);
			$regional_head = $modelEmployee->getEmployeeByTypes(211, ['removed' => 0, 'status' => 1, 'region_id' => $engineer->region_id]);
			$aisd_head = $modelEmployee->getEmployeeByTypes(210, ['removed' => 0, 'status' => 1]);
			$national_head = $modelEmployee->getEmployeeByTypes(201, ['removed' => 0, 'status' => 1]);
			if ($asd && $rsd && $regional_head && $aisd_head && $national_head) {
				$response['status'] = 'success';
				$response['message'] = lang('Employee.Engineer.success_detail');
				$response['employee'] = [
					'type' => $this->empType,
					'data' => [
						'area_name' => $engineer->area_name,
						'mobile' => $engineer->mobile,
						'region_name' => $engineer->region_name,
						'asd' => $asd->name,
						'rsd' => $rsd->name,
						'regional_head' => $regional_head->name,
						'aisd_head' => $aisd_head->name,
						'national_head' => $national_head->name
					],
					// 'full_data' => $engineer
				];
				return $this->setResponseFormat("json")->respond($response, 200);
			}
		} else {
			$response['status'] = 'error';
			$response['message'] = lang('Employee.Engineer.error_detail');
			return $this->setResponseFormat("json")->respond($response, 201);
		}
	}
}
