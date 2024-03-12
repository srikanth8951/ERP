<?php

namespace App\Models\Admin;

use Config\Services;
use Config\Database;

class StoreCategoryModel
{
    private $cdb;

    public function __construct()
    {
        $this->cdb = Database::connect('default');
    }

    public function getTotalCategories($data = [])
    {
        $condition = array();

        $condition = array();
        if (isset($data['parent'])) {
            if ($data['parent'] == true) {
                $condition['parent > '] = 0;
            } else {
                $condition['parent'] = 0;
            }

            $parentId = $data['parent_id'] ?? 0;
            if ($parentId && $data['parent']) {
                $condition['parent'] = (int)$data['status'];
            }
        }


        if (isset($data['status'])) {
            $condition['status'] = (int)$data['status'];
        }

        if (isset($data['removed'])) {
            $condition['removed'] = (int)$data['removed'];
        }

        $builder = $this->cdb->table('store_category');
        $builder->select('COUNT(*) AS total');
        if ($condition) {
            $builder->where($condition);
        }

        // Search
        $searchData = $data['search'] ?? '';
        if ($searchData) {
            if ($data['search']) {
                $builder->groupStart()
                    ->like('name', $searchData)
                    ->groupEnd();
            }
        }

        if (isset($data['except'])) {
            $builder->whereNotIn('category_id', $data['except']);
        }

        $query = $builder->get();

        if ($query->getNumRows() > 0) {
            return $query->getRow()->total;
        } else {
            return 0;
        }
    }

    public function getCategories($data = [])
    {
        $condition = array();
        
        if (isset($data['parent'])) {
            if ($data['parent'] == true) {
                $condition['parent > '] = 0;
            } else {
                $condition['parent'] = 0;
            }
        }
        
        if(isset($data['category_id'])){
            $condition['category_id'] = $data['category_id'];
        }

        if (isset($data['status'])) {
            $condition['status'] = (int)$data['status'];
        }

        if (isset($data['removed'])) {
            $condition['removed'] = (int)$data['removed'];
        }
        // print_r($data);
        $builder = $this->cdb->table('store_category');
        $builder->select('*');
        if ($condition) {
            $builder->where($condition);
        }

        // Search
        $searchData = $data['search'] ?? '';
        if ($searchData) {
            if ($data['search']) {
                $builder->groupStart()
                    ->like('name', $searchData)
                    ->groupEnd();
            }
        }

        if (isset($data['except'])) {
            $builder->whereNotIn('category_id', $data['except']);
        }

        if (isset($data['type'])) {
            $builder->whereIn('status', array('0', '2'));
        }

        if (isset($data['is_exist'])) {
            $builder->where('is_exist != ', 0);
        }
        //Limit
        if (isset($data['limit'])) {
            $limit = 20;
            $start = 0;
            if (isset($data['start'])) {
                $start = $data['start'];
            }

            if ($data['limit']) {
                $limit = $data['limit'];
            }

            $builder->limit($limit, $start);
        }

        //Sort
        $sortData = $data['sort'] ?? '';
        if ($sortData) {
            $sort = $sortData;
        } else {
            $sort = 'category_id';
        }

        if (isset($data['order'])) {
            $order = $data['order'];
        } else {
            $order = 'DESC';
        }
        $builder->orderBy($sort, $order);

        $query = $builder->get();
        // print_r($this->cdb->getLastQuery());
        if ($query->getNumRows() > 0) {
            return $query->getResult();
        } else {
            return 0;
        }
    }

    public function getCategory($category_id, $data = [])
    {
        $condition = array(
            'category_id' => (int)$category_id
        );

        if (isset($data['status'])) {
            $condition['status'] = (int)$data['status'];
        }

        $builder = $this->cdb->table('store_category');
        $builder->select('*');
        if ($condition) {
            $builder->where($condition);
        }

        $query = $builder->get();

        if ($query->getNumRows() > 0) {
            return $query->getRow();
        } else {
            return 0;
        }
    }

    public function getCategoriesByParent($data = [])
    {
        $condition = array(
            'parent' => $data['parent'],
            'removed' => $data['removed']
        );
        // print_r($data);
        if (isset($data['removed'])) {
            $condition['removed'] = (int)$data['removed'];
        }

        $builder = $this->cdb->table('store_category');
        $builder->select('*');
        if ($condition) {
            $builder->where($condition);
        }

        $query = $builder->get();

        if ($query->getNumRows() > 0) {
            return $query->getResult();
        } else {
            return 0;
        }
    }

    public function getCategoryByName($store_category_name, $data = [])
    {
        $condition = array();

        if (isset($data['removed'])) {
            $condition['removed'] = $data['removed'];
        }

        if (isset($data['status'])) {
            $condition['status'] = $data['status'];
        }

        $builder = $this->cdb->table('store_category');
        $builder->select('*');
        if ($condition) {
            $builder->where($condition);
        }

        // Check name
        $check_name = 'REPLACE(LOWER(name), " ", "_") = REPLACE(LOWER("' . $store_category_name . '")," ", "_")';
        $builder->where($check_name);

        // Except Branches
        $exceptData = $data['except'] ?? [];
        if ($exceptData) {
            $builder->whereNotIn('category_id', $exceptData);
        }

        $query = $builder->get();

        if ($query->getNumRows() > 0) {
            return $query->getRow();
        } else {
            return 0;
        }
    }

    public function getCategoryByCode($group_code, $data = [])
    {
        $condition = array();

        if (isset($data['removed'])) {
            $condition['removed'] = $data['removed'];
        }

        if (isset($data['status'])) {
            $condition['status'] = $data['status'];
        }

        $builder = $this->cdb->table('store_category');
        $builder->select('*');
        if ($condition) {
            $builder->where($condition);
        }

        // Check code
        $check_code = 'REPLACE(LOWER(code), " ", "_") = REPLACE(LOWER("' . $group_code . '")," ", "_")';
        $builder->where($check_code);

        $query = $builder->get();

        if ($query->getNumRows() > 0) {
            return $query->getRow();
        } else {
            return 0;
        }
    }

    public function addCategory($data)
    {

        $insert_data = array(
            'name' => $data['store_category_name'],
            // 'is_exist' => (int)$data['is_exist'],
            'parent' => $data['parent'] ?? 0,
            'status' => (int)$data['status'],
            'created_datetime' => date('Y-m-d H:i:s')
        );
        $this->cdb->transStart();
        $this->cdb->table('store_category')->insert($insert_data);
        $insertId = $this->cdb->insertID();
        $this->cdb->transComplete();

        if ($this->cdb->transStatus() === false) {
            return false;
        } else {
            return $insertId;
        }
    }

    public function editCategory($category_id, $data)
    {

        $condition = array(
            'category_id' => $category_id
        );
        $update_data = array(
            'name' => esc($data['store_category_name']),
            'parent' => $data['parent'] ?? 0,
            'status' => (int)$data['status'],
            'updated_datetime' => date('Y-m-d H:i:s')
        );
        $this->cdb->transStart();
        $builder = $this->cdb->table('store_category');
        $builder->where($condition);
        $result = $builder->update($update_data);

        $this->cdb->transComplete();

        if ($this->cdb->transStatus() === false) {
            return false;
        } else {
            return $result;
        }
    }

    public function removeCategory($category_id)
    {

        $condition = array(
            'category_id' => $category_id
        );
        $update_data = array(
            'removed' => 1,
            'status' => 0,
            'updated_datetime' => date('Y-m-d H:i:s')
        );
        $this->cdb->transStart();
        $builder = $this->cdb->table('store_category');
        $builder->where($condition);
        $result = $builder->update($update_data);
        $this->cdb->transComplete();

        if ($this->cdb->transStatus() === false) {
            return false;
        } else {
            return $result;
        }
    }

    public function updateCategory($category_id, $data = [])
    {
        $condition = array(
            'category_id' => $category_id,
            'code' => $data['code'],
        );
        $update_data = array(
            'is_exist' => 2, //already existing category in db
            'updated_datetime' => date('Y-m-d H:i:s')
        );
        $builder = $this->cdb->table('store_category');
        $builder->where($condition);
        $result = $builder->update($update_data);
        $this->cdb->transComplete();

        if ($this->cdb->transStatus() === false) {
            return false;
        } else {
            return $result;
        }
    }

    public function updateCategoryDetails($group_code)
    {
        $condition = array(
            'code' => $group_code,
            'is_exist' => 3,
            'status' => 0,
            'removed' => 0,
        );
        $update_data = array(
            'is_exist' => 4, //newly existing category in db
            'status' => 2,
        );
        $this->cdb->transStart();
        $builder = $this->cdb->table('store_category');
        $builder->where($condition);
        $result = $builder->update($update_data);
        $this->cdb->transComplete();

        if ($this->cdb->transStatus() === false) {
            return false;
        } else {
            return $result;
        }
    }

    public function cancelUpload()
    {
        $this->cdb->transStart();

        $builder = $this->cdb->table('store_category');
        $builder->whereIn('status', array('0', '2'));
        $builder->whereIn('is_exist', array('3', '4'));

        $result = $builder->delete();

        $condition = array(
            'is_exist' => 2,
            'status' => 1,
        );

        $update_data = array(
            'is_exist' => 0
        );
        $builder = $this->cdb->table('store_category');
        $builder->where($condition);
        $result = $builder->update($update_data);
        $this->cdb->transComplete();

        if ($this->cdb->transStatus() === false) {
            return false;
        } else {
            return true;
        }
    }

    public function updateUploadCategory()
    {
        $condition = array(
            'is_exist' => 3,
            'status' => 0,
        );
        $update_data = array(
            'is_exist' => 0, //already existing category in db
            'status' => 1,
            'updated_datetime' => date('Y-m-d H:i:s')
        );
        $this->cdb->transStart();
        $builder = $this->cdb->table('store_category');
        $builder->where($condition);
        $result = $builder->update($update_data);

        $builder = $this->cdb->table('store_category');
        $builder->where('status', 2);
        $builder->where('is_exist', 4);
        $result = $builder->delete();
        $this->cdb->transComplete();

        if ($this->cdb->transStatus() === false) {
            return false;
        } else {
            return true;
        }
    }
}
