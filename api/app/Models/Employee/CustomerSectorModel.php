<?php 

namespace App\Models\Admin;

use Config\Services;
use Config\Database;

class CustomerSectorModel
{
    private $cdb;

	public function __construct()
    {        
        $this->cdb = Database::connect('default');
	}

    public function getTotalCustomerSectores($data = [])
    {
        $condition = array();  

        if (isset($data['status'])) {
            $condition['cs.status'] = (int)$data['status'];
        }

        if (isset($data['removed'])) {
            $condition['cs.removed'] = (int)$data['removed'];
        }

        if (isset($data['type_id'])) {
            $condition['cs.type_id'] = (int)$data['type_id'];
        }

        // if (isset($data['is_exist'])) {
        //     $condition['cs.is_exist'] = (int)$data['is_exist'];
        // }

        $builder = $this->cdb->table('customer_sector cs');
        $builder->join('customer_sector_type cst', 'cst.customer_sector_type_id = cs.type_id');
        $builder->select('COUNT(*) AS total');
        if ($condition) {
            $builder->where($condition);
        }
        
        // Search
        $searchData = $data['search'] ?? '';
        if ($searchData) {
            if ($data['search']) {
                $builder->groupStart()
                    ->like('cs.title', $searchData)
                    ->orLike('cst.name', $searchData)
                    ->groupEnd();
            }
        }

        if (isset($data['except'])) {
            $builder->whereNotIn('customer_sector_id', $data['except']);
        }


        $query = $builder->get();    

        if ($query->getNumRows() > 0  ) {
            return $query->getRow()->total;
        } else {
            return 0;
        }
    }

    public function getCustomerSectores($data = [])
    {
        $condition = array();  

        if (isset($data['status'])) {
            $condition['cs.status'] = (int)$data['status'];
        }

        if (isset($data['type_id'])) {
            $condition['cs.type_id'] = (int)$data['type_id'];
        }
        
        if (isset($data['removed'])) {
            $condition['cs.removed'] = (int)$data['removed'];
        }

        if (isset($data['is_exist'])) {
            $condition['cs.is_exist'] = (int)$data['is_exist'];
        }

        $builder = $this->cdb->table('customer_sector cs');
        $builder->join('customer_sector_type cst', 'cst.customer_sector_type_id = cs.type_id');
        $builder->select('cs.*, cst.name as type_name');
        if ($condition) {
            $builder->where($condition);
        }
        
        // Search
        $searchData = $data['search'] ?? '';
        if ($searchData) {
            if ($data['search']) {
                $builder->groupStart()
                    ->like('cs.title', $searchData)
                    ->orLike('cst.name', $searchData)
                    ->groupEnd();
            }
        }

        // Except CustomerSectores
        if (isset($data['except'])) {
            $builder->whereNotIn('customer_sector_id', $data['except']);
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
            $sort = 'cs.customer_sector_id';
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

    public function getCustomerSectoreTypes($data = [])
    {
        $condition = array();  

        if (isset($data['status'])) {
            $condition['status'] = (int)$data['status'];
        }

        $builder = $this->cdb->table('customer_sector_type');
        $builder->select('*');
        if ($condition) {
            $builder->where($condition);
        }
        
        // Search
        $searchData = $data['search'] ?? '';
        if ($searchData) {
            if ($data['search']) {
                $builder->groupStart()
                    ->like('cs.title', $searchData)
                    ->orLike('cst.name', $searchData)
                    ->groupEnd();
            }
        }

        if (isset($data['except'])) {
            $builder->whereNotIn('customer_sector_id', $data['except']);
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
            $sort = 'customer_sector_type_id';
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

    public function getCustomerSector($customer_sector_id, $data = [])
    {
        $condition = array(
            'cs.customer_sector_id' => (int)$customer_sector_id
        );
        
        if (isset($data['status'])) {
            $condition['cs.status'] = (int)$data['status'];
        }

        $builder = $this->cdb->table('customer_sector cs');
        $builder->join('customer_sector_type cst', 'cst.customer_sector_type_id = cs.type_id');
        $builder->select('cs.*, cst.name AS customer_sector_type_name');
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

    public function getCustomerSectorByName($customer_sector_name, $data = [])
    {
        $condition = array();  
        
        if (isset($data['type_id'])) {
            $condition['type_id'] = $data['type_id'];
        }

        if (isset($data['removed'])) {
            $condition['removed'] = $data['removed'];
        }

        $builder = $this->cdb->table('customer_sector');
        $builder->select('*');
        if ($condition) {
            $builder->where($condition);
        }
        
        // Check name
        $check_name = 'REPLACE(LOWER(title), " ", "_") = REPLACE(LOWER("'. $customer_sector_name .'")," ", "_")';
        $builder->where($check_name);

        // Except CustomerSectores
        if (isset($data['except'])) {
            $builder->whereNotIn('customer_sector_id', $data['except']);
        }

       
        $query = $builder->get();    

        if ($query->getNumRows() > 0  ) {
            return $query->getRow();
        } else {
            return 0;
        }
    }

    public function addCustomerSector($data)
    {
        $this->cdb->transStart();
        $insert_data = array(
            'type_id' => (int)$data['type_id'],
            'title' => $data['title'],    
            'status' => (int)$data['status'],
            'created_datetime' =>date('Y-m-d H:i:s')
        );
        $this->cdb->table('customer_sector')->insert($insert_data); 
        $insertId = $this->cdb->insertID();
        $this->cdb->transComplete();

        if ($this->cdb->transStatus() === false) {
            return false;
        } else {
            return $insertId;
        }
    } 

    public function editCustomerSector($customer_sector_id, $data)
    {
        $this->cdb->transStart();
        $condition = array(
            'customer_sector_id' => $customer_sector_id
        );
        $update_data = array(
            'type_id' => (int)$data['type_id'],
            'title' => $data['title'], 
            'status' => (int)$data['status'],
            'updated_datetime' => date('Y-m-d H:i:s')
        );
        $builder = $this->cdb->table('customer_sector');
        $builder->where($condition);
        $result = $builder->update($update_data); 

        $this->cdb->transComplete();

        if ($this->cdb->transStatus() === false) {
            return false;
        } else {
            return $result;
        }
    }

    public function removeCustomerSector($customer_sector_id)
    {
        $this->cdb->transStart();
        $condition = array(
            'customer_sector_id' => $customer_sector_id
        );
        $update_data = array( 
            'removed' => 1,   
            'status' => 0,
            'updated_datetime' => date('Y-m-d H:i:s')
        );
        $builder = $this->cdb->table('customer_sector');
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