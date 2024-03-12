<?php 

namespace App\Models\Admin;

use Config\Services;
use Config\Database;

class AreaModel
{
    private $cdb;

	public function __construct()
    {        
        $this->cdb = Database::connect('default');
	}

    public function getTotalAreas($data = [])
    {
        $condition = array();  

        if (isset($data['region_id'])) {
            $condition['a.region_id'] = (int)$data['region_id'];
        }

        if (isset($data['status'])) {
            $condition['a.status'] = (int)$data['status'];
        }

        if (isset($data['removed'])) {
            $condition['a.removed'] = (int)$data['removed'];
        }

        if (isset($data['is_exist'])) {
            $condition['a.is_exist'] = (int)$data['is_exist'];
        }

        $builder = $this->cdb->table('area a');
        $builder->join('branch b', 'b.branch_id = a.branch_id');
        $builder->join('region r', 'r.region_id = a.region_id');
        $builder->select('COUNT(*) AS total');
        if ($condition) {
            $builder->where($condition);
        }
        
        // Search
        $searchData = $data['search'] ?? '';
        if ($searchData) {
            if ($data['search']) {
                $builder->groupStart()
                    ->like('a.name', $searchData)
                    ->orLike('a.code', $searchData)
                    ->orLike('r.name', $searchData)
                    ->groupEnd();
            }
        }

        // Except Areas
        if (isset($data['except'])) {
            $builder->whereNotIn('region_id', $data['except']);
        }
       
        $query = $builder->get();    

        if ($query->getNumRows() > 0  ) {
            return $query->getRow()->total;
        } else {
            return 0;
        }
    }

    public function getAreas($data = [])
    {
        $condition = array();  

        if (isset($data['region_id'])) {
            $condition['a.region_id'] = (int)$data['region_id'];
        }

        if (isset($data['branch_id'])) {
            $condition['a.branch_id'] = (int)$data['branch_id'];
        }

        if (isset($data['status'])) {
            $condition['a.status'] = (int)$data['status'];
        }
        
        if (isset($data['removed'])) {
            $condition['a.removed'] = (int)$data['removed'];
        }

        if (isset($data['is_exist'])) {
            $condition['a.is_exist'] = (int)$data['is_exist'];
        }

        $builder = $this->cdb->table('area a');
        $builder->join('branch b', 'b.branch_id = a.branch_id');
        $builder->join('region r', 'r.region_id = a.region_id');
        $builder->select('a.*, r.name as region_name');
        $builder->select('a.*, b.name as branch_name');
        if ($condition) {
            $builder->where($condition);
        }
        
        // Search
        $searchData = $data['search'] ?? '';
        if ($searchData) {
            if ($data['search']) {
                $builder->groupStart()
                    ->like('a.name', $searchData)
                    ->orLike('a.code', $searchData)
                    ->orLike('r.name', $searchData)
                    ->orLike('b.name', $searchData)
                    ->groupEnd();
            }
        }

        // Except Areas
        if (isset($data['except'])) {
            $builder->whereNotIn('region_id', $data['except']);
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
            $sort = 'a.area_id';
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

    public function getArea($area_id, $data = [])
    {
        $condition = array(
            'a.area_id' => (int)$area_id
        );  

        if (isset($data['region_id'])) {
            $condition['a.region_id'] = (int)$data['region_id'];
        }
        
        if (isset($data['status'])) {
            $condition['a.status'] = (int)$data['status'];
        }

        $builder = $this->cdb->table('area a');
        $builder->join('region r', 'r.region_id = a.region_id');
        $builder->select('a.*, r.name AS region_name');
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

    public function getAreaByName($area_name, $data = [])
    {
        $condition = array();  
        
        if (isset($data['region_id'])) {
            $condition['region_id'] = $data['region_id'];
        }

        if (isset($data['branch_id'])) {
            $condition['branch_id'] = $data['branch_id'];
        }

        if (isset($data['removed'])) {
            $condition['removed'] = $data['removed'];
        }

        if (isset($data['code'])) {
            $condition['code'] = $data['code'];
        }

        $builder = $this->cdb->table('area');
        $builder->select('*');
        if ($condition) {
            $builder->where($condition);
        }
        
        // Check name
        $check_name = 'REPLACE(LOWER(name), " ", "_") = REPLACE(LOWER("'. $area_name .'")," ", "_")';
        $builder->where($check_name);

        // Except Areas
        if (isset($data['except'])) {
            $builder->whereNotIn('area_id', $data['except']);
        }

        $query = $builder->get();  
       
        if ($query->getNumRows() > 0  ) {
            return $query->getRow();
        } else {
            return 0;
        }
    }

    public function getAreaByCode($area_code, $data = [])
    {
        $condition = array();  
        
        if (isset($data['region_id'])) {
            $condition['region_id'] = $data['region_id'];
        }

        if (isset($data['removed'])) {
            $condition['removed'] = $data['removed'];
        }

        $builder = $this->cdb->table('area');
        $builder->select('*');
        if ($condition) {
            $builder->where($condition);
        }
        
        // Check code
        $check_code = 'REPLACE(LOWER(code), " ", "_") = REPLACE(LOWER("'. $area_code .'")," ", "_")';
        $builder->where($check_code);

        // Except Areas
        if (isset($data['except'])) {
            $builder->whereNotIn('region_id', $data['except']);
        }


        $query = $builder->get();    
        if ($query->getNumRows() > 0  ) {
            return $query->getRow();
        } else {
            return 0;
        }
    }

    public function addArea($data)
    {
        $this->cdb->transStart();
        $insert_data = array(
            'region_id' => (int)$data['region_id'],
            'branch_id' => (int)$data['branch_id'],
            'name' => $data['name'],
            'code' => $data['code'],    
            'status' => (int)$data['status'],
            'is_exist' => (int)$data['is_exist'] ?? 0,
            'created_datetime' =>date('Y-m-d H:i:s')
        );
        $this->cdb->table('area')->insert($insert_data); 
        $insertId = $this->cdb->insertID();
        
        $this->cdb->transComplete();
         //upload validation
         if($data['name'] == ''){
            $this->removeArea($insertId);
        }
        //end

        if ($this->cdb->transStatus() === false) {
            return false;
        } else {
            return $insertId;
        }
    } 

    public function editArea($area_id, $data)
    {
        $this->cdb->transStart();
        $condition = array(
            'area_id' => $area_id
        );
        $update_data = array(
            'region_id' => (int)$data['region_id'],
            'branch_id' => (int)$data['branch_id'],
            'name' => $data['name'],
            'code' => $data['code'], 
            'status' => (int)$data['status'],
            'updated_datetime' => date('Y-m-d H:i:s')
        );
        $builder = $this->cdb->table('area');
        $builder->where($condition);
        $result = $builder->update($update_data); 

        $this->cdb->transComplete();

        if ($this->cdb->transStatus() === false) {
            return false;
        } else {
            return $result;
        }
    }

    public function removeArea($area_id)
    {
        $this->cdb->transStart();
        $condition = array(
            'area_id' => $area_id
        );
        $update_data = array( 
            'removed' => 1,   
            'status' => 0,
            'updated_datetime' => date('Y-m-d H:i:s')
        );
        $builder = $this->cdb->table('area');
        $builder->where($condition);
        $result = $builder->update($update_data); 
        $this->cdb->transComplete();

        if ($this->cdb->transStatus() === false) {
            return false;
        } else {
            return $result;
        }
    } 

    function getAreaExist($data = [])
    { 
        $condition = array();  

        $condition = array( 
            'removed' => 0,   
            'status' => 1,
            'is_exist' => $data['is_exist']
        );
        $builder = $this->cdb->table('area');
        $builder->select('*');
        $builder->groupStart()
        ->like('code', $data['code'])
        ->groupEnd();       
        $builder->where($condition);

        $query = $builder->get();    
        if ($query->getNumRows() > 0  ) {
            $val = $query->getRow(); 
            return $val;
        } else {
            return 0;
        }
    }

    function getAreaExists($data = [])
    {
        $condition = array();  

        $condition = array( 
            'removed' => 0,   
            'status' => 1,
            'is_exist' => $data['is_exist'],
            'code' => $data['code'],
        );
        $builder = $this->cdb->table('area');
        $builder->select('*');
        $builder->groupStart()
        ->like('code', $data['code'])
        ->groupEnd();       
        $builder->where($condition);

        $query = $builder->get();    
        if ($query->getNumRows() > 0  ) {
            $val = $query->getRow();
            return $val;
        } else {
            return 0;
        }
    }

    public function updateArea($area_id,$data = [])
    {
        $condition = array(
            'area_id' => $area_id,
            'code' => $data['code'],
        );
        $update_data = array(   
            'is_exist' => 2,
            'updated_datetime' => date('Y-m-d H:i:s')
        );
        $builder = $this->cdb->table('area');
        $builder->where($condition);
        $result = $builder->update($update_data); 
        $this->cdb->transComplete();

        if ($this->cdb->transStatus() === false) {
            return false;
        } else {
            return $result;
        }
    } 

    public function updateAreaDetails($area_code)
    {
        $condition = array(
            'code' => $area_code,
            'is_exist' => 3,
            'status' => 0,
            'removed' => 0,
        );
        $update_data = array(   
            'is_exist' => 4,//newly existing area in db
            'status' => 2,
        );
        $this->cdb->transStart();
        $builder = $this->cdb->table('area');
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
    
        $builder = $this->cdb->table('area');
        $builder->whereIn('status', array('0','2'));
        $builder->whereIn('is_exist', array('3','4'));

        $result = $builder->delete(); 
        
        $condition = array(   
            'is_exist' => 2,
            'status' => 1,
        );
        
        $update_data = array(   
            'is_exist' =>0
        );
        $builder = $this->cdb->table('area');
        $builder->where($condition);
        $result = $builder->update($update_data); 
        $this->cdb->transComplete();

        if ($this->cdb->transStatus() === false) {
            return false;
        } else {
            return true;
        }
    } 

    public function updateUploadArea()
    {
        $condition = array(   
            'is_exist' => 3,
            'status' => 0,
        );

        $update_data = array(   
            'is_exist' => 0,//already existing area in db
            'status' => 1,
            'updated_datetime' => date('Y-m-d H:i:s')
        );

        $this->cdb->transStart();
        $builder = $this->cdb->table('area');
        $builder->where($condition);
        $result = $builder->update($update_data); 
        $this->cdb->transComplete();

        //already existing area in db
        $condition1 = array(   
            'is_exist' => 2,
            'status' => 1,
            'removed' => 0
        );
        $update_data1 = array(   
            'is_exist' => 0
        );

        $this->cdb->transStart();
        $builder = $this->cdb->table('area');
        $builder->where($condition1);
        $builder->update($update_data1); 
        $this->cdb->transComplete();

        //delete duplicate entries
        $this->cdb->transStart();
        $builder = $this->cdb->table('area');
        $builder->where('status',2);
        $builder->where('is_exist', 4);
        $result = $builder->delete(); 
        $this->cdb->transComplete();

        if ($this->cdb->transStatus() === false) {
            return false;
        } else {
            return true;
        }
    }
    
    public function getAreaList($data = [])
    {
        $condition = array();  

        if (isset($data['removed'])) {
            $condition['a.removed'] = (int)$data['removed'];
        }

        $builder = $this->cdb->table('area a');
        $builder->join('branch b', 'b.branch_id = a.branch_id');
        $builder->join('region r', 'r.region_id = a.region_id');
        $builder->select('a.*, r.name as region_name');
        $builder->select('a.*, b.name as branch_name');
        $builder->whereNotIn('a.is_exist', array('0'));
        $builder->whereNotIn('a.status', array('1'));
        $builder->where($condition);

        $query = $builder->get();  
        
        if ($query->getNumRows() > 0  ) {
            return $query->getResult();
        } else {
            return 0;
        }
    }

    public function getEmptyAreaList($data = [])
    {
        $condition = array();  
        $builder = $this->cdb->table('area a');
        $builder->join('branch b', 'b.branch_id = a.branch_id');
        $builder->join('region r', 'r.region_id = a.region_id');
        $builder->select('a.area_id');
        $builder->where(array('a.status' => 0,'a.is_exist' => 3) );
        $builder->groupStart()
        ->where('a.name', '')
        ->orwhere('a.code', '')
        ->orwhere('a.branch_id', '')
        ->orwhere('a.region_id', '')
        ->groupEnd();
        $query = $builder->get();  
        if ($query->getNumRows() > 0  ) {
            return $query->getResult();
        } else {
            return 0;
        }
    }

    public function removeEmptyAreaList($area_id)
    { 
        $this->cdb->transStart();
        $builder = $this->cdb->table('area');
        if (isset($area_id)) {
            $builder->where('area_id', $area_id);
        }
        $result = $builder->delete(); 
        $this->cdb->transComplete();

        if ($this->cdb->transStatus() === false) {
            return false;
        } else {
            return true;
        }

    }
}