<?php

namespace App\Controllers\Admin\Store;

use CodeIgniter\RESTful\ResourceController;
use App\Libraries\Auth\User as AuthUser;
use App\Models\Admin\StoreAttributeModel;
use App\Models\Admin\StoreAttributeGroupModel;

class Attribute extends ResourceController
{


    public function index()
    {

        $this->validatePermission('view_attribute');    // Check permission
        $modelAttribute = new StoreAttributeModel(); // Load model

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

        $total_attributes = $modelAttribute->getTotalAttributes($filter_data);
        $attributes = $modelAttribute->getAttributes($filter_data);

        $modelAttributeGroup = new StoreAttributeGroupModel(); // Load model

        $rfilter_data = array(
            'removed' => 0,
            'status' => 1
        );

        //upload data exixt
        // $uploadAttribute = $modelAttribute->cancelUpload();
        //end


        if ($attributes) {
            $response = array(
                'status' => 'success',
                'message' => lang('Attribute.success_list'),
                'attributes' => $attributes,
                'pagination' => array(
                    'total' => (int)$total_attributes,
                    'length' => $limit,
                    'start' => $start,
                    'records' => count($attributes)
                )
            );
            return $this->setResponseFormat("json")->respond($response, 200);
        } else {
            $response = array(
                'status' => 'error',
                'message' => lang('Attribute.error_list')
            );
            return $this->setResponseFormat("json")->respond($response, 201);
        }
    }

    public function getAttribute()
    {
        $response = array();
        $this->validatePermission('view_attribute');    // Check permission

        $modelAttribute = new StoreAttributeModel(); // Load model

        $attribute_id = $this->request->getVar('attribute_id');
        $attribute = $modelAttribute->getAttribute($attribute_id);
        if ($attribute) {
            $response['status'] = 'success';
            $response['message'] = lang('Attribute.success_detail');
            $response['attribute'] = $attribute;
            return $this->setResponseFormat("json")->respond($response, 200);
        } else {
            $response['status'] = 'error';
            $response['message'] = lang('Attribute.error_detail');
            return $this->setResponseFormat("json")->respond($response, 201);
        }
    }

    public function addAttribute()
    {
        $response = array();
        $this->validatePermission('add_attribute');    // Check permission

        $rules = [
            "attribute_group_id"   => "required",
            // 'area_id'     => "required",
            "attribute_name" => "required",
        ];

        $messages = [
            "attribute_group_id" => [
                "required" => "attribute_group is required"
            ],
            // "area_id" => [
            // 	"required" => "area is required"
            // ],
            "attribute_name" => [
                "required" => "attribute name is required"
            ],
        ];
        if ($this->validate($rules, $messages)) {
            $modelAttribute = new StoreAttributeModel(); // Load model
            $attribute_name = $this->request->getPost('attribute_name');
            $attribute_group_id = $this->request->getPost('attribute_group_id');

            $attribute_data = array(
                'attribute_group_id' => $attribute_group_id,
                'name' => $attribute_name,
                'status' => $this->request->getPost('status')
            );
            $filter_data = array(
                'attribute_group_id' => $attribute_group_id,
                'removed' => 0
            );
            $attribute = $modelAttribute->getAttributeByName($attribute_name, $filter_data);
            if ($attribute) {
                $response['status'] = 'error';
                $response['message'] = lang('Attribute.error_exist');
                return $this->setResponseFormat("json")->respond($response, 201);
            } else {
                $add = $modelAttribute->addAttribute($attribute_data);
                if ($add) {
                    $response['status'] = 'success';
                    $response['message'] = lang('Attribute.success_add');
                    return $this->setResponseFormat("json")->respond($response, 200);
                } else {
                    $response['status'] = 'error';
                    $response['message'] = lang('Attribute.error_add');
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

    public function editAttribute()
    {
        $response = array();

        $this->validatePermission('edit_attribute');    // Check permission

        $rules = [
            "attribute_group_id" => "required",
            "attribute_name" => "required",
        ];

        $messages = [
            "attribute_group_id" => [
                "required" => "attribute_group is required"
            ],
            "attribute_name" => [
                "required" => "attribute name is required"
            ],
        ];
        if ($this->validate($rules, $messages)) {
            $modelAttribute = new StoreAttributeModel(); // Load model

            $attribute_id = $this->request->getVar('attribute_id');

            $attribute = $modelAttribute->getAttribute($attribute_id);
            if ($attribute) {
                $attribute_data = array(
                    'attribute_group_id' => $this->request->getPost('attribute_group_id'),
                    'name' => $this->request->getPost('attribute_name'),
                    'status' => $this->request->getPost('status')
                );
                $filter_data = array(
                    'removed' => 0,
                    'attribute_group_id' => $attribute_data['attribute_group_id'],
                    'except' => [$attribute_id]
                );
                $attribute_name = $modelAttribute->getAttributeByName($attribute_data['name'], $filter_data);
                if ($attribute_name) {
                    $response['status'] = 'error';
                    $response['message'] = lang('Attribute.error_exist');
                    return $this->setResponseFormat("json")->respond($response, 201);
                } else {
                    $edit = $modelAttribute->editAttribute($attribute_id, $attribute_data);
                    if ($edit) {
                        $response['status'] = 'success';
                        $response['message'] = lang('Attribute.success_edit');
                        return $this->setResponseFormat("json")->respond($response, 200);
                    } else {
                        $response['status'] = 'error';
                        $response['message'] = lang('Attribute.error_edit');
                        return $this->setResponseFormat("json")->respond($response, 201);
                    }
                }
            } else {
                $response['status'] = 'error';
                $response['message'] = lang('Attribute.error_detail');
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

    public function deleteAttribute()
    {
        $response = array();

        $this->validatePermission('edit_attribute');    // Check permission
        $modelAttribute = new StoreAttributeModel(); // Load model

        $attribute_id = $this->request->getVar('attribute_id');
        $attribute = $modelAttribute->getAttribute($attribute_id);
        if ($attribute) {
            $remove = $modelAttribute->removeAttribute($attribute_id);
            if ($remove) {
                $response['status'] = 'success';
                $response['message'] = lang('Attribute.success_removed');
                return $this->setResponseFormat("json")->respond($response, 200);
            } else {
                $response['status'] = 'error';
                $response['message'] = lang('Attribute.error_removed');
                return $this->setResponseFormat("json")->respond($response, 201);
            }
        } else {
            $response['status'] = 'error';
            $response['message'] = lang('Attribute.error_detail');
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
        $this->validatePermission('view_attribute');    // Check permission
        $modelAttribute = new StoreAttributeModel(); // Load model

        $attribute_group_id = $this->request->getVar('attribute_group_id');
        $filter_data = array(
            // 'attribute_group_id' => $attribute_group_id ?? 0,
            'removed' => 0,
            'status' => 1
        );
        $attributeArray = array();
        $attributes = $modelAttribute->getAttributes($filter_data);
        //print_r($attributes);
        if ($attributes) {
            foreach ($attributes as $attribute) {
                $attributeArray[] = array(
                    'id' => (int)$attribute->attribute_id,
                    'name' => html_entity_decode($attribute->name)
                );
            }
        }
        $response = array(
            'status' => 'success',
            'attributes' => $attributeArray,
        );

        return $this->setResponseFormat("json")->respond($response, 200);
    }
}
