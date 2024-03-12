<?php 

namespace App\Models\Admin;

use Config\Services;
use Config\Database;

class BranchModel
{
    private $cdb;

	public function __construct()
    {        
        $this->cdb = Database::connect('default');
	}

    public function getTotalBranches($data = [])
    {
        $condition = array();  

        if (isset($data['status'])) {
            $condition['b.status'] = (int)$data['status'];
        }

        if (isset($data['removed'])) {
            $condition['b.removed'] = (int)$data['removed'];
        }

        if (isset($data['is_exist'])) {
            $condition['b.is_exist'] = (int)$data['is_exist'];
            
        }

        $builder = $this->cdb->table('branch b');
        $builder->join('region r', 'r.region_id = b.region_id');
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
                    ->orLike('b.code', $searchData)
                    ->orLike('r.name', $searchData)
                    ->groupEnd();
            }
        }

        // Except Branches
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

    public function getBranches($data = [])
    {
        $condition = array();  

        if (isset($data['status'])) {
            $condition['b.status'] = (int)$data['status'];
        }

        if (isset($data['region_id'])) {
            $condition['b.region_id'] = (int)$data['region_id'];
        }

        if (isset($data['area_id'])) {
            $condition['b.area_id'] = (int)$data['area_id'];
        }

        
        if (isset($data['removed'])) {
            $condition['b.removed'] = (int)$data['removed'];
        }

        if (isset($data['is_exist'])) {
            $condition['b.is_exist'] = (int)$data['is_exist'];
            
        }

        $builder = $this->cdb->table('branch b');
        $builder->join('region r', 'r.region_id = b.region_id');
        $builder->select('b.*, r.name as region_name');
        if ($condition) {
            $builder->where($condition);
        }
        
        // Search
        $searchData = $data['search'] ?? '';
        if ($searchData) {
            if ($data['search']) {
                $builder->groupStart()
                    ->like('b.name', $searchData)
                    ->orLike('b.code', $searchData)
                    ->orLike('r.name', $searchData)
                    ->groupEnd();
            }
        }

        // Except Branches
        if (isset($data['except'])) {
            $builder->whereNotIn('region_id', $data['except']);
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
            $sort = 'b.branch_id';
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

    public function getBranch($branch_id, $data = [])
    {
        $condition = array(
            'b.branch_id' => (int)$branch_id
        );
        
        if (isset($data['status'])) {
            $condition['b.status'] = (int)$data['status'];
        }

        $builder = $this->cdb->table('branch b');
        $builder->join('region r', 'r.region_id = b.region_id');
        $builder->select('b.*, r.name AS region_name');
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

    public function getBranchByName($branch_name, $data = [])
    {
        $condition = array();  
        
        if (isset($data['region_id'])) {
            $condition['region_id'] = $data['region_id'];
        }

        if (isset($data['removed'])) {
            $condition['removed'] = $data['removed'];
        }

        if (isset($data['code'])) {
            $condition['code'] = $data['code'];
        }

        $builder = $this->cdb->table('branch');
        $builder->select('*');
        if ($condition) {
            $builder->where($condition);
        }
        
        // Check name
        $check_name = 'REPLACE(LOWER(name), " ", "_") = REPLACE(LOWER("'. $branch_name .'")," ", "_")';
        $builder->where($check_name);

        // Except Branches
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

    public function getBranchByCode($branch_code, $data = [])
    {
        $condition = array();  
        
        if (isset($data['region_id'])) {
            $condition['region_id'] = $data['region_id'];
        }

        if (isset($data['removed'])) {
            $condition['removed'] = $data['removed'];
        }

        $builder = $this->cdb->table('branch');
        $builder->select('*');
        if ($condition) {
            $builder->where($condition);
        }
        
        // Check name
        $check_code = 'REPLACE(LOWER(code), " ", "_") = REPLACE(LOWER("'. $branch_code .'")," ", "_")';
        $builder->where($check_code);

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

    public function addBranch($data)
    {
        $this->cdb->transStart();
        $insert_data = array(
            'region_id' => (int)$data['region_id'],
            //'area_id' => (int)$data['area_id'],
            'name' => $data['name'],
            'code' => $data['code'],    
            'status' => (int)$data['status'],
            'is_exist' => (int)$data['is_exist'] ?? 0,
            'created_datetime' =>date('Y-m-d H:i:s')
        );
        $this->cdb->table('branch')->insert($insert_data); 
        $insertId = $this->cdb->insertID();
        $this->cdb->transComplete();

         //upload validation
         if($data['name'] == ''){
            $this->removeBranch($insertId);
        }
        //end

        if ($this->cdb->transStatus() === false) {
            return false;
        } else {
            return $insertId;
        }
    } 

    public function editBranch($branch_id, $data)
    {
        $this->cdb->transStart();
        $condition = array(
            'branch_id' => $branch_id
        );
        $update_data = array(
            'region_id' => (int)$data['region_id'],
            'name' => $data['name'],
            'code' => $data['code'], 
            'status' => (int)$data['status'],
            'updated_datetime' => date('Y-m-d H:i:s')
        );
        $builder = $this->cdb->table('branch');
        $builder->where($condition);
        $result = $builder->update($update_data); 

        $this->cdb->transComplete();

        if ($this->cdb->transStatus() === false) {
            return false;
        } else {
            return $result;
        }
    }

    public function removeBranch($branch_id)
    {
        $this->cdb->transStart();
        $condition = array(
            'branch_id' => $branch_id
        );
        $update_data = array( 
            'removed' => 1,   
            'status' => 0,
            'updated_datetime' => date('Y-m-d H:i:s')
        );
        $builder = $this->cdb->table('branch');
        $builder->where($condition);
        $result = $builder->update($update_data); 
        $this->cdb->transComplete();

        if ($this->cdb->transStatus() === false) {
            return false;
        } else {
            return $result;
        }
    } 

    function getBranchByCodeExist($data = [])
    { 
        $condition = array();  

        $condition = array( 
            'removed' => 0,   
            'status' => 1,
            'is_exist' => $data['is_exist']
        );
        $builder = $this->cdb->table('branch');
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

    function getBranchByCodeExists($data = [])
    {
        $condition = array();  

        $condition = array( 
            'removed' => 0,   
            'status' => 1,
            'is_exist' => $data['is_exist'],
            'code' => $data['code'],
        );
        $builder = $this->cdb->table('branch');
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

    public function updateBranch($branch_id,$data = [])
    {
        $condition = array(
            'branch_id' => $branch_id,
            'code' => $data['code'],
        );
        $update_data = array(   
            'is_exist' => 2,//already existing branch in db
            'updated_datetime' => date('Y-m-d H:i:s')
        );
        $builder = $this->cdb->table('branch');
        $builder->where($condition);
        $result = $builder->update($update_data); 
        $this->cdb->transComplete();

        if ($this->cdb->transStatus() === false) {
            return false;
        } else {
            return $result;
        }
    } 

    public function updateBranchDetails($branch_code)
    {
        $condition = array(
            'code' => $branch_code,
            'is_exist' => 3,
            'status' => 0,
            'removed' => 0,
        );
        $update_data = array(   
            'is_exist' => 4,//newly existing branch in db
            'status' => 2,
        );
        $this->cdb->transStart();
        $builder = $this->cdb->table('branch');
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
    
        $builder = $this->cdb->table('branch');
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
        $builder = $this->cdb->table('branch');
        $builder->where($condition);
        $result = $builder->update($update_data); 
        $this->cdb->transComplete();

        if ($this->cdb->transStatus() === false) {
            return false;
        } else {
            return true;
        }
    } 

    public function updateUploadBranch()
    {
        $condition = array(   
            'is_exist' => 3,
            'status' => 0,
        );
        $update_data = array(   
            'is_exist' => 0,//already existing branch in db
            'status' => 1,
            'updated_datetime' => date('Y-m-d H:i:s')
        );
        $this->cdb->transStart();
        $builder = $this->cdb->table('branch');
        $builder->where($condition);
        $result = $builder->update($update_data); 
        $this->cdb->transComplete();

        //already existing branches in db
        $condition1 = array(   
            'is_exist' => 2,
            'status' => 1,
            'removed' => 0
        );
        $update_data1 = array(   
            'is_exist' => 0
        );

        $this->cdb->transStart();
        $builder = $this->cdb->table('branch');
        $builder->where($condition1);
        $builder->update($update_data1); 
        $this->cdb->transComplete();

        //delete duplicate entries
        $this->cdb->transStart();
        $builder = $this->cdb->table('branch');
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

    public function getBranchList($data = [])
    {
        $condition = array();  

        if (isset($data['removed'])) {
            $condition['b.removed'] = (int)$data['removed'];
        }

        $builder = $this->cdb->table('branch b');
        $builder->join('region r', 'r.region_id = b.region_id');
        $builder->select('b.*, r.name as region_name');
        $builder->whereNotIn('b.is_exist', array('0'));
        $builder->whereNotIn('b.status', array('1'));
        $builder->where($condition);

        $query = $builder->get();  
        
        if ($query->getNumRows() > 0  ) {
            return $query->getResult();
        } else {
            return 0;
        }
    }

    public function getEmptyBranchList($data = [])
    {
        $condition = array();  
        $builder = $this->cdb->table('branch b');
        $builder->join('region r', 'r.region_id = b.region_id');
        $builder->select('b.branch_id');
        $builder->where(array('b.status' => 0,'b.is_exist' => 3) );
        $builder->groupStart()
        ->where('b.name', '')
        ->orwhere('b.code', '')
        ->orwhere('b.name', '')
        ->groupEnd();
        $query = $builder->get();  
        if ($query->getNumRows() > 0  ) {
            return $query->getResult();
        } else {
            return 0;
        }
    }

    public function removeEmptyBranchList($branch_id)
    {
      
        $this->cdb->transStart();
        $builder = $this->cdb->table('branch');
        if (isset($branch_id)) {
            $builder->whereIn('branch_id', $branch_id);
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