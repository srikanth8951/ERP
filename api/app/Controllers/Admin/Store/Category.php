<?php

namespace App\Controllers\Admin\Store;

use CodeIgniter\RESTful\ResourceController;
use App\Libraries\Auth\User as AuthUser;
use App\Models\Admin\StoreCategoryModel;

class Category extends ResourceController
{

    public function index()
    {

        $this->validatePermission('view_area');    // Check permission
        $modelStoreCategory = new StoreCategoryModel(); // Load model

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
            'parent' => false
        );

        $total_store_categories = $modelStoreCategory->getTotalCategories($filter_data);
        $store_categories = $modelStoreCategory->getCategories($filter_data);
        if ($store_categories) {
            $response = array(
                'status' => 'success',
                'message' => lang('StoreCategory.success_list'),
                'store_categories' => $store_categories,
                'pagination' => array(
                    'total' => (int)$total_store_categories,
                    'length' => $limit,
                    'start' => $start,
                    'records' => count($store_categories)
                )
            );
            return $this->setResponseFormat("json")->respond($response, 200);
        } else {
            $response = array(
                'status' => 'success',
                'message' => lang('StoreCategory.error_list')
            );
            return $this->setResponseFormat("json")->respond($response, 201);
        }
    }

    public function getSubCategoryDetails()
    {

        $this->validatePermission('view_area');    // Check permission
        $modelStoreCategory = new StoreCategoryModel(); // Load model

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
            'parent' => true
        );

        $total_store_categories = $modelStoreCategory->getTotalCategories($filter_data);
        $store_categories = $modelStoreCategory->getCategories($filter_data);
        if ($store_categories) {
            foreach ($store_categories as $store_category) {
                $filter_data = array(
                    'removed' => 0,
                    'parent' => false,
                    'category_id' => $store_category->parent
                );
                // print_r($store_category);
                $parents = $modelStoreCategory->getCategories($filter_data);
                if ($parents) {
                    foreach ($parents as $parent) {
                        $details[] = array(
                            'category_id' => $store_category->category_id,
                            'name' => $store_category->name,
                            'parent' => $parent->name,
                            'removed' => $store_category->removed,
                            'status' => $store_category->status,
                            'created_datetime' => $store_category->created_datetime,
                            'updated_datetime' => $store_category->updated_datetime,
                        );
                    }
                } else {
                    $response = array(
                        'status' => 'error',
                        'message' => lang('StoreCategory.error_list')
                    );
                    return $this->setResponseFormat("json")->respond($response, 201);
                }
            }
            $response = array(
                'status' => 'success',
                'message' => lang('StoreCategory.success_list'),
                'store_categories' => $details,
                'pagination' => array(
                    'total' => (int)$total_store_categories,
                    'length' => $limit,
                    'start' => $start,
                    'records' => count($store_categories)
                )
            );
            return $this->setResponseFormat("json")->respond($response, 200);
        } else {
            $response = array(
                'status' => 'error',
                'message' => lang('StoreCategory.error_list')
            );
            return $this->setResponseFormat("json")->respond($response, 201);
        }
    }

    public function getCategory()
    {
        $response = array();
        $this->validatePermission('view_store_category');    // Check permission

        $modelStoreCategory = new StoreCategoryModel(); // Load model

        $category_id = $this->request->getVar('store_category_id');
        $store_category = $modelStoreCategory->getCategory($category_id);
        // print_r($category_id);
        if ($store_category) {
            $response['status'] = 'success';
            $response['message'] = lang('StoreCategory.success_detail');
            $response['store_category'] = $store_category;
            return $this->setResponseFormat("json")->respond($response, 200);
        } else {
            $response['status'] = 'error';
            $response['message'] = lang('StoreCategory.error_detail');
            return $this->setResponseFormat("json")->respond($response, 201);
        }
    }

    public function addCategory()
    {
        $response = array();
        $this->validatePermission('add_store_category');    // Check permission

        $rules = [
            "store_category_name" => "required",
        ];

        $messages = [
            "store_category_name" => [
                "required" => "store_category name is required"
            ],
        ];
        if ($this->validate($rules, $messages)) {
            $modelStoreCategory = new StoreCategoryModel(); // Load model
            $store_category_data = array(
                'store_category_name' => $this->request->getPost('store_category_name'),
                'parent' => $this->request->getPost('parent'),
                // 'code' => $this->request->getPost('store_category_code'),
                // 'is_exist' => 0,
                'status' => $this->request->getPost('status')
            );
            $filter_data = array('removed' => 0);
            $store_category_name = $modelStoreCategory->getCategoryByName($store_category_data['store_category_name'], $filter_data);
            if ($store_category_name) {
                $response['status'] = 'error';
                $response['message'] = lang('Name already exist');
                return $this->setResponseFormat("json")->respond($response, 201);
            } else {
                $add = $modelStoreCategory->addCategory($store_category_data);
                if ($add) {
                    $response['status'] = 'success';
                    $response['message'] = lang('StoreCategory.success_add');
                    return $this->setResponseFormat("json")->respond($response, 200);
                } else {
                    $response['status'] = 'error';
                    $response['message'] = lang('StoreCategory.error_add');
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

    public function editCategory()
    {
        $response = array();

        $this->validatePermission('edit_store_category');    // Check permission

        $rules = [
            "store_category_name" => "required",
        ];

        $messages = [
            "store_category_name" => [
                "required" => "store_category name is required"
            ],
        ];
        if ($this->validate($rules, $messages)) {
            $modelStoreCategory = new StoreCategoryModel(); // Load model

            $category_id = $this->request->getVar('store_category_id');
            // print_r($category_id);
            $store_category = $modelStoreCategory->getCategory($category_id);
            if ($store_category) {
                $store_category_data = array(
                    'store_category_name' => $this->request->getPost('store_category_name'),
                    'parent' => $this->request->getPost('parent'),
                    // 'code' => $this->request->getPost('store_category_code'),
                    'status' => $this->request->getPost('status')
                );
                $filter_data = array('removed' => 0, 'except' => [$category_id]);
                $store_category_name = $modelStoreCategory->getCategoryByName($store_category_data['store_category_name'], $filter_data);
                if ($store_category_name) {
                    $response['status'] = 'error';
                    $response['message'] = lang('Name already exist');
                    return $this->setResponseFormat("json")->respond($response, 201);
                } else {
                    $edit = $modelStoreCategory->editCategory($category_id, $store_category_data);
                    if ($edit) {
                        $response['status'] = 'success';
                        $response['message'] = lang('StoreCategory.success_edit');
                        return $this->setResponseFormat("json")->respond($response, 200);
                    } else {
                        $response['status'] = 'error';
                        $response['message'] = lang('StoreCategory.error_edit');
                        return $this->setResponseFormat("json")->respond($response, 201);
                    }
                }
            } else {
                $response['status'] = 'error';
                $response['message'] = lang('StoreCategory.error_detail');
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

    public function deleteCategory()
    {
        $response = array();

        $this->validatePermission('edit_store_category');    // Check permission
        $modelStoreCategory = new StoreCategoryModel(); // Load model

        $category_id = $this->request->getVar('store_category_id');
        $filter_data = array(
            'removed' => 0,
            'parent' => $category_id
        );
        $parents = $modelStoreCategory->getCategoriesByParent($filter_data);
        if ($parents) {
            $response['status'] = 'error';
            $response['message'] = 'Asset store_category has sub-asset store_category';
            return $this->setResponseFormat("json")->respond($response, 201);
        } else {
            $store_category = $modelStoreCategory->getCategory($category_id);
            if ($store_category) {
                $remove = $modelStoreCategory->removeCategory($category_id);
                if ($remove) {
                    $response['status'] = 'success';
                    $response['message'] = lang('StoreCategory.success_removed');
                    return $this->setResponseFormat("json")->respond($response, 200);
                } else {
                    $response['status'] = 'error';
                    $response['message'] = lang('StoreCategory.error_removed');
                    return $this->setResponseFormat("json")->respond($response, 201);
                }
            } else {
                $response['status'] = 'error';
                $response['message'] = lang('StoreCategory.error_detail');
                return $this->setResponseFormat("json")->respond($response, 201);
            }
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
        $this->validatePermission('view_store_category');    // Check permission
        $modelStoreCategory = new StoreCategoryModel(); // Load model

        $parent = (bool)$this->request->getVar('parent');
        print_r($parent);
        $filter_data = array(
            'removed' => 0,
            'parent' => $parent,
            'status' => 1
        );
        $store_categoryArray = array();
        $store_categories = $modelStoreCategory->getCategories($filter_data);
        if ($store_categories) {
            foreach ($store_categories as $store_category) {
                $store_categoryArray[] = array(
                    'id' => (int)$store_category->category_id,
                    'name' => html_entity_decode($store_category->name)
                );
            }
        }
        $response = array(
            'status' => 'success',
            'store_categories' => $store_categoryArray,
        );

        return $this->setResponseFormat("json")->respond($response, 200);
    }

    public function autocompleteSubCategory()
    {
        $this->validatePermission('view_store_category');    // Check permission
        $modelStoreCategory = new StoreCategoryModel(); // Load model

        $parent = $this->request->getGet('parent');
        
        $filter_data = array(
            'removed' => 0,
            'parent' => $parent,
            'status' => 1
        );
        $storeSubCategoryArray = array();
        $store_subcategories = $modelStoreCategory->getCategoriesByParent($filter_data);
        if ($store_subcategories) {
            foreach ($store_subcategories as $storeSubCategory) {
                $storeSubCategoryArray[] = array(
                    'id' => (int)$storeSubCategory->category_id,
                    'name' => html_entity_decode($storeSubCategory->name)
                );
            }
        }
        $response = array(
            'status' => 'success',
            'store_sub_categories' => $storeSubCategoryArray,
        );

        return $this->setResponseFormat("json")->respond($response, 200);
    }
}
