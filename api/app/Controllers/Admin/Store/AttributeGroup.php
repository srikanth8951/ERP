<?php

namespace App\Controllers\Admin\Store;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use CodeIgniter\RESTful\ResourceController;
use App\Libraries\Auth\User as AuthUser;
use App\Models\Admin\StoreAttributeGroupModel;

class AttributeGroup extends ResourceController
{

	function __construct()
	{
	}

	public function index()
	{

		$this->validatePermission('view_area');	// Check permission
		$modelAttributeGroup = new StoreAttributeGroupModel(); // Load model

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
			'order' => $order
		);

		$total_attribute_groups = $modelAttributeGroup->getTotalAttributeGroups($filter_data);
		$attribute_groups = $modelAttributeGroup->getAttributeGroups($filter_data);

		if ($attribute_groups) {
			$response = array(
				'status' => 'success',
				'message' => lang('AttributeGroup.success_list'),
				'attribute_groups' => $attribute_groups,
				'pagination' => array(
					'total' => (int)$total_attribute_groups,
					'length' => $limit,
					'start' => $start,
					'records' => count($attribute_groups)
				)
			);
			return $this->setResponseFormat("json")->respond($response, 200);
		} else {
			$response = array(
				'status' => 'success',
				'message' => lang('AttributeGroup.error_list')
			);
			return $this->setResponseFormat("json")->respond($response, 201);
		}
	}

	public function getAttributeGroup()
	{
		$response = array();
		$this->validatePermission('view_attribute_group');	// Check permission

		$modelAttributeGroup = new StoreAttributeGroupModel(); // Load model

		$attribute_group_id = $this->request->getVar('attribute_group_id');
		$attribute_group = $modelAttributeGroup->getAttributeGroup($attribute_group_id);
		if ($attribute_group) {
			$response['status'] = 'success';
			$response['message'] = lang('AttributeGroup.success_detail');
			$response['attribute_group'] = $attribute_group;
			return $this->setResponseFormat("json")->respond($response, 200);
		} else {
			$response['status'] = 'error';
			$response['message'] = lang('AttributeGroup.error_detail');
			return $this->setResponseFormat("json")->respond($response, 201);
		}
	}

	public function addAttributeGroup()
	{
		$response = array();
		$this->validatePermission('add_attribute_group');	// Check permission

		$rules = [
			"attribute_group_name" => "required",
		];

		$messages = [
			"attribute_group_name" => [
				"required" => "attribute_group name is required"
			],
		];
		if ($this->validate($rules, $messages)) {
			$modelAttributeGroup = new StoreAttributeGroupModel(); // Load model
			$attribute_group_data = array(
				'name' => $this->request->getPost('attribute_group_name'),
				'status' => $this->request->getPost('status')
			);
			$filter_data = array('removed' => 0,);
			$attribute_group_name = $modelAttributeGroup->getAttributeGroupByName($attribute_group_data['name'], $filter_data);
			if ($attribute_group_name) {
				$response['status'] = 'error';
				$response['message'] = lang('Name already exist');
				return $this->setResponseFormat("json")->respond($response, 201);
			} else {
				$add = $modelAttributeGroup->addAttributeGroup($attribute_group_data);
				if ($add) {
					$response['status'] = 'success';
					$response['message'] = lang('AttributeGroup.success_add');
					return $this->setResponseFormat("json")->respond($response, 200);
				} else {
					$response['status'] = 'error';
					$response['message'] = lang('AttributeGroup.error_add');
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

	public function editAttributeGroup()
	{
		$response = array();

		$this->validatePermission('edit_attribute_group');	// Check permission

		$rules = [
			"attribute_group_name" => "required",
		];

		$messages = [
			"attribute_group_name" => [
				"required" => "attribute_group name is required"
			],
		];
		if ($this->validate($rules, $messages)) {
			$modelAttributeGroup = new StoreAttributeGroupModel(); // Load model

			$attribute_group_id = $this->request->getVar('attribute_group_id');

			$attribute_group = $modelAttributeGroup->getAttributeGroup($attribute_group_id);
			if ($attribute_group) {
				$attribute_group_data = array(
					'name' => $this->request->getPost('attribute_group_name'),
					'status' => $attribute_group->status
				);
				$filter_data = array('removed' => 0, 'except' => [$attribute_group_id]);
				$attribute_group_name = $modelAttributeGroup->getAttributeGroupByName($attribute_group_data['name'], $filter_data);
				if ($attribute_group_name) {
					$response['status'] = 'error';
					$response['message'] = lang('Name already exist');
					return $this->setResponseFormat("json")->respond($response, 201);
				} else {
					$edit = $modelAttributeGroup->editAttributeGroup($attribute_group_id, $attribute_group_data);
					if ($edit) {
						$response['status'] = 'success';
						$response['message'] = lang('AttributeGroup.success_edit');
						return $this->setResponseFormat("json")->respond($response, 200);
					} else {
						$response['status'] = 'error';
						$response['message'] = lang('AttributeGroup.error_edit');
						return $this->setResponseFormat("json")->respond($response, 201);
					}
				}
			} else {
				$response['status'] = 'error';
				$response['message'] = lang('AttributeGroup.error_detail');
				return $this->setResponseFormat("json")->respond($response, 201);
			}
		} else {
			$response = [
				'status' => 'error',
				'message' => $this->validator->getErrors()
			];
		}
	}

	public function deleteAttributeGroup()
	{
		$response = array();

		$this->validatePermission('edit_attribute_group');	// Check permission
		$modelAttributeGroup = new StoreAttributeGroupModel(); // Load model

		$attribute_group_id = $this->request->getVar('attribute_group_id');
		$attribute_group = $modelAttributeGroup->getAttributeGroup($attribute_group_id);
		if ($attribute_group) {
			$remove = $modelAttributeGroup->removeAttributeGroup($attribute_group_id);
			if ($remove) {
				$response['status'] = 'success';
				$response['message'] = lang('AttributeGroup.success_removed');
				return $this->setResponseFormat("json")->respond($response, 200);
			} else {
				$response['status'] = 'error';
				$response['message'] = lang('AttributeGroup.error_removed');
				return $this->setResponseFormat("json")->respond($response, 201);
			}
		} else {
			$response['status'] = 'error';
			$response['message'] = lang('AttributeGroup.error_detail');
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
		$this->validatePermission('view_attribute_group');	// Check permission
		$modelAttributeGroup = new StoreAttributeGroupModel(); // Load model

		$filter_data = array(
			'removed' => 0,
			'status' => 1
		);
		$attribute_groupArray = array();
		$attribute_groups = $modelAttributeGroup->getAttributeGroups($filter_data);
		if ($attribute_groups) {
			foreach ($attribute_groups as $attribute_group) {
				$attribute_groupArray[] = array(
					'id' => (int)$attribute_group->attribute_group_id,
					'name' => html_entity_decode($attribute_group->name)
				);
			}
		}
		$response = array(
			'status' => 'success',
			'attribute_groups' => $attribute_groupArray,
		);

		return $this->setResponseFormat("json")->respond($response, 200);
	}

}
