<?php

namespace App\Controllers\Employee\store;

use CodeIgniter\RESTful\ResourceController;
use App\Libraries\Auth\User as AuthUser;
use App\Libraries\Auth\Employee as AuthEmployee;
use App\Models\Employee\StoreRequestModel;
use App\Models\Employee\StoreProductModel;
use App\Models\Employee\EmployeeModel;

class ProductRequest extends ResourceController
{
    protected $empType = 'store';
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

        $modelStoreRequest = new StoreRequestModel(); // Load model
        $modelEmp = new EmployeeModel(); // Load model
		$empDetail = $modelEmp->getEmployee($this->employeeId);

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
            'order' => $order,
            'region_id' => $empDetail->region_id,
            'branch_id' => $empDetail->branch_id
        );

        $total_product_requests = $modelStoreRequest->getTotalRequests($filter_data);
        $product_requests = $modelStoreRequest->getRequests($filter_data);

        if ($product_requests) {
            foreach ($product_requests as $key => $value) {
                $request_status = getStoreRequestStatusById($value->status);
                if ($request_status) {
                    $product_requests[$key]->status = $request_status;
                }
                $requested_products = $modelStoreRequest->getRequestProducts($value->request_id);
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

        $modelStoreRequest = new StoreRequestModel(); // Load model

        $request_id = $this->request->getVar('request_id');
        $product_request = $modelStoreRequest->getRequest($request_id);
        if ($product_request) {
            $request_status = getStoreRequestStatusById($product_request->status);
            if ($request_status) {
                $product_request->status = $request_status;
            }
            $requested_products = $modelStoreRequest->getRequestProducts($product_request->request_id);
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
            $modelStoreRequest = new StoreRequestModel(); // Load model
            $product_data = $this->request->getPost(null);
            $product_data['requested_by'] = AuthUser::getId();
            $add = $modelStoreRequest->addProduct($product_data);
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
            $modelStoreRequest = new StoreRequestModel(); // Load model

            $request_id = $this->request->getVar('request_id');

            $product_request = $modelStoreRequest->getProductRequests($request_id);
            if ($product_request) {
                $product_data = $this->request->getPost(null);
                $product_data['requested_by'] = AuthUser::getId();
                // print_r($product_id, $product_data);
                $edit = $modelStoreRequest->editProductRequests($request_id, $product_data);
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
        $modelStoreRequest = new StoreRequestModel(); // Load model

        $request_id = $this->request->getVar('request_id');
        $product_request = $modelStoreRequest->getProductRequests($request_id);
        if ($product_request) {
            $remove = $modelStoreRequest->removeProductRequests($request_id);
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

        $modelStoreRequest = new StoreRequestModel(); // Load model
        $modelStoreProduct = new StoreProductModel(); // Load model

        $request_id = $this->request->getVar('request_id');
        $request = $modelStoreRequest->getRequests($request_id);
        if ($request) {
            $stockStatus = [];
            $requested_products = $modelStoreRequest->getRequestProducts($request_id);
            if ($requested_products) {
                foreach ($requested_products as $requested_product) {
                    $product = $modelStoreProduct->getProductStock($requested_product->product_id, ['quantity' => $requested_product->requested_quantity]);
                    array_push($stockStatus, (int)$product);
                }
            }
            if(! $stockStatus || in_array(0, $stockStatus)){
                $response['status'] = 'error';
                $response['message'] = lang('ProductRequests.error_stock');
                return $this->setResponseFormat("json")->respond($response, 201);
            } else {
                $approval_data = array(
                    'is_approved' => 1,
                    'approved_by' => AuthUser::getId()
                );
                $approval = $modelStoreRequest->isuueRequestProduct($request_id, $requested_products, $approval_data);
                if ($approval) {
                    $modelStoreRequest->updateRequest($request_id, ['status' => 9]);
                    $response['status'] = 'success';
                    $response['message'] = lang('ProductRequests.success_approved');
                    return $this->setResponseFormat("json")->respond($response, 200);
                } else {
                    $response['status'] = 'error';
                    $response['message'] = lang('ProductRequests.error_approve');
                    return $this->setResponseFormat("json")->respond($response, 201);
                }
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
        $modelStoreRequest = new StoreRequestModel(); // Load model
        $request_id = $this->request->getVar('request_id');
        $filter_data = array(
            'request_id' => $request_id,
            'removed' => 0,
            'status' => 1,
        );
        $productRequestArray = array();
        $product_requests = $modelStoreRequest->getProductRequest($filter_data);
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

    // Product Stock
    public function addProductStock()
    {
        $response = array();

        $this->validatePermission('edit_product');    // Check permission

        $rules = [
            "quantity" => "required",
        ];

        $messages = [
            "quantity" => [
                "required" => "quantity is required"
            ],
        ];
        if ($this->validate($rules, $messages)) {
            $modelStoreRequest = new StoreRequestModel(); // Load model
            $modelStoreProduct = new StoreProductModel(); // Load model

            $store_request_id = $this->request->getVar('store_request_id');
            $product_id = $this->request->getVar('product_id');
            
            $product = $modelStoreRequest->getRequestProduct($store_request_id, $product_id);
            if ($product) {
                $product_data = $this->request->getPost(null);
                $product_data['updated_by'] = AuthUser::getId();
                
                $add = $modelStoreProduct->addProductStock($product_id, $product_data);
                if ($add) {
                    $response['status'] = 'success';
                    $response['message'] = lang('Product.Stock.success_add');
                    return $this->setResponseFormat("json")->respond($response, 200);
                } else {
                    $response['status'] = 'error';
                    $response['message'] = lang('Product.Stock.error_add');
                    return $this->setResponseFormat("json")->respond($response, 201);
                }
            } else {
                $response['status'] = 'error';
                $response['message'] = lang('Product.error_detail');
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

    public function getProductStock($product_id, $data = [])
    {
        $condition = array(
            'product_id' => (int)$product_id,
        );  

        if (isset($data['status'])) {
            $condition['status'] = (int)$data['status'];
        }

        $builder = $this->cdb->table('store_product');
        $builder->select('*');
        if ($condition) {
            $builder->where($condition);
        }
        if($data['quantity']) {
            $builder->where('quantity <=', $data['quantity']);
        }
        
        $query = $builder->get();    

        if ($query->getNumRows() > 0  ) {
            return $query->getRow();
        } else {
            return 0;
        }
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
