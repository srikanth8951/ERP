<?php

namespace App\Controllers\Employee\aisd;

use CodeIgniter\RESTful\ResourceController;
use App\Libraries\Auth\User as AuthUser;
use App\Libraries\Auth\Employee as AuthEmployee;
use App\Models\Employee\EmployeeModel;
use App\Models\Employee\UserModel;
use App\Libraries\AppLog;

class Profile extends ResourceController
{
	protected $empType = 'aisd_head';
	protected $employeeId;

	public function __construct()
	{
		helper('user');
		AppLog::initLog(); // Init log
	}

	public function index()
	{
		$this->getEmployee();
	}

	public function getEmployee()
	{
		$response = array();
		$this->validatePermission('view_aisdHead_Head');	// Check permission

		$modelEmployee = new EmployeeModel(); // Load model

		if (!$this->isEmployee()) {
			$response = array(
				'status' => 'error',
				'message' => lang('Common.error_employee_login')
			);

			return $this->setResponseFormat("json")->respond($response, 201);
		}

		$userID = AuthUser::getId();

		$filter_data = array(
			'removed' => 0,
			'status' => 1
		);
		$userDetails = $modelEmployee->getEmployeeByID($userID, $filter_data);

		if ($userDetails) {
			if ($userDetails->user_image && file_exists(ROOTPATH . 'public/uploads/user_profile_image' . '/' . $userDetails->user_image)) {
				$thumb = base_url() . '/uploads/user_profile_image' . '/' . $userDetails->user_image;
				$userDetails->profile_image_link = $thumb;
			} else {
				$userDetails->profile_image_link = 'http://mentric.co.in/sw/assets/images/users/avatar-1.jpg';
			}
			$response['status'] = 'success';
			$response['message'] = lang('Employee.aisdHead.success_detail');
			$response['employee'] = [
				'type' => $this->empType,
				'data' => $userDetails
			];
			return $this->setResponseFormat("json")->respond($response, 200);
		} else {
			$response['status'] = 'error';
			$response['message'] = lang('Employee.aisdHead.error_detail');
			return $this->setResponseFormat("json")->respond($response, 201);
		}
	}

	public function editEmployee()
	{
		$response = array();

		$this->validatePermission('edit_aisdHead');	// Check permission
		
		if (!$this->isEmployee()) {
			$response = array(
				'status' => 'error',
				'message' => lang('Common.error_employee_login')
			);

			return $this->setResponseFormat("json")->respond($response, 201);
		}

		$userID = AuthUser::getId();

		// Log
		AppLog::writeLog('post_param', json_encode($this->request->getPost(NULL)));

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
				"required" => "First name is required",
				"numeric" => "Mobile number must be numeric"
			]
		];
		if ($this->validate($rules, $messages)) {
			$modelEmployee = new EmployeeModel(); // Load model

			$employee_data = array_merge($this->request->getPost(null), [
				'user_type' => $user_type['type_id']
			]);

			// Profile image
			$file = $this->request->getFile('file'); // getting image
			if (!empty($file)) {
				$filename = $file->getClientName() ?? '';
				AppLog::writeLog('file_param', $filename);
			}

			$user_type = getUserTypeByCode($this->empType);

			$filter_data = array(
				'removed' => 0,
				'status' => 1
			);
			$employee = $modelEmployee->getEmployeeByID($userID, $filter_data);
			AppLog::writeLog('Employee', json_encode($employee));
			if ($employee) {
				$filter_data = array(
					'removed' => 0,
					'status' => 1,
					'email' => $employee_data['email'],
					'username' => $employee_data['username'],
					'except' => [$employee->user_id]
				);
				$thumb = $employee->user_image ?? '';
				// Upload image
				if (!empty($file)) {
					$upload = $this->saveFile($file, $thumb); // save file
					if ($upload['status'] == 'success') {
						$employee_data['image'] = $upload['image']; // add file name
					}
				}

				$aisdHead = $modelEmployee->getEmployeeByValidation($filter_data);
				if ($aisdHead) {
					$response['status'] = 'error';
					$response['message'] = 'Email id already exist';
					AppLog::writeLog('response', json_encode($response));
					return $this->setResponseFormat("json")->respond($response, 201);
				} else {
					$edit = $modelEmployee->editProfile($employee->employee_id, $employee_data);
					if ($edit) {
						$response['status'] = 'success';
						$response['message'] = 'Updated successfuly';
						AppLog::writeLog('response', json_encode($response));
						return $this->setResponseFormat("json")->respond($response, 200);
					} else {
						$response['status'] = 'error';
						$response['message'] = 'Not Updated';
						AppLog::writeLog('response', json_encode($response));
						return $this->setResponseFormat("json")->respond($response, 201);
					}
				}
			} else {
				$response['status'] = 'error';
				$response['message'] = lang('Employee.NationalHead.error_detail');
				AppLog::writeLog('response', json_encode($response));
				return $this->setResponseFormat("json")->respond($response, 201);
			}
		} else {
			$response = [
				'status' => 'error',
				'message' => $this->validator->getErrors()
			];
			AppLog::writeLog('response', json_encode($response));
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

	//Save file
	private function saveFile($uploadFile, $thumb = '', $uploadType = 'file')
	{
		$json = array();
		$json['status'] = false;
		$file_upload = false;

		if (!empty($uploadFile)) {

			// Load file storage library
			$fileStorage = new \App\Libraries\Storage\DefaultStorage();

			$randomFilename = $uploadFile->getRandomName();
			$newFilename = $randomFilename ? pathinfo($randomFilename, PATHINFO_FILENAME) : '';
			$file_data = array(
				'file' => $uploadFile,
				'newName' => $newFilename,
				'uploadPath' => ROOTPATH . 'public/uploads/user_profile_image'
			);
			$file_upload = $fileStorage->uploadFile($file_data);

			$file_upload_status = isset($file_upload['status']) ? $file_upload['status'] : false;
			if ($file_upload_status) {
				if ($thumb) {
					if (file_exists($file_data['uploadPath'] . '/' . $thumb)) {
						unlink($file_data['uploadPath'] . '/' . $thumb);
					}
				}
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

		AppLog::writeLog('file_upload', json_encode($json)); // Log
		return $json;
	}

	protected function isEmployee()
	{
		$this->userId = AuthUser::getId();
		if (AuthEmployee::isValid($this->empType)) {
			$this->employeeId = AuthEmployee::getId();
		}
		return $this->employeeId;
	}
}
