<?php

namespace App\Controllers\Admin;

use CodeIgniter\RESTful\ResourceController;
use App\Libraries\Auth\User as AuthUser;
use App\Libraries\Auth\Admin as AuthAdmin;
use App\Models\Admin\UserModel;
use App\Libraries\AppLog;

class Profile extends ResourceController
{
	protected $userType = 'admin';
	protected $adminId;

	public function __construct()
	{
		helper('user');
		AppLog::initLog(); // Init log
	}

	public function index()
	{
		$this->getUser();
	}

	public function getUser()
	{
		$response = array();
		$this->validatePermission('view_engineer_Head');	// Check permission

		$modelUser = new UserModel(); // Load user modal

		if (!$this->isAdmin()) {
			$response = array(
				'status' => 'error',
				'message' => lang('Common.error_admin_login')
			);

			return $this->setResponseFormat("json")->respond($response, 201);
		}
		$userId = AuthUser::getId();

		$user_type = getUserTypeByCode($this->userType);

		$filter_data = array(
			'removed' => 0,
			'status' => 1
		);
		$userDetail = $modelUser->getUser($userId, $filter_data);

		if ($userDetail) {

			if ($userDetail->image) {
				if (file_exists(ROOTPATH . 'public/uploads/user_profile_image/' . $userDetail->image)) {
					$userDetail->image = base_url() . '/public/uploads/user_profile_image/' . $userDetail->image;
				} else {
					$userDetail->image = "http://mentric.co.in/sw/assets/images/users/avatar-1.jpg";
				}
			} else {
				$userDetail->image = "http://mentric.co.in/sw/assets/images/users/avatar-1.jpg";
			}

			$response['status'] = 'success';
			$response['message'] = lang('User.user.success_detail');
			$response['user'] = [
				'type' => $this->userType,
				'data' => $userDetail
			];
			return $this->setResponseFormat("json")->respond($response, 200);
		} else {
			$response['status'] = 'error';
			$response['message'] = lang('User.user.error_detail');
			return $this->setResponseFormat("json")->respond($response, 200);
		}
	}

	public function editUser()
	{
		$response = array();

		$this->validatePermission('edit_engineer');	// Check permission
		if (!$this->isAdmin()) {
			$response = array(
				'status' => 'error',
				'message' => lang('Common.error_admin_login')
			);

			return $this->setResponseFormat("json")->respond($response, 201);
		}
		$modelUser = new UserModel(); // Load user modal
		$userId = AuthUser::getId();

		$user_type = getUserTypeByCode($this->userType);

		$rules = [
			"first_name" => "required",
			"last_name" => 'required',
			"email" => "required|valid_email",
			"mobile" => "required|numeric"
		];

		$messages = [
			"first_name" => [
				"required" => "First name is required"
			],
			"last_name" => [
				"required" => "Last name is required"
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
			$modelUser = new UserModel(); // Load model

			// Log
			AppLog::writeLog('post_param', json_encode($this->request->getPost(NULL)));

			$user_type = getUserTypeByCode($this->userType);

			$user_data = array_merge($this->request->getPost(null), [
				'user_type' => $user_type['type_id']
			]);

			// Profile image
			$file = $this->request->getFile('file'); // getting image
			if (!empty($file)) {
				$filename = $file->getClientName() ?? '';
				AppLog::writeLog('file_param', $filename);
			}

			$filter_data = array(
				'removed' => 0,
				'status' => 1
			);
			$user = $modelUser->getUser($userId);

			if ($user) {
				$filter_data = array(
					'removed' => 0,
					'status' => 1,
					'email' => $user_data['email'],
					'username' => $user_data['username'],
					'except' => [$userId]
				);
				$thumb = $user->user_image ?? ''; // existing user image
				// Upload image
				if (!empty($file)) {
					$upload = $this->saveFile($file, $thumb); // save file
					if ($upload['status'] == 'success') {
						$user_data['image'] = $upload['image']; // add file name
					}
				}

				$admin = $modelUser->getUserByValidation($filter_data);
				if ($admin) {
					$response['status'] = 'error';
					$response['message'] = 'Email or username already exist';
					return $this->setResponseFormat("json")->respond($response, 201);
				} else {
					$edit = $modelUser->editProfile($userId, $user_data);
					if ($edit) {
						$response['status'] = 'success';
						$response['message'] = 'Updated successfuly';
						return $this->setResponseFormat("json")->respond($response, 200);
					} else {
						$response['status'] = 'error';
						$response['message'] = 'Not Updated';
						return $this->setResponseFormat("json")->respond($response, 201);
					}
				}
			} else {
				$response['status'] = 'error';
				$response['message'] = lang('Employee.NationalHead.error_detail');
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

	protected function isAdmin()
	{
		$this->userId = AuthUser::getId();
		if (AuthAdmin::isValid($this->userType)) {
			$this->adminId = AuthAdmin::getId();
		}

		return $this->adminId;
	}
}
