<?php

namespace App\Controllers\Admin;

use CodeIgniter\RESTful\ResourceController;
use App\Libraries\Auth\User as AuthUser;
use App\Models\Admin\AssetGroupModel;

class AssetGroup extends ResourceController
{

    public function index()
    {

        $this->validatePermission('view_area');    // Check permission
        $modelAssetGroup = new AssetGroupModel(); // Load model

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

        $total_groups = $modelAssetGroup->getTotalGroups($filter_data);
        $groups = $modelAssetGroup->getGroups($filter_data);
        if ($groups) {
            $response = array(
                'status' => 'success',
                'message' => lang('AssetGroup.success_list'),
                'groups' => $groups,
                'pagination' => array(
                    'total' => (int)$total_groups,
                    'length' => $limit,
                    'start' => $start,
                    'records' => count($groups)
                )
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

    public function getSubGroupDetails()
    {

        $this->validatePermission('view_area');    // Check permission
        $modelAssetGroup = new AssetGroupModel(); // Load model

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

        $total_groups = $modelAssetGroup->getTotalGroups($filter_data);
        $groups = $modelAssetGroup->getGroups($filter_data);
        if ($groups) {
            foreach ($groups as $group) {
                $filter_data = array(
                    'removed' => 0,
                    'parent' => false,
                    'asset_group_id' => $group->parent
                );
                // print_r($group);
                $parents = $modelAssetGroup->getGroups($filter_data);
                if ($parents) {
                    foreach ($parents as $parent) {
                        $details[] = array(
                            'asset_group_id' => $group->asset_group_id,
                            'name' => $group->name,
                            'parent' => $parent->name,
                            'removed' => $group->removed,
                            'status' => $group->status,
                            'created_datetime' => $group->created_datetime,
                            'updated_datetime' => $group->updated_datetime,
                        );
                    }
                } else {
                    $response = array(
                        'status' => 'error',
                        'message' => lang('AssetGroup.error_list')
                    );
                    return $this->setResponseFormat("json")->respond($response, 201);
                }
            }
            $response = array(
                'status' => 'success',
                'message' => lang('AssetGroup.success_list'),
                'groups' => $details,
                'pagination' => array(
                    'total' => (int)$total_groups,
                    'length' => $limit,
                    'start' => $start,
                    'records' => count($groups)
                )
            );
            return $this->setResponseFormat("json")->respond($response, 200);
        } else {
            $response = array(
                'status' => 'error',
                'message' => lang('AssetGroup.error_list')
            );
            return $this->setResponseFormat("json")->respond($response, 201);
        }
    }

    public function getGroup()
    {
        $response = array();
        $this->validatePermission('view_group');    // Check permission

        $modelAssetGroup = new AssetGroupModel(); // Load model

        $group_id = $this->request->getVar('group_id');
        $group = $modelAssetGroup->getGroup($group_id);
        if ($group) {
            $response['status'] = 'success';
            $response['message'] = lang('AssetGroup.success_detail');
            $response['group'] = $group;
            return $this->setResponseFormat("json")->respond($response, 200);
        } else {
            $response['status'] = 'error';
            $response['message'] = lang('AssetGroup.error_detail');
            return $this->setResponseFormat("json")->respond($response, 201);
        }
    }

    public function addGroup()
    {
        $response = array();
        $this->validatePermission('add_group');    // Check permission

        $rules = [
            "group_name" => "required",
        ];

        $messages = [
            "group_name" => [
                "required" => "group name is required"
            ],
        ];
        if ($this->validate($rules, $messages)) {
            $modelAssetGroup = new AssetGroupModel(); // Load model
            $group_data = array(
                'group_name' => $this->request->getPost('group_name'),
                'parent' => $this->request->getPost('parent'),
                // 'code' => $this->request->getPost('group_code'),
                // 'is_exist' => 0,
                'status' => $this->request->getPost('status')
            );
            $filter_data = array('removed' => 0);
            $group_name = $modelAssetGroup->getGroupByName($group_data['group_name'], $filter_data);
            if ($group_name) {
                $response['status'] = 'error';
                $response['message'] = lang('Name already exist');
                return $this->setResponseFormat("json")->respond($response, 201);
            } else {
                $add = $modelAssetGroup->addGroup($group_data);
                if ($add) {
                    $response['status'] = 'success';
                    $response['message'] = lang('AssetGroup.success_add');
                    return $this->setResponseFormat("json")->respond($response, 200);
                } else {
                    $response['status'] = 'error';
                    $response['message'] = lang('AssetGroup.error_add');
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

    public function editGroup()
    {
        $response = array();

        $this->validatePermission('edit_group');    // Check permission

        $rules = [
            "group_name" => "required",
        ];

        $messages = [
            "group_name" => [
                "required" => "group name is required"
            ],
        ];
        if ($this->validate($rules, $messages)) {
            $modelAssetGroup = new AssetGroupModel(); // Load model

            $group_id = $this->request->getVar('group_id');

            $group = $modelAssetGroup->getGroup($group_id);
            if ($group) {
                $group_data = array(
                    'group_name' => $this->request->getPost('group_name'),
                    'parent' => $this->request->getPost('parent'),
                    // 'code' => $this->request->getPost('group_code'),
                    'status' => $this->request->getPost('status')
                );
                $filter_data = array('removed' => 0, 'except' => [$group_id]);
                $group_name = $modelAssetGroup->getGroupByName($group_data['group_name'], $filter_data);
                if ($group_name) {
                    $response['status'] = 'error';
                    $response['message'] = lang('Name already exist');
                    return $this->setResponseFormat("json")->respond($response, 201);
                } else {
                    $edit = $modelAssetGroup->editGroup($group_id, $group_data);
                    if ($edit) {
                        $response['status'] = 'success';
                        $response['message'] = lang('AssetGroup.success_edit');
                        return $this->setResponseFormat("json")->respond($response, 200);
                    } else {
                        $response['status'] = 'error';
                        $response['message'] = lang('AssetGroup.error_edit');
                        return $this->setResponseFormat("json")->respond($response, 201);
                    }
                }
            } else {
                $response['status'] = 'error';
                $response['message'] = lang('AssetGroup.error_detail');
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

    public function deleteGroup()
    {
        $response = array();

        $this->validatePermission('edit_group');    // Check permission
        $modelAssetGroup = new AssetGroupModel(); // Load model

        $group_id = $this->request->getVar('group_id');
        $filter_data = array(
            'removed' => 0,
            'parent' => $group_id
        );
        // print_r($group);
        $parents = $modelAssetGroup->getGroupsByParent($filter_data);
        if ($parents) {
            $response['status'] = 'error';
            $response['message'] = 'Asset group has sub-asset group';
            return $this->setResponseFormat("json")->respond($response, 201);
        } else {
            $group = $modelAssetGroup->getGroup($group_id);
            if ($group) {
                $remove = $modelAssetGroup->removeGroup($group_id);
                if ($remove) {
                    $response['status'] = 'success';
                    $response['message'] = lang('AssetGroup.success_removed');
                    return $this->setResponseFormat("json")->respond($response, 200);
                } else {
                    $response['status'] = 'error';
                    $response['message'] = lang('AssetGroup.error_removed');
                    return $this->setResponseFormat("json")->respond($response, 201);
                }
            } else {
                $response['status'] = 'error';
                $response['message'] = lang('AssetGroup.error_detail');
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
        $this->validatePermission('view_group');    // Check permission
        $modelAssetGroup = new AssetGroupModel(); // Load model

        $parent = (bool)$this->request->getGet('parent');

        $filter_data = array(
            'removed' => 0,
            'parent' => $parent,
            'status' => 1
        );
        $limit = $this->request->getGet('limit');
        if ($limit) {
            $filter_data['limit'] = (int)$limit;
        }
        $groupArray = array();
        $groups = $modelAssetGroup->getGroups($filter_data);
        if ($groups) {
            foreach ($groups as $group) {
                $groupArray[] = array(
                    'id' => (int)$group->asset_group_id,
                    'name' => html_entity_decode($group->name)
                );
            }
        }
        $response = array(
            'status' => 'success',
            'asset_groups' => $groupArray,
        );

        return $this->setResponseFormat("json")->respond($response, 200);
    }
}
