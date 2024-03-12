<?php 

namespace App\Models\Admin;

use Config\Services;
use Config\Database;

class StoreAttributeModel
{
    private $cdb;

	public function __construct()
    {        
        $this->cdb = Database::connect('default');
	}

    public function getTotalAttributes($data = [])
    {
        $condition = array();  

        if (isset($data['status'])) {
            $condition['b.status'] = (int)$data['status'];
        }

        if (isset($data['removed'])) {
            $condition['b.removed'] = (int)$data['removed'];
        }

        $builder = $this->cdb->table('store_attribute b');
        $builder->join('store_attribute_group r', 'r.attribute_group_id = b.attribute_group_id');
        $builder->select('COUNT(*) AS total');
        if ($condition) {
            $builder->where($condition);
        }
        
        // Search
        $searchData = $data['search'] ?? '';
        if ($searchData) {
            if ($data['search']) {
                $builder->groupStart()
                    ->like('b.name', $searchData)
                    ->orLike('r.name', $searchData)
                    ->groupEnd();
            }
        }

        // Except Attributes
        if (isset($data['except'])) {
            $builder->whereNotIn('b.attribute_id', $data['except']);
        }

        

        $query = $builder->get();    

        if ($query->getNumRows() > 0  ) {
            return $query->getRow()->total;
        } else {
            return 0;
        }
    }

    public function getAttributes($data = [])
    {
        $condition = array();  

        if (isset($data['status'])) {
            $condition['b.status'] = (int)$data['status'];
        }

        if (isset($data['attribute_group_id'])) {
            $condition['b.attribute_group_id'] = (int)$data['attribute_group_id'];
        }
        
        if (isset($data['removed'])) {
            $condition['b.removed'] = (int)$data['removed'];
        }

        $builder = $this->cdb->table('store_attribute b');
        $builder->join('store_attribute_group r', 'r.attribute_group_id = b.attribute_group_id');
        $builder->select('b.*, r.name as attribute_group_name');
        if ($condition) {
            $builder->where($condition);
        }
        
        // Search
        $searchData = $data['search'] ?? '';
        if ($searchData) {
            if ($data['search']) {
                $builder->groupStart()
                    ->like('b.name', $searchData)
                    ->orLike('r.name', $searchData)
                    ->groupEnd();
            }
        }

        // Except Attributes
        if (isset($data['except'])) {
            $builder->whereNotIn('b.attribute_id', $data['except']);
        }
        
        if (isset($data['type'])) {
            $builder->whereIn('b.status', array('0','2'));
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
            $sort = 'b.attribute_id';
        }

        $sortOrderData = $data['order'] ?? '';
        if ($sortOrderData) {
            $order = $sortOrderData;
        } else {
            $order = 'desc';
        }
        $builder->orderBy($sort, $order);

        $query = $builder->get();    

        if ($query->getNumRows() > 0  ) {
            return $query->getResult();
        } else {
            return [];
        }
    }

    public function getAttribute($attribute_id, $data = [])
    {
        $condition = array(
            'b.attribute_id' => (int)$attribute_id
        );
        
        if (isset($data['status'])) {
            $condition['b.status'] = (int)$data['status'];
        }

        $builder = $this->cdb->table('store_attribute b');
        $builder->join('store_attribute_group r', 'r.attribute_group_id = b.attribute_group_id');
        $builder->select('b.*, r.name AS attribute_group_name');
        if ($condition) {
            $builder->where($condition);
        }
        
        $query = $builder->get();    

        if ($query->getNumRows() > 0  ) {
            return $query->getRow();
        } else {
            return 0;
        }
    }

    public function getAttributeByName($attribute_name, $data = [])
    {
        $condition = array();  
        
        if (isset($data['attribute_id'])) {
            $condition['attribute_id'] = $data['attribute_id'];
        }

        if (isset($data['removed'])) {
            $condition['removed'] = $data['removed'];
        }

        $builder = $this->cdb->table('store_attribute');
        $builder->select('*');
        if ($condition) {
            $builder->where($condition);
        }
        
        // Check name
        $check_name = 'REPLACE(LOWER(name), " ", "_") = REPLACE(LOWER("'. $attribute_name .'")," ", "_")';
        $builder->where($check_name);

        // Except Attributes
        if (isset($data['except'])) {
            $builder->whereNotIn('attribute_id', $data['except']);
        }


        $query = $builder->get();    

        if ($query->getNumRows() > 0  ) {
            return $query->getRow();
        } else {
            return 0;
        }
    }

    public function addAttribute($data)
    {
        $this->cdb->transStart();
        $insert_data = array(
            'attribute_group_id' => (int)$data['attribute_group_id'],
            'name' => $data['name'], 
            'status' => (int)$data['status'],
            'created_datetime' =>date('Y-m-d H:i:s')
        );
        $this->cdb->table('store_attribute')->insert($insert_data); 
        $insertId = $this->cdb->insertID();
        $this->cdb->transComplete();

        if ($this->cdb->transStatus() === false) {
            return false;
        } else {
            return $insertId;
        }
    } 

    public function editAttribute($attribute_id, $data)
    {
        $this->cdb->transStart();
        $condition = array(
            'attribute_id' => $attribute_id
        );
        $update_data = array(
            'attribute_group_id' => (int)$data['attribute_group_id'],
            'name' => $data['name'], 
            'status' => (int)$data['status'],
            'updated_datetime' => date('Y-m-d H:i:s')
        );
        $builder = $this->cdb->table('store_attribute');
        $builder->where($condition);
        $result = $builder->update($update_data); 

        $this->cdb->transComplete();

        if ($this->cdb->transStatus() === false) {
            return false;
        } else {
            return $result;
        }
    }

    public function removeAttribute($attribute_id)
    {
        $this->cdb->transStart();
        $condition = array(
            'attribute_id' => $attribute_id
        );
        $update_data = array( 
            'removed' => 1,   
            'status' => 0,
            'updated_datetime' => date('Y-m-d H:i:s')
        );
        $builder = $this->cdb->table('store_attribute');
        $builder->where($condition);
        $result = $builder->update($update_data); 
        $this->cdb->transComplete();

        if ($this->cdb->transStatus() === false) {
            return false;
        } else {
            return $result;
        }
    } 

}