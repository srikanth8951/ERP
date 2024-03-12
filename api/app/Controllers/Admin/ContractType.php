<?php

namespace App\Controllers\Admin;

use CodeIgniter\RESTful\ResourceController;
use App\Libraries\Auth\User as AuthUser;
use App\Models\Admin\ContractTypeModel;

class ContractType extends ResourceController
{

	public function index()
	{

		$this->validatePermission('view_area');	// Check permission
		$modelContractType = new ContractTypeModel(); // Load model

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

		$total_contractTypes = $modelContractType->getTotalContractTypes($filter_data);
		$contractTypes = $modelContractType->getContractTypes($filter_data);
		if ($contractTypes) {
			$response = array(
				'status' => 'success',
				'message' => lang('ContractType.success_list'),
				'contract_types' => $contractTypes,
				'pagination' => array(
					'total' => (int)$total_contractTypes,
					'length' => $limit,
					'start' => $start,
					'records' => count($contractTypes)
				)
			);

			return $this->setResponseFormat("json")->respond($response, 200);
		} else {
			$response = array(
				'status' => 'error',
				'message' => lang('ContractType.error_list')
			);

			return $this->setResponseFormat("json")->respond($response, 201);
		}
	}

	public function getContractType()
	{
		$response = array();
		$this->validatePermission('view_contract_type');	// Check permission

		$modelContractType = new ContractTypeModel(); // Load model

		$contract_type_id = $this->request->getVar('contract_type_id');
		$contractType = $modelContractType->getContractType($contract_type_id);
		if ($contractType) {
			$response['status'] = 'success';
			$response['message'] = lang('ContractType.success_detail');
			$response['contract_type'] = $contractType;
			return $this->setResponseFormat("json")->respond($response, 200);
		} else {
			$response['status'] = 'error';
			$response['message'] = lang('ContractType.error_detail');
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
		$this->validatePermission('view_contract_type');	// Check permission
		$modelContractType = new ContractTypeModel(); // Load model

		$filter_data = array(
			'removed' => 0,
			'status' => 1
		);
		$contractTypeArray = array();
		$contractTypes = $modelContractType->getContractTypes($filter_data);
		if ($contractTypes) {
			foreach ($contractTypes as $contractType) {
				$contractTypeArray[] = array(
					'id' => (int)$contractType->contract_type_id,
					'name' => html_entity_decode($contractType->name),
					'disable'=> $contractType->disable
				);
			}
		}
		$response = array(
			'status' => 'success',
			'contract_types' => $contractTypeArray,
		);

		return $this->setResponseFormat("json")->respond($response, 200);
	}
}
