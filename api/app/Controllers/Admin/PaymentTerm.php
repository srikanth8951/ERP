<?php

namespace App\Controllers\Admin;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use CodeIgniter\RESTful\ResourceController;
use App\Libraries\Auth\User as AuthUser;
use App\Models\Admin\PaymentTermModel;

class PaymentTerm extends ResourceController
{

	public function index()
	{

		$this->validatePermission('view_area');	// Check permission
		$modelPaymentTerm = new PaymentTermModel(); // Load model

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

		$total_paymentTerms = $modelPaymentTerm->getTotalPaymentTerms($filter_data);
		$paymentTerms = $modelPaymentTerm->getPaymentTerms($filter_data);

		//upload data exixt
		$uploadPaymentTerm = $modelPaymentTerm->cancelUpload();
		//end

		if ($paymentTerms) {
			$response = array(
				'status' => 'success',
				'message' => lang('PaymentTerm.success_list'),
				'payment_terms' => $paymentTerms,
				'pagination' => array(
					'total' => (int)$total_paymentTerms,
					'length' => $limit,
					'start' => $start,
					'records' => count($paymentTerms)
				)
			);

			return $this->setResponseFormat("json")->respond($response, 200);
		} else {
			$response = array(
				'status' => 'success',
				'message' => lang('PaymentTerm.error_list')
			);
			return $this->setResponseFormat("json")->respond($response, 201);
		}
	}

	public function getPaymentTerm()
	{
		$response = array();
		$this->validatePermission('view_paymentTerm');	// Check permission

		$modelPaymentTerm = new PaymentTermModel(); // Load model

		$payment_term_id = $this->request->getVar('payment_term_id');
		$paymentTerm = $modelPaymentTerm->getPaymentTerm($payment_term_id);
		if ($paymentTerm) {
			$response['status'] = 'success';
			$response['message'] = lang('PaymentTerm.success_detail');
			$response['payment_term'] = $paymentTerm;
			return $this->setResponseFormat("json")->respond($response, 200);
		} else {
			$response['status'] = 'error';
			$response['message'] = lang('PaymentTerm.error_no_detail');
			return $this->setResponseFormat("json")->respond($response, 201);
		}
	}

	public function addPaymentTerm()
	{
		$response = array();
		$this->validatePermission('add_paymentTerm');	// Check permission

		$rules = [
			"payment_term_title" => "required"
		];

		$messages = [
			"payment_term_title" => [
				"required" => "paymentTerm name is required"
			]
		];
		if ($this->validate($rules, $messages)) {
			$modelPaymentTerm = new PaymentTermModel(); // Load model
			$paymentTerm_data = array(
				'title' => $this->request->getPost('payment_term_title'),
				'description' => $this->request->getPost('payment_term_description'),
				'is_exist' => 0,
				'status' => 1
			);
			$filter_data = array('removed', 0);
			$paymentTerm = $modelPaymentTerm->getPaymentTermByTitle($paymentTerm_data['title'], $filter_data);
			if ($paymentTerm) {
				$response['status'] = 'error';
				$response['message'] = lang('PaymentTerm.error_exist');
				return $this->setResponseFormat("json")->respond($response, 201);
			} else {
				$add = $modelPaymentTerm->addPaymentTerm($paymentTerm_data);
				if ($add) {
					$response['status'] = 'success';
					$response['message'] = lang('PaymentTerm.success_add');
					return $this->setResponseFormat("json")->respond($response, 200);
				} else {
					$response['status'] = 'error';
					$response['message'] = lang('PaymentTerm.error_add');
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

	public function editPaymentTerm()
	{
		$response = array();

		$this->validatePermission('edit_paymentTerm');	// Check permission

		$rules = [
			"payment_term_title" => "required",
		];

		$messages = [
			"payment_term_title" => [
				"required" => "paymentTerm name is required"
			]
		];
		if ($this->validate($rules, $messages)) {
			$modelPaymentTerm = new PaymentTermModel(); // Load model
			$paymentTerm_data = array(
				'title' => $this->request->getPost('payment_term_title'),
				'description' => $this->request->getPost('payment_term_description'),
				'status' => 1
			);
			$payment_term_id = $this->request->getVar('payment_term_id');

			$paymentTerm = $modelPaymentTerm->getPaymentTerm($payment_term_id);
			if ($paymentTerm) {
				$filter_data = array('removed', 0, 'except' => [$payment_term_id]);
				$paymentTerm_name = $modelPaymentTerm->getPaymentTermByTitle($paymentTerm_data['title'], $filter_data);
				if ($paymentTerm_name) {
					$response['status'] = 'error';
					$response['message'] = lang('PaymentTerm.error_exist');
					return $this->setResponseFormat("json")->respond($response, 201);
				} else {
					$edit = $modelPaymentTerm->editPaymentTerm($payment_term_id, $paymentTerm_data);
					if ($edit) {
						$response['status'] = 'success';
						$response['message'] = lang('PaymentTerm.success_edit');
						return $this->setResponseFormat("json")->respond($response, 200);
					} else {
						$response['status'] = 'error';
						$response['message'] = lang('PaymentTerm.error_edit');
						return $this->setResponseFormat("json")->respond($response, 201);
					}
				}
			} else {
				$response['status'] = 'error';
				$response['message'] = lang('PaymentTerm.error_no_detail');
				return $this->setResponseFormat("json")->respond($response, 201);
			}
		} else {
			$response = [
				'status' => 'error',
				'message' => $this->validator->getErrors()
			];
		}
	}

	public function deletePaymentTerm()
	{
		$response = array();

		$this->validatePermission('edit_paymentTerm');	// Check permission
		$modelPaymentTerm = new PaymentTermModel(); // Load model

		$payment_term_id = $this->request->getVar('payment_term_id');
		$paymentTerm = $modelPaymentTerm->getPaymentTerm($payment_term_id);
		if ($paymentTerm) {
			$remove = $modelPaymentTerm->removePaymentTerm($payment_term_id);
			if ($remove) {
				$response['status'] = 'success';
				$response['message'] = lang('success_removed');
				return $this->setResponseFormat("json")->respond($response, 200);
			} else {
				$response['status'] = 'error';
				$response['message'] = lang('error_removed');
				return $this->setResponseFormat("json")->respond($response, 201);
			}
		} else {
			$response['status'] = 'error';
			$response['message'] = lang('PaymentTerm.error_no_detail');
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
		$this->validatePermission('view_paymentTerm');	// Check permission
		$modelPaymentTerm = new PaymentTermModel(); // Load model

		$filter_data = array(
			'removed' => 0,
			'status' => 1
		);
		$paymentTermArray = array();
		$paymentTerms = $modelPaymentTerm->getPaymentTerms($filter_data);
		if ($paymentTerms) {
			foreach ($paymentTerms as $paymentTerm) {
				$paymentTermArray[] = array(
					'id' => (int)$paymentTerm->payment_term_id,
					'name' => html_entity_decode($paymentTerm->title)
				);
			}
		}
		$response = array(
			'status' => 'success',
			'payment_terms' => $paymentTermArray,
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
			$modelPayment = new PaymentTermModel(); // Load model
			if ($upload['status'] == 'success') {

				$file = fopen(WRITEPATH . "uploads/" . $upload['image'], "r");
				$i = 0;
				$numberOfFields = 2;
				$csvArr = array();

				while (($filedata = fgetcsv($file, 1000, ",")) !== FALSE) {
					$num = count($filedata);
					if ($i > 0 && $num == $numberOfFields) {
						if (empty($filedata[1])) {
							$desc = '';
						} else {
							$desc = $filedata[1];
						}
						$csvArr[$i]['title'] = $filedata[0] ?? '';
						$csvArr[$i]['description'] = $desc ?? '';
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
						'title' => $userdata['title']
					);

					$rdetails = $modelPayment->getPaymentTermExist($dfilter_data);

					if (!empty($rdetails)) {
						$modelPayment->updatePaymentTerm($rdetails->payment_term_id, $dfilter_data);
					}

					if ($userdata['title']) {
						$add = $modelPayment->addPaymentTerm($userdata);
					}

					$nfilter_data = array(
						'removed' => 0,
						'status' => 1,
						'is_exist' => 2,
						'title' => $userdata['title']
					);

					$fdetails = $modelPayment->getPaymentTermExists($nfilter_data);

					if ($fdetails) {
						$modelPayment->updatePaymentTermDetails($fdetails->title);
					}

					$rfilter_data = array(
						'removed' => 0
					);

					$final = $modelPayment->getPaymentTermList($rfilter_data);
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

	public function savePayment()
	{
		$response = [];
		$modelPayment = new PaymentTermModel(); // Load model
		$add = $modelPayment->updateUploadPaymentTerm();

		if ($add) {
			$response['status'] = 'success';
			$response['message'] = lang('PaymentTerm.success_add');
			return $this->setResponseFormat("json")->respond($response, 200);
		} else {
			$response['status'] = 'error';
			$response['message'] = lang('PaymentTerm.error_add');
			return $this->setResponseFormat("json")->respond($response, 201);
		}
	}

	public function cancel()
	{
		$response = [];
		$modelPayment = new PaymentTermModel(); // Load model
		$add = $modelPayment->cancelUpload();

		if ($add) {
			$response['status'] = 'success';
			$response['message'] = lang('PaymentTerm.success_upload_cancel');
			return $this->setResponseFormat("json")->respond($response, 200);
		} else {
			$response['status'] = 'error';
			$response['message'] = lang('PaymentTerm.error_add');
			return $this->setResponseFormat("json")->respond($response, 201);
		}
	}
}
