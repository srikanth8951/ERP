<?php

namespace App\Controllers\Customer;

use CodeIgniter\RESTful\ResourceController;
use App\Libraries\Auth\User as AuthUser;
use App\Libraries\Auth\Customer as AuthCustomer;
use App\Models\Customer\CustomerModel;
use App\Models\Customer\UserModel;
use App\Libraries\AppLog;

class Profile extends ResourceController
{
	protected $customerType = 'customer';
	protected $customerId;

	public function __construct()
	{
		helper('user');
		AppLog::initLog(); // Init log
	}

	public function index()
	{
		$this->getCustomer();
	}

	public function getCustomer()
	{
		$response = array();
		$this->validatePermission('customer');	// Check permission

		$modelCustomer = new CustomerModel(); // Load model

		if (!$this->isCustomer()) {
			$response = array(
				'status' => 'error',
				'message' => lang('Common.error_customer_login')
			);

			return $this->setResponseFormat("json")->respond($response, 201);
		}

		$userID = AuthUser::getId();

		$filter_data = array(
			'removed' => 0,
			'status' => 1
		);
		$userDetails = $modelCustomer->getCustomerByID($userID, $filter_data);

		if ($userDetails) {
			if ($userDetails->user_image && file_exists(ROOTPATH . 'public/uploads/user_profile_image' . '/' . $userDetails->user_image)) {
				$thumb = base_url() . '/uploads/user_profile_image' . '/' . $userDetails->user_image;
				$userDetails->profile_image_link = $thumb;
			} else {
				$userDetails->profile_image_link = 'http://mentric.co.in/sw/assets/images/users/avatar-1.jpg';
			}
			$response['status'] = 'success';
			$response['message'] = lang('Customer.cam.success_detail');
			$response['customer'] = [
				'type' => $this->customerType,
				'data' => $userDetails
			];
			return $this->setResponseFormat("json")->respond($response, 200);
		} else {
			$response['status'] = 'error';
			$response['message'] = lang('Customer.cam.error_detail');
			return $this->setResponseFormat("json")->respond($response, 201);
		}
	}

	public function editCustomer()
	{
		$response = array();

		$this->validatePermission('edit_cam');	// Check permission
		
		if (!$this->isCustomer()) {
			$response = array(
				'status' => 'error',
				'message' => lang('Common.error_customer_login')
			);

			return $this->setResponseFormat("json")->respond($response, 201);
		}

		$userID = AuthUser::getId();

		// Log
		AppLog::writeLog('post_param', json_encode($this->request->getPost(NULL)));

		$user_type = getUserTypeByCode($this->customerType);

		$rules = [
			"company_name" => "required",
			"billing_address_email" => "required|valid_email",
			"username" => "required",
			"billing_address_mobile" => "required|numeric"
		];

		$messages = [
			"company_name" => [
				"required" => "First name is required"
			],
			"billing_address_email" => [
				"required" => "Email is required",
				"valid_emil" => "Invalid email"
			],
			"username" => [
				"required" => "Username is required"
			],
			"billing_address_mobile" => [
				"required" => "First name is required",
				"numeric" => "Mobile number must be numeric"
			]
		];
		if ($this->validate($rules, $messages)) {
			$modelCustomer = new CustomerModel(); // Load model

			$customer_data = array_merge($this->request->getPost(null), [
				'user_type' => $user_type['type_id']
			]);

			// Profile image
			$file = $this->request->getFile('file'); // getting image
			if (!empty($file)) {
				$filename = $file->getClientName() ?? '';
				AppLog::writeLog('file_param', $filename);
			}

			$user_type = getUserTypeByCode($this->customerType);

			$filter_data = array(
				'removed' => 0,
				'status' => 1
			);
			$customer = $modelCustomer->getCustomerByID($userID, $filter_data);
			AppLog::writeLog('Customer', json_encode($customer));
			if ($customer) {
				$filter_data = array(
					'removed' => 0,
					'status' => 1,
					'username' => $customer_data['username'],
					'except' => [$customer->user_id]
				);
				$thumb = $customer->user_image ?? '';
				// Upload image
				if (!empty($file)) {
					$upload = $this->saveFile($file, $thumb); // save file
					if ($upload['status'] == 'success') {
						$customer_data['image'] = $upload['image']; // add file name
					}
				}

				$cam = $modelCustomer->getCustomerValidation($filter_data);
				if ($cam) {
					$response['status'] = 'error';
					$response['message'] = 'Email id already exist';
					AppLog::writeLog('response', json_encode($response));
					return $this->setResponseFormat("json")->respond($response, 201);
				} else {
					$edit = $modelCustomer->editProfile($customer->customer_id, $customer_data);
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
				$response['message'] = lang('Customer.NationalHead.error_detail');
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

	protected function isCustomer()
	{
		$this->userId = AuthUser::getId();
		if (AuthCustomer::isValid()) {
			$this->customerId = AuthCustomer::getId();
		}
		return $this->customerId;
	}
}
