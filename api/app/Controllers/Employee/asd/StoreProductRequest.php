<?php

namespace App\Controllers\Employee\asd;

use CodeIgniter\RESTful\ResourceController;
use App\Libraries\Auth\User as AuthUser;
use App\Libraries\Auth\Employee as AuthEmployee;
use App\Models\Employee\StoreRequestModel;
use App\Models\Employee\EmployeeModel;


class StoreProductRequest extends ResourceController
{
    protected $empType = 'asd_head';
    protected $employeeId;

    function __construct()
    {
        helper(['default', 'user', 'common']);
    }

    public function index()
    {

        $this->validatePermission('view_area');    // Check permission

        if (!$this->isEmployee()) {
            $response = array(
                'status' => 'error',
                'message' => lang('Common.error_employee_login')
            );

            return $this->setResponseFormat("json")->respond($response, 201);
        }

        $modelProductRequest = new StoreRequestModel(); // Load model
        $modelEmp = new EmployeeModel(); // Load model
        $empDetail = $modelEmp->getEmployee($this->employeeId);

        $start = $this->request->getVar('start');
        if ($start) {
            $start = (int)$start;
        } else {
            $start = 1;
        }

        $length = $this->request->getVar('length');
        if ($length) {
            $limit = (int)$length;
        } else {
            $limit = 10;
        }

        $search = $this->request->getVar('search');
        if ($search) {
            $search = $search;
        } else {
            $search = '';
        }

        $sort = $this->request->getVar('sort_column');
        if ($sort) {
            $sort = $sort;
        } else {
            $sort = '';
        }

        $order = $this->request->getVar('sort_order');
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
            'order' => $order,
            'region_id' => $empDetail->region_id,
            'branch_id' => $empDetail->branch_id,
        );

        $total_product_requests = $modelProductRequest->getTotalRequests($filter_data);
        $product_requests = $modelProductRequest->getRequests($filter_data);

        if ($product_requests) {
            foreach ($product_requests as $key => $value) {
                $request_status = getStoreRequestStatusById($value->status);
                $product_requests[$key]->status = $request_status;

                $requested_products = $modelProductRequest->getRequestProducts($value->request_id);
                $product_requests[$key]->products = $requested_products;
            }
            $response = array(
                'status' => 'success',
                'message' => lang('ProductRequests.success_list'),
                'product_requests' => $product_requests,
                'pagination' => array(
                    'total' => (int)$total_product_requests,
                    'length' => $limit,
                    'start' => $start,
                    'records' => count($product_requests)
                )
            );
            return $this->setResponseFormat("json")->respond($response, 200);
        } else {
            $response = array(
                'status' => 'success',
                'message' => lang('ProductRequests.error_list')
            );
            return $this->setResponseFormat("json")->respond($response, 201);
        }
    }

    public function getProductRequest()
    {
        $response = array();
        $this->validatePermission('view_product');    // Check permission

        if (!$this->isEmployee()) {
            $response = array(
                'status' => 'error',
                'message' => lang('Common.error_employee_login')
            );

            return $this->setResponseFormat("json")->respond($response, 201);
        }

        $modelProductRequest = new StoreRequestModel(); // Load model

        $request_id = $this->request->getVar('request_id');
        $product_request = $modelProductRequest->getRequest($request_id);
        if ($product_request) {
            $request_status = getStoreRequestStatusById($product_request->status);
            if ($request_status) {
                $product_request->status = $request_status;
            }
            $requested_products = $modelProductRequest->getRequestProducts($product_request->request_id);
            if ($requested_products) {
                $product_request->products = $requested_products;
            }
            $response['status'] = 'success';
            $response['message'] = lang('ProductRequests.success_detail');
            $response['product_request'] = $product_request;
            return $this->setResponseFormat("json")->respond($response, 200);
        } else {
            $response['status'] = 'error';
            $response['message'] = lang('ProductRequests.error_detail');
            return $this->setResponseFormat("json")->respond($response, 201);
        }
    }

    public function addProductRequests()
    {
        $response = array();
        $this->validatePermission('add_product');    // Check permission

        if (!$this->isEmployee()) {
            $response = array(
                'status' => 'error',
                'message' => lang('Common.error_employee_login')
            );

            return $this->setResponseFormat("json")->respond($response, 201);
        }
        $rules = [
            "title" => "required",
        ];

        $messages = [
            "title" => [
                "required" => "product_request name is required"
            ],
        ];
        // if ($this->validate($rules, $messages)) {
        $modelProductRequest = new StoreRequestModel(); // Load model
        $product_data = $this->request->getPost(null);
        $product_data['requested_by'] = AuthUser::getId();
        $product_data['status'] = 1; // status 1-> request rised 
        $product_data['removed'] = 0;
        $request_products = $this->request->getPost('product_data');
        // Log
        if (!is_array($request_products)) {
            $request_products = json_decode($request_products, true);
        }
        $add = $modelProductRequest->addRequest($product_data);
        if ($add) {
            $product = $modelProductRequest->addProductRequest($add, $request_products);
            if ($product) {
                $response['status'] = 'success';
                $response['message'] = lang('ProductRequests.success_add');
                return $this->setResponseFormat("json")->respond($response, 200);
            } else {
                $response['status'] = 'error';
                $response['message'] = lang('ProductRequests.error_add');
                return $this->setResponseFormat("json")->respond($response, 201);
            }
        } else {
            $response['status'] = 'error';
            $response['message'] = lang('ProductRequests.error_add');
            return $this->setResponseFormat("json")->respond($response, 201);
        }
        // } else {
        //     $response = [
        //         'status' => 'error',
        //         'message' => $this->validator->getErrors()
        //     ];
        //     return $this->setResponseFormat("json")->respond($response, 201);
        // }
    }

    public function editProductRequests()
    {
        $response = array();

        $this->validatePermission('edit_product');    // Check permission

        if (!$this->isEmployee()) {
            $response = array(
                'status' => 'error',
                'message' => lang('Common.error_employee_login')
            );

            return $this->setResponseFormat("json")->respond($response, 201);
        }

        $rules = [
            "name" => "required",
        ];

        $messages = [
            "name" => [
                "required" => "product_request name is required"
            ],
        ];
        if ($this->validate($rules, $messages)) {
            $modelProductRequest = new StoreRequestModel(); // Load model

            $product_id = $this->request->getVar('product_id');

            $product_request = $modelProductRequest->getProductRequests($product_id);
            if ($product_request) {
                $product_data = $this->request->getPost(null);
                $product_data['created_by'] = AuthUser::getId();
                // print_r($product_id, $product_data);
                $edit = $modelProductRequest->editProductRequests($product_id, $product_data);
                if ($edit) {
                    $response['status'] = 'success';
                    $response['message'] = lang('ProductRequests.success_edit');
                    return $this->setResponseFormat("json")->respond($response, 200);
                } else {
                    $response['status'] = 'error';
                    $response['message'] = lang('ProductRequests.error_edit');
                    return $this->setResponseFormat("json")->respond($response, 201);
                }
            } else {
                $response['status'] = 'error';
                $response['message'] = lang('ProductRequests.error_detail');
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

    public function deleteProductRequests()
    {
        $response = array();

        $this->validatePermission('edit_product');    // Check permission

        if (!$this->isEmployee()) {
            $response = array(
                'status' => 'error',
                'message' => lang('Common.error_employee_login')
            );

            return $this->setResponseFormat("json")->respond($response, 201);
        }

        $modelProductRequest = new StoreRequestModel(); // Load model

        $product_id = $this->request->getVar('product_id');
        $product_request = $modelProductRequest->getProductRequests($product_id);
        if ($product_request) {
            $remove = $modelProductRequest->removeProductRequests($product_id);
            if ($remove) {
                $response['status'] = 'success';
                $response['message'] = lang('ProductRequests.success_removed');
                return $this->setResponseFormat("json")->respond($response, 200);
            } else {
                $response['status'] = 'error';
                $response['message'] = lang('ProductRequests.error_removed');
                return $this->setResponseFormat("json")->respond($response, 201);
            }
        } else {
            $response['status'] = 'error';
            $response['message'] = lang('ProductRequests.error_detail');
            return $this->setResponseFormat("json")->respond($response, 201);
        }
    }

    public function productRequestAprroval()
    {
        $response = array();

        if (!$this->isEmployee()) {
            $response = array(
                'status' => 'error',
                'message' => lang('Common.error_employee_login')
            );

            return $this->setResponseFormat("json")->respond($response, 201);
        }

        $modelProductRequest = new StoreRequestModel(); // Load model

        $request_id = $this->request->getVar('request_id');
        $request = $modelProductRequest->getRequests($request_id);
        if ($request) {
            $approval_data = array(
                'is_approved' => 1,
                'approved_by' => AuthUser::getId()
            );
            $approval = $modelProductRequest->addRequestApproval($request_id, $approval_data);
            if ($approval) {
                $modelProductRequest->updateRequest($request_id, ['status' => 5]);
                $response['status'] = 'success';
                $response['message'] = lang('ProductRequests.success_approved');
                return $this->setResponseFormat("json")->respond($response, 200);
            } else {
                $response['status'] = 'error';
                $response['message'] = lang('ProductRequests.error_approve');
                return $this->setResponseFormat("json")->respond($response, 201);
            }
        } else {
            $response['status'] = 'error';
            $response['message'] = lang('ProductRequests.error_detail');
            return $this->setResponseFormat("json")->respond($response, 201);
        }
    }

    public function productRequestReject()
    {
        $response = array();

        if (!$this->isEmployee()) {
            $response = array(
                'status' => 'error',
                'message' => lang('Common.error_employee_login')
            );

            return $this->setResponseFormat("json")->respond($response, 201);
        }

        $modelProductRequest = new StoreRequestModel(); // Load model

        $request_id = $this->request->getVar('request_id');
        $request = $modelProductRequest->getRequests($request_id);
        if ($request) {
            $approval_data = array(
                'is_rejected' => 1,
                'rejected_by' => AuthUser::getId()
            );
            $approval = $modelProductRequest->addRequestReject($request_id, $approval_data);
            if ($approval) {
                $modelProductRequest->updateRequest($request_id, ['status' => 6]);
                $response['status'] = 'success';
                $response['message'] = lang('ProductRequests.success_reject');
                return $this->setResponseFormat("json")->respond($response, 200);
            } else {
                $response['status'] = 'error';
                $response['message'] = lang('ProductRequests.error_reject');
                return $this->setResponseFormat("json")->respond($response, 201);
            }
        } else {
            $response['status'] = 'error';
            $response['message'] = lang('ProductRequests.error_detail');
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
        $this->validatePermission('view_product');    // Check permission
        $modelProductRequest = new StoreRequestModel(); // Load model
        $category_id = $this->request->getVar('category_id');
        $sub_category_id = $this->request->getVar('sub_category_id');
        $filter_data = array(
            'category_id' => $category_id,
            'sub_category_id' => $sub_category_id,
            'removed' => 0,
            'status' => 1,
        );
        $productRequestArray = array();
        $product_requests = $modelProductRequest->getProductRequest($filter_data);
        if ($product_requests) {
            foreach ($product_requests as $product_request) {
                $productRequestArray[] = array(
                    'id' => (int)$product_request->product_id,
                    'name' => html_entity_decode($product_request->name)
                );
            }
        }
        $response = array(
            'status' => 'success',
            'product_requests' => $productRequestArray,
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
