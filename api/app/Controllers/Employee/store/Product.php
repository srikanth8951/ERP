<?php

namespace App\Controllers\Employee\store;

use CodeIgniter\RESTful\ResourceController;
use App\Libraries\Auth\User as AuthUser;
use App\Models\Employee\StoreProductModel;

class Product extends ResourceController
{

    function __construct()
    {
    }

    public function index()
    {

        $this->validatePermission('view_area');    // Check permission
        $modelProduct = new StoreProductModel(); // Load model

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
            'order' => "DESC"
        );

        $total_products = $modelProduct->getTotalProducts($filter_data);
        $products = $modelProduct->getProducts($filter_data);

        if ($products) {
            $response = array(
                'status' => 'success',
                'message' => lang('Product.success_list'),
                'products' => $products,
                'pagination' => array(
                    'total' => (int)$total_products,
                    'length' => $limit,
                    'start' => $start,
                    'records' => count($products)
                )
            );
            return $this->setResponseFormat("json")->respond($response, 200);
        } else {
            $response = array(
                'status' => 'success',
                'message' => lang('Product.error_list')
            );
            return $this->setResponseFormat("json")->respond($response, 201);
        }
    }

    public function getProduct()
    {
        $response = array();
        $this->validatePermission('view_product');    // Check permission

        $modelProduct = new StoreProductModel(); // Load model

        $product_id = $this->request->getVar('product_id');
        $product = $modelProduct->getProduct($product_id);
        if ($product) {
            $response['status'] = 'success';
            $response['message'] = lang('Product.success_detail');
            $response['product'] = $product;
            return $this->setResponseFormat("json")->respond($response, 200);
        } else {
            $response['status'] = 'error';
            $response['message'] = lang('Product.error_detail');
            return $this->setResponseFormat("json")->respond($response, 201);
        }
    }

    public function addProduct()
    {
        $response = array();
        $this->validatePermission('add_product');    // Check permission

        $rules = [
            "name" => "required",
        ];

        $messages = [
            "name" => [
                "required" => "product name is required"
            ],
        ];
        if ($this->validate($rules, $messages)) {
            $modelProduct = new StoreProductModel(); // Load model
            $product_data = $this->request->getPost(null);
            $product_data['created_by'] = AuthUser::getId();
            $add = $modelProduct->addProduct($product_data);
            if ($add) {
                $response['status'] = 'success';
                $response['message'] = lang('Product.success_add');
                return $this->setResponseFormat("json")->respond($response, 200);
            } else {
                $response['status'] = 'error';
                $response['message'] = lang('Product.error_add');
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

    public function editProduct()
    {
        $response = array();

        $this->validatePermission('edit_product');    // Check permission

        $rules = [
            "name" => "required",
        ];

        $messages = [
            "name" => [
                "required" => "product name is required"
            ],
        ];
        if ($this->validate($rules, $messages)) {
            $modelProduct = new StoreProductModel(); // Load model

            $product_id = $this->request->getVar('product_id');

            $product = $modelProduct->getProduct($product_id);
            if ($product) {
                $product_data = $this->request->getPost(null);
                $product_data['created_by'] = AuthUser::getId();
                // print_r($product_id, $product_data);
                $edit = $modelProduct->editProduct($product_id, $product_data);
                if ($edit) {
                    $response['status'] = 'success';
                    $response['message'] = lang('Product.success_edit');
                    return $this->setResponseFormat("json")->respond($response, 200);
                } else {
                    $response['status'] = 'error';
                    $response['message'] = lang('Product.error_edit');
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

    public function deleteProduct()
    {
        $response = array();

        $this->validatePermission('edit_product');    // Check permission
        $modelProduct = new StoreProductModel(); // Load model

        $product_id = $this->request->getVar('product_id');
        $product = $modelProduct->getProduct($product_id);
        if ($product) {
            $remove = $modelProduct->removeProduct($product_id);
            if ($remove) {
                $response['status'] = 'success';
                $response['message'] = lang('Product.success_removed');
                return $this->setResponseFormat("json")->respond($response, 200);
            } else {
                $response['status'] = 'error';
                $response['message'] = lang('Product.error_removed');
                return $this->setResponseFormat("json")->respond($response, 201);
            }
        } else {
            $response['status'] = 'error';
            $response['message'] = lang('Product.error_detail');
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
        $modelProduct = new StoreProductModel(); // Load model
        $category_id = $this->request->getVar('category_id');
        $sub_category_id = $this->request->getVar('sub_category_id');
        $filter_data = array(
            'category_id' => $category_id,
            'sub_category_id' => $sub_category_id,
            'removed' => 0,
            'status' => 1,
        );
        $productArray = array();
        $products = $modelProduct->getProducts($filter_data);
        if ($products) {
            foreach ($products as $product) {
                $productArray[] = array(
                    'id' => (int)$product->product_id,
                    'name' => html_entity_decode($product->name)
                );
            }
        }
        $response = array(
            'status' => 'success',
            'products' => $productArray,
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
            $modelProduct = new StoreProductModel(); // Load model

            $product_id = $this->request->getVar('product_id');

            $product = $modelProduct->getProduct($product_id);
            if ($product) {
                $product_data = $this->request->getPost(null);
                $product_data['updated_by'] = AuthUser::getId();

                $add = $modelProduct->addProductStock($product_id, $product_data);
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
}
