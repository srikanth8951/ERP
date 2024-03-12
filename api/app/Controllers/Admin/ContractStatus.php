<?php

namespace App\Controllers\Admin;

use CodeIgniter\RESTful\ResourceController;
use App\Libraries\Auth\User as AuthUser;
use App\Models\Admin\ContractStatusModel;

class ContractStatus extends ResourceController
{

	public function index()
	{

		$this->validatePermission('view_area');	// Check permission
		$modelContractStatus = new ContractStatusModel(); // Load model

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

		$total_contractStatuses = $modelContractStatus->getTotalContractStatuss($filter_data);
		$contractStatuses = $modelContractStatus->getContractStatuss($filter_data);
		if ($contractStatuses) {
			$response = array(
				'status' => 'success',
				'message' => lang('ContractStatus.success_list'),
				'contract_statuss' => $contractStatuses,
				'pagination' => array(
					'total' => (int)$total_contractStatuses,
					'length' => $limit,
					'start' => $start,
					'records' => count($contractStatuses)
				)
			);

			return $this->setResponseFormat("json")->respond($response, 200);
		} else {
			$response = array(
				'status' => 'error',
				'message' => lang('ContractStatus.error_list')
			);

			return $this->setResponseFormat("json")->respond($response, 201);
		}
	}

	public function getContractStatus()
	{
		$response = array();
		$this->validatePermission('view_contract_status');	// Check permission

		$modelContractStatus = new ContractStatusModel(); // Load model

		$contract_status_id = $this->request->getVar('contract_status_id');
		$contractStatus = $modelContractStatus->getContractStatus($contract_status_id);
		if ($contractStatus) {
			$response['status'] = 'success';
			$response['message'] = lang('ContractStatus.success_detail');
			$response['contract_status'] = $contractStatus;
			return $this->setResponseFormat("json")->respond($response, 200);
		} else {
			$response['status'] = 'error';
			$response['message'] = lang('ContractStatus.error_detail');
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
		$this->validatePermission('view_contract_status');	// Check permission
		$modelContractStatus = new ContractStatusModel(); // Load model

		$type = $this->request->getVar('formType');
		$filter_data = array(
			'removed' => 0,
			'status' => 1,
			'formType' => $type
		);
		$contractStatusArray = array();
		$contractStatuses = $modelContractStatus->getContractStatuss($filter_data);
		if ($contractStatuses) {
			foreach ($contractStatuses as $contractStatus) {
				$contractStatusArray[] = array(
					'id' => (int)$contractStatus->contract_status_id,
					'name' => html_entity_decode($contractStatus->name)
				);
			}
		}
		$response = array(
			'status' => 'success',
			'contract_statuses' => $contractStatusArray,
		);

		return $this->setResponseFormat("json")->respond($response, 200);
	}
}
