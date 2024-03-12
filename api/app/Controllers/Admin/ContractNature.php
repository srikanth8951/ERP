<?php

namespace App\Controllers\Admin;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use CodeIgniter\RESTful\ResourceController;
use App\Libraries\Auth\User as AuthUser;
use App\Models\Admin\ContractNatureModel;

class ContractNature extends ResourceController
{

	public function index()
	{

		$this->validatePermission('view_area');	// Check permission
		$modelContractNature = new ContractNatureModel(); // Load model

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

		$total_contractNatures = $modelContractNature->getTotalContractNatures($filter_data);
		$contractNatures = $modelContractNature->getContractNatures($filter_data);

		//upload data exixt
		$uploadContractNature = $modelContractNature->cancelUpload();
		//end

		if ($contractNatures) {
			$response = array(
				'status' => 'success',
				'message' => lang('ContractNature.success_list'),
				'contract_natures' => $contractNatures,
				'pagination' => array(
					'total' => (int)$total_contractNatures,
					'length' => $limit,
					'start' => $start,
					'records' => count($contractNatures)
				)
			);

			return $this->setResponseFormat("json")->respond($response, 200);
		} else {
			$response = array(
				'status' => 'error',
				'message' => lang('ContractNature.error_list')
			);

			return $this->setResponseFormat("json")->respond($response, 201);
		}
	}

	public function getContractNature()
	{
		$response = array();
		$this->validatePermission('view_contract_nature');	// Check permission

		$modelContractNature = new ContractNatureModel(); // Load model

		$contract_nature_id = $this->request->getVar('contract_nature_id');
		$contractNature = $modelContractNature->getContractNature($contract_nature_id);
		if ($contractNature) {
			$response['status'] = 'success';
			$response['message'] = lang('ContractNature.success_detail');
			$response['contract_nature'] = $contractNature;
			return $this->setResponseFormat("json")->respond($response, 200);
		} else {
			$response['status'] = 'error';
			$response['message'] = lang('ContractNature.error_detail');
			return $this->setResponseFormat("json")->respond($response, 201);
		}
	}

	public function addContractNature()
	{
		$response = array();
		$this->validatePermission('add_contract_nature');	// Check permission

		$rules = [
			"contract_nature_name" => "required",
			"contract_nature_code" => "required"
		];

		$messages = [
			"contract_nature_name" => [
				"required" => "contractNature name is required"
			],
			"contract_nature_code" => [
				"required" => "contractNature code is required"
			]
		];
		if ($this->validate($rules, $messages)) {
			$modelContractNature = new ContractNatureModel(); // Load model
			$contract_nature_name = $this->request->getPost('contract_nature_name');
			$contract_nature_code = $this->request->getPost('contract_nature_code');
			$contractNature_data = array(
				'name' => $contract_nature_name,
				'code' => $contract_nature_code,
				'is_exist' => 0,
				'status' => 1
			);
			$filter_data = array('removed' => 0, $contract_nature_code);
			$contractNature_name = $modelContractNature->getContractNatureByName($contract_nature_name, $filter_data);
			if ($contractNature_name) {
				$response['status'] = 'error';
				$response['message'] = lang('Name already exist');
				return $this->setResponseFormat("json")->respond($response, 201);
			} else {
				$add = $modelContractNature->addContractNature($contractNature_data);
				if ($add) {
					$response['status'] = 'success';
					$response['message'] = lang('ContractNature.success_add');
					return $this->setResponseFormat("json")->respond($response, 200);
				} else {
					$response['status'] = 'error';
					$response['message'] = lang('ContractNature.error_add');
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

	public function editContractNature()
	{
		$response = array();

		$this->validatePermission('edit_contract_nature');	// Check permission

		$rules = [
			"contract_nature_name" => "required",
			"contract_nature_code" => "required"
		];

		$messages = [
			"contract_nature_name" => [
				"required" => "contractNature name is required"
			],
			"contract_nature_code" => [
				"required" => "contractNature code is required"
			]
		];
		if ($this->validate($rules, $messages)) {
			$modelContractNature = new ContractNatureModel(); // Load model

			$contract_nature_id = $this->request->getVar('contract_nature_id');

			$contractNature = $modelContractNature->getContractNature($contract_nature_id);
			if ($contractNature) {
				$contractNature_data = array(
					'name' => $this->request->getPost('contract_nature_name'),
					'code' => $this->request->getPost('contract_nature_code'),
					'status' => $contractNature->status
				);
				$filter_data = array(
					'removed' => 0,
					$contractNature_data['code'],
					'except' => [$contract_nature_id]
				);
				$contractNature_name = $modelContractNature->getContractNatureByName($contractNature_data['name'], $filter_data);
				if ($contractNature_name) {
					$response['status'] = 'error';
					$response['message'] = lang('Name already exist');
					return $this->setResponseFormat("json")->respond($response, 201);
				} else {
					$edit = $modelContractNature->editContractNature($contract_nature_id, $contractNature_data);
					if ($edit) {
						$response['status'] = 'success';
						$response['message'] = lang('ContractNature.success_edit');
						return $this->setResponseFormat("json")->respond($response, 200);
					} else {
						$response['status'] = 'error';
						$response['message'] = lang('ContractNature.error_edit');
						return $this->setResponseFormat("json")->respond($response, 201);
					}
				}
			} else {
				$response['status'] = 'error';
				$response['message'] = lang('ContractNature.error_detail');
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

	public function deleteContractNature()
	{
		$response = array();

		$this->validatePermission('edit_contract_nature');	// Check permission
		$modelContractNature = new ContractNatureModel(); // Load model

		$contract_nature_id = $this->request->getVar('contract_nature_id');
		$contractNature = $modelContractNature->getContractNature($contract_nature_id);
		if ($contractNature) {
			$remove = $modelContractNature->removeContractNature($contract_nature_id);
			if ($remove) {
				$response['status'] = 'success';
				$response['message'] = lang('ContractNature.success_removed');
				return $this->setResponseFormat("json")->respond($response, 200);
			} else {
				$response['status'] = 'error';
				$response['message'] = lang('ContractNature.error_removed');
				return $this->setResponseFormat("json")->respond($response, 201);
			}
		} else {
			$response['status'] = 'error';
			$response['message'] = lang('ContractNature.error_detail');
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
		$this->validatePermission('view_contract_nature');	// Check permission
		$modelContractNature = new ContractNatureModel(); // Load model

		$filter_data = array(
			'removed' => 0,
			'status' => 1
		);
		$contractNatureArray = array();
		$contractNatures = $modelContractNature->getContractNatures($filter_data);
		if ($contractNatures) {
			foreach ($contractNatures as $contractNature) {
				$contractNatureArray[] = array(
					'id' => (int)$contractNature->contract_nature_id,
					'name' => html_entity_decode($contractNature->name)
				);
			}
		}
		$response = array(
			'status' => 'success',
			'contract_natures' => $contractNatureArray,
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

			$modelContract = new ContractNatureModel(); // Load model
			if ($upload['status'] == 'success') {

				$file = fopen(WRITEPATH . "uploads/" . $upload['image'], "r");
				$i = 0;
				$numberOfFields = 2;
				$csvArr = array();

				while (($filedata = fgetcsv($file, 1000, ",")) !== FALSE) {
					$num = count($filedata);
					if ($i > 0 && $num == $numberOfFields) {

						$csvArr[$i]['name'] = $filedata[0] ?? '';
						$csvArr[$i]['code'] = $filedata[1] ?? '';
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

					$rdetails = $modelContract->getContractNatureByCodeExist($dfilter_data);

					if (!empty($rdetails)) {
						$modelContract->updateContractNature($rdetails->contract_nature_id, $dfilter_data);
					}

					if ($userdata['code'] && $userdata['name']) {
						$add = $modelContract->addContractNature($userdata);
					}

					$nfilter_data = array(
						'removed' => 0,
						'status' => 1,
						'is_exist' => 2,
						'code' => $userdata['code']
					);

					$fdetails = $modelContract->getContractNatureByCodeExist1($nfilter_data);

					if ($fdetails) {
						$modelContract->updateContractNatureDetails($fdetails->code);
					}

					$rfilter_data = array(
						'removed' => 0,
						//'status' => 0,
						'is_exist' => 0,
						'type' => 'upload'
					);

					$final = $modelContract->getContactNatureList($rfilter_data);
				}

				$path_to_file = WRITEPATH . "uploads/" . $upload['image'];
				unlink($path_to_file);

				$response = array(
					'status' => 'success',
					'upload_nature' => $final
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


	public function saveContractNature()
	{
		$response = [];
		$modelContract = new ContractNatureModel(); // Load model
		$add = $modelContract->updateUploadContractNature();

		if ($add) {
			$response['status'] = 'success';
			$response['message'] = lang('ContractNature.success_add');
			return $this->setResponseFormat("json")->respond($response, 200);
		} else {
			$response['status'] = 'error';
			$response['message'] = lang('ContractNature.error_add');
			return $this->setResponseFormat("json")->respond($response, 201);
		}
	}

	public function cancel()
	{
		$response = [];
		$modelContract = new ContractNatureModel(); // Load model
		$add = $modelContract->cancelUpload();

		if ($add) {
			$response['status'] = 'success';
			$response['message'] = lang('ContractNature.success_upload_cancel');
			return $this->setResponseFormat("json")->respond($response, 200);
		} else {
			$response['status'] = 'error';
			$response['message'] = lang('ContractNature.error_add');
			return $this->setResponseFormat("json")->respond($response, 201);
		}
	}
}
