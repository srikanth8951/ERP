<?php

namespace App\Controllers\Customer;

use CodeIgniter\RESTful\ResourceController;
use App\Libraries\Auth\User as AuthUser;
use App\Models\Customer\CustomerSectorModel;


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
