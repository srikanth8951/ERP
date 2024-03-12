<?php

namespace App\Controllers\Admin\Store;

use CodeIgniter\RESTful\ResourceController;
use App\Libraries\Auth\User as AuthUser;
use App\Models\Admin\StoreProductRequestModel;

class ProductRequest extends ResourceController
{

    function __construct()
    {
        helper("common");
    }

    public function index()
    {

        $this->validatePermission('view_area');    // Check permission
        $modelProductRequest = new StoreProductRequestModel(); // Load model

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

        $total_product_requests = $modelProductRequest->getTotalRequests($filter_data);
        $product_requests = $modelProductRequest->getRequests($filter_data);

        if ($product_requests) {
            foreach ($product_requests as $key => $value) {
                $request_status = getStoreRequestStatusById($value->status);
                if ($request_status) {
                    $product_requests[$key]->status = $request_status;
                }
                $requested_products = $modelProductRequest->getRequestProducts($value->request_id);
                if ($requested_products) {
                    $product_requests[$key]->products = $requested_products;
                }
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

        $modelProductRequest = new StoreProductRequestModel(); // Load model

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
        $this->validatePermission('add_request');    // Check permission

        $rules = [
            "title" => "required",
        ];

        $messages = [
            "title" => [
                "required" => "product_request title is required"
            ],
        ];
        if ($this->validate($rules, $messages)) {
            $modelProductRequest = new StoreProductRequestModel(); // Load model
            $product_data = $this->request->getPost(null);
            $product_data['requested_by'] = AuthUser::getId();
            $add = $modelProductRequest->addProduct($product_data);
            if ($add) {
                $response['status'] = 'success';
                $response['message'] = lang('ProductRequests.success_add');
                return $this->setResponseFormat("json")->respond($response, 200);
            } else {
                $response['status'] = 'error';
                $response['message'] = lang('ProductRequests.error_add');
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

    public function editProductRequests()
    {
        $response = array();

        $this->validatePermission('edit_request');    // Check permission

        $rules = [
            "title" => "required",
        ];

        $messages = [
            "title" => [
                "required" => "product_request title is required"
            ],
        ];
        if ($this->validate($rules, $messages)) {
            $modelProductRequest = new StoreProductRequestModel(); // Load model

            $request_id = $this->request->getVar('request_id');

            $product_request = $modelProductRequest->getProductRequests($request_id);
            if ($product_request) {
                $product_data = $this->request->getPost(null);
                $product_data['requested_by'] = AuthUser::getId();
                // print_r($product_id, $product_data);
                $edit = $modelProductRequest->editProductRequests($request_id, $product_data);
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
        $modelProductRequest = new StoreProductRequestModel(); // Load model

        $request_id = $this->request->getVar('request_id');
        $product_request = $modelProductRequest->getProductRequests($request_id);
        if ($product_request) {
            $remove = $modelProductRequest->removeProductRequests($request_id);
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
        $modelProductRequest = new StoreProductRequestModel(); // Load model
        $request_id = $this->request->getVar('request_id');
        $filter_data = array(
            'request_id' => $request_id,
            'removed' => 0,
            'status' => 1,
        );
        $productRequestArray = array();
        $product_requests = $modelProductRequest->getProductRequest($filter_data);
        if ($product_requests) {
            foreach ($product_requests as $product_request) {
                $productRequestArray[] = array(
                    'id' => (int)$product_request->request_id,
                    'name' => html_entity_decode($product_request->title)
                );
            }
        }
        $response = array(
            'status' => 'success',
            'product_requests' => $productRequestArray,
        );

        return $this->setResponseFormat("json")->respond($response, 200);
    }
}
