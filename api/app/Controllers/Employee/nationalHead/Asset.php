<?php

namespace App\Controllers\Employee\nationalHead;

use CodeIgniter\RESTful\ResourceController;
use App\Libraries\Auth\User as AuthUser;
use App\Libraries\Auth\Employee as AuthEmployee;
use App\Models\Employee\ContractJobAssetModel;
use App\Models\Employee\AssetGroupModel;

class Asset extends ResourceController
{

    protected $empType = 'national_head';
    protected $employeeId;

	public function __construct()
	{
		helper('common');
	}

    public function index()
    {

        $this->validatePermission('view_asset');    // Check permission

		if (!$this->isEmployee()) {
            $response = array(
                'status' => 'error',
                'message' => lang('Common.error_employee_login')
            );

            return $this->setResponseFormat("json")->respond($response, 201);
        }

        $modelAsset = new ContractJobAssetModel(); // Load model

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
            // 'order' => $order,
            'is_exist' => 0,
        );

        $total_assets = $modelAsset->getTotalAssets($filter_data);
        $assets = $modelAsset->getAssetsList($filter_data);
        if ($assets) {
            $response = array(
                'status' => 'success',
                'message' => lang('AssetGroup.success_list'),
                'assets' => [
                    'data' => $assets,
                    'pagination' => array(
                        'total' => (int)$total_assets,
                        'length' => $limit,
                        'start' => $start,
                        'records' => count($assets)
                    )
                ]
            );
            return $this->setResponseFormat("json")->respond($response, 200);
        } else {
            $response = array(
                'status' => 'success',
                'message' => lang('AssetGroup.error_list')
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

    public function getAsset()
	{
		$response = array();
		$this->validatePermission('view_asset');	// Check permission

		if (!$this->isEmployee()) {
            $response = array(
                'status' => 'error',
                'message' => lang('Common.error_employee_login')
            );

            return $this->setResponseFormat("json")->respond($response, 201);
        }

		$modelAsset = new ContractJobAssetModel(); // Load model

		$asset_id = $this->request->getVar('asset_id');
		$asset = $modelAsset->getAsset($asset_id);
		if ($asset) {
            $assetJobs = $modelAsset->getAssetJobs($asset_id, [
                'job_status' => 1,
                'status' => 1
            ]); // Get asset jobs
            
            if ($assetJobs) {
                $asset->jobs = $assetJobs;
            } else {
                $asset->jobs = [];
            }
            
            $response['status'] = 'success';
			$response['message'] = lang('Asset.success_detail');
			$response['asset'] = $asset;
			return $this->setResponseFormat("json")->respond($response, 200);
		} else {
			$response['status'] = 'error';
			$response['message'] = lang('Asset.error_detail');
			return $this->setResponseFormat("json")->respond($response, 201);
		}
	}

    public function autocomplete()
	{
		$this->validatePermission('view_asset');	// Check permission
		$modelAsset = new ContractJobAssetModel(); // Load model

		$asset_id = $this->request->getVar('asset_id');
		$filter_data = array(
			'asset_id' => $asset_id ?? 0,
			'removed' => 0,
			'status' => 1,
            'is_exist' => 0,
		);
		$assetArray = array();
		$assets = $modelAsset->getAssets($filter_data);
		if ($assets) {
			foreach ($assets as $asset) {
				$assetArray[] = array(
					'id' => (int)$asset->asset_id,
					'name' => html_entity_decode($asset->name)
				);
			}
		}
		$response = array(
			'status' => 'success',
			'assets' => $assetArray,
		);

		return $this->setResponseFormat("json")->respond($response, 200);
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

