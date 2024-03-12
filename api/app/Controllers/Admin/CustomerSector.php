<?php

namespace App\Controllers\Admin;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use CodeIgniter\RESTful\ResourceController;
use App\Libraries\Auth\User as AuthUser;
use App\Models\Admin\CustomerSectorModel;


class CustomerSector extends ResourceController
{


	public function index()
	{

		$this->validatePermission('view_customer_sector');	// Check permission
		$modelCustomerSector = new CustomerSectorModel(); // Load model

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
			// 'is_exist' => 0,
			'order' => $order
		);

		$total_customer_sectores = $modelCustomerSector->getTotalCustomerSectores($filter_data);
		$customer_sectores = $modelCustomerSector->getCustomerSectores($filter_data);

		if ($customer_sectores) {
			$response = array(
				'status' => 'success',
				'message' => lang('CustomerSector.success_list'),
				'customer_sectores' => $customer_sectores,
				'pagination' => array(
					'total' => (int)$total_customer_sectores,
					'length' => $limit,
					'start' => $start,
					'records' => count($customer_sectores)
				)
			);
			return $this->setResponseFormat("json")->respond($response, 200);
		} else {
			$response = array(
				'status' => 'error',
				'message' => lang('CustomerSector.error_list')
			);
			return $this->setResponseFormat("json")->respond($response, 201);
		}
	}

	public function getCustomerSector()
	{
		$response = array();
		$this->validatePermission('view_customer_sector');	// Check permission

		$modelCustomerSector = new CustomerSectorModel(); // Load model

		$customer_sector_id = $this->request->getVar('customer_sector_id');
		$customer_sector = $modelCustomerSector->getCustomerSector($customer_sector_id);
		if ($customer_sector) {
			$response['status'] = 'success';
			$response['message'] = lang('CustomerSector.success_detail');
			$response['customer_sector'] = $customer_sector;
			return $this->setResponseFormat("json")->respond($response, 200);
		} else {
			$response['status'] = 'error';
			$response['message'] = lang('CustomerSector.error_detail');
			return $this->setResponseFormat("json")->respond($response, 201);
		}
	}

	public function addCustomerSector()
	{
		$response = array();
		$this->validatePermission('add_customer_sector');	// Check permission

		$rules = [
			"title" => "required",
		];

		$messages = [
			"title" => [
				"required" => "customer_sector name is required"
			],
		];
		if ($this->validate($rules, $messages)) {
			$modelCustomerSector = new CustomerSectorModel(); // Load model
			$customer_sector_title = $this->request->getPost('title');
			$type_id = $this->request->getPost('type_id');
			$customer_sector_data = array(
				'type_id' => $type_id,
				'title' => $customer_sector_title,
				'status' => 1
			);
			$filter_data = array('type_id' => $type_id, 'removed' => 0);
			$customer_sector = $modelCustomerSector->getCustomerSectorByName($customer_sector_title, $filter_data);
			if ($customer_sector) {
				$response['status'] = 'error';
				$response['message'] = lang('CustomerSector.name_exist');
				return $this->setResponseFormat("json")->respond($response, 201);
			} else {
				$add = $modelCustomerSector->addCustomerSector($customer_sector_data);
				if ($add) {
					$response['status'] = 'success';
					$response['message'] = lang('CustomerSector.success_add');
					return $this->setResponseFormat("json")->respond($response, 200);
				} else {
					$response['status'] = 'error';
					$response['message'] = lang('CustomerSector.error_add');
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

	public function editCustomerSector()
	{
		$response = array();

		$this->validatePermission('edit_customer_sector');	// Check permission

		$rules = [
			"title" => "required",
		];

		$messages = [
			"title" => [
				"required" => "customer_sector name is required"
			],
		];
		if ($this->validate($rules, $messages)) {
			$modelCustomerSector = new CustomerSectorModel(); // Load model

			$customer_sector_id = $this->request->getVar('customer_sector_id');

			$customer_sector = $modelCustomerSector->getCustomerSector($customer_sector_id);
			if ($customer_sector) {
				$customer_sector_data = array(
					'title' => $this->request->getPost('title'),
					'type_id' => $this->request->getPost('type_id'),
					'status' => 1
				);
				$filter_data = array(
					'removed' => 0,
					'type_id' => $customer_sector_data['type_id'],
					'except' => [$customer_sector_id]
				);
				$customer_sector_name = $modelCustomerSector->getCustomerSectorByName($customer_sector_data['title'], $filter_data);
				if ($customer_sector_name) {
					$response['status'] = 'error';
					$response['message'] = lang('CustomerSector.name_exist');
					return $this->setResponseFormat("json")->respond($response, 201);
				} else {
					$edit = $modelCustomerSector->editCustomerSector($customer_sector_id, $customer_sector_data);
					if ($edit) {
						$response['status'] = 'success';
						$response['message'] = lang('CustomerSector.success_edit');
						return $this->setResponseFormat("json")->respond($response, 200);
					} else {
						$response['status'] = 'error';
						$response['message'] = lang('CustomerSector.error_edit');
						return $this->setResponseFormat("json")->respond($response, 201);
					}
				}
			} else {
				$response['status'] = 'error';
				$response['message'] = lang('CustomerSector.error_detail');
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

	public function deleteCustomerSector()
	{
		$response = array();

		$this->validatePermission('edit_customer_sector');	// Check permission
		$modelCustomerSector = new CustomerSectorModel(); // Load model

		$customer_sector_id = $this->request->getVar('customer_sector_id');
		$customer_sector = $modelCustomerSector->getCustomerSector($customer_sector_id);
		if ($customer_sector) {
			$remove = $modelCustomerSector->removeCustomerSector($customer_sector_id);
			if ($remove) {
				$response['status'] = 'success';
				$response['message'] = lang('CustomerSector.success_removed');
				return $this->setResponseFormat("json")->respond($response, 200);
			} else {
				$response['status'] = 'error';
				$response['message'] = lang('CustomerSector.error_removed');
				return $this->setResponseFormat("json")->respond($response, 201);
			}
		} else {
			$response['status'] = 'error';
			$response['message'] = lang('CustomerSector.error_detail');
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
		$this->validatePermission('view_customer_sector');	// Check permission
		$modelCustomerSector = new CustomerSectorModel(); // Load model

		$type_id = $this->request->getVar('type_id');
		$filter_data = array(
			'removed' => 0,
			'status' => 1
		);
		$customer_sectorArray = array();
		$customer_sectores = $modelCustomerSector->getCustomerSectores($filter_data);
		//print_r($customer_sectores);
		if ($customer_sectores) {
			foreach ($customer_sectores as $customer_sector) {
				$customer_sectorArray[] = array(
					'id' => (int)$customer_sector->customer_sector_id,
					'name' => html_entity_decode($customer_sector->title. '-' .$customer_sector->type_name)
				);
			}
		}
		$response = array(
			'status' => 'success',
			'customer_sectores' => $customer_sectorArray,
		);

		return $this->setResponseFormat("json")->respond($response, 200);
	}

	public function typeAutocomplete()
	{
		$this->validatePermission('view_customer_sector');	// Check permission
		$modelCustomerSector = new CustomerSectorModel(); // Load model

		$filter_data = array(
			'status' => 1
		);
		$customer_sectorArray = array();
		$customer_sectores = $modelCustomerSector->getCustomerSectoreTypes($filter_data);
		//print_r($customer_sectores);
		if ($customer_sectores) {
			foreach ($customer_sectores as $customer_sector) {
				$customer_sectorArray[] = array(
					'id' => (int)$customer_sector->customer_sector_type_id,
					'name' => html_entity_decode($customer_sector->name)
				);
			}
		}
		$response = array(
			'status' => 'success',
			'types' => $customer_sectorArray,
		);

		return $this->setResponseFormat("json")->respond($response, 200);
	}
}
