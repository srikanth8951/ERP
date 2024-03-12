<?php 

namespace App\Models\Admin;

use Config\Services;
use Config\Database;

class DepartmentModel
{
    private $cdb;

	public function __construct()
    {        
        $this->cdb = Database::connect('default');
	}

    public function getTotalDepartments($data = [])
    {
        $condition = array();  

        if (isset($data['status'])) {
            $condition['status'] = (int)$data['status'];
        }

        if (isset($data['removed'])) {
            $condition['removed'] = (int)$data['removed'];
        }

        if (isset($data['is_exist'])) {
            $condition['is_exist'] = (int)$data['is_exist'];
        }

        $builder = $this->cdb->table('department');
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
            $builder->whereNotIn('department_id', $data['except']);
        }

        $query = $builder->get();    

        if ($query->getNumRows() > 0  ) {
            return $query->getRow()->total;
        } else {
            return 0;
        }
    }

    public function getDepartments($data = [])
    {
        $condition = array();  

        if (isset($data['status'])) {
            $condition['status'] = (int)$data['status'];
        }

        if (isset($data['removed'])) {
            $condition['removed'] = (int)$data['removed'];
        }

        if (isset($data['is_exist'])) {
            $condition['is_exist'] = (int)$data['is_exist'];
        }

        $builder = $this->cdb->table('department');
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
            $builder->whereNotIn('department_id', $data['except']);
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
            $sort = 'department_id';
        }

        if (isset($data['order'])) {
            $order = $data['order'];
        } else {
            $order = 'DESC';
        }
        $builder->orderBy($sort, $order);

        $query = $builder->get();    

        if ($query->getNumRows() > 0  ) {
            return $query->getResult();
        } else {
            return 0;
        }
    }

    public function getDepartment($department_id, $data = [])
    {
        $condition = array(
            'department_id' => (int)$department_id
        );  

        if (isset($data['status'])) {
            $condition['status'] = (int)$data['status'];
        }

        $builder = $this->cdb->table('department');
        $builder->select('*');
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

    public function getDepartmentByName($department_name, $data = [])
    {
        $condition = array();  

        if (isset($data['removed'])) {
            $condition['removed'] = $data['removed'];
        }

        $builder = $this->cdb->table('department');
        $builder->select('*');
        if ($condition) {
            $builder->where($condition);
        }
        
        // Check name
        $check_name = 'REPLACE(LOWER(name), " ", "_") = REPLACE(LOWER("'. $department_name .'")," ", "_")';
        $builder->where($check_name);

        if (isset($data['except'])) {
            $builder->whereNotIn('department_id', $data['except']);
        }
        
        $query = $builder->get();  

        if ($query->getNumRows() > 0  ) {
            return $query->getRow();
        } else {
            return 0;
        }
    }

    public function addDepartment($data)
    {
        $this->cdb->transStart();
        $insert_data = array(
            'name' => $data['name'],    
            'status' => (int)$data['status'],
            'is_exist' => (int)$data['is_exist'] ?? 0,   
            'created_datetime' =>date('Y-m-d H:i:s')
        );
        $this->cdb->table('department')->insert($insert_data); 
        $insertId = $this->cdb->insertID();
        $this->cdb->transComplete();

        //upload validation
        if($data['name'] == ''){
            $this->removeDepartment($insertId);
        }
        //end

        //upload validation
        if($data['name'] == ''){
            $this->removeDepartment($insertId);
        }
        //end

        if ($this->cdb->transStatus() === false) {
            return false;
        } else {
            return $insertId;
        }
    } 

    public function editDepartment($department_id, $data)
    {
        $this->cdb->transStart();
        $condition = array(
            'department_id' => $department_id
        );
        $update_data = array(
            'name' => $data['name'],    
            'status' => (int)$data['status'],
            'updated_datetime' => date('Y-m-d H:i:s')
        );
        $builder = $this->cdb->table('department');
        $builder->where($condition);
        $result = $builder->update($update_data); 

        $this->cdb->transComplete();

        if ($this->cdb->transStatus() === false) {
            return false;
        } else {
            return $result;
        }
    }

    public function removeDepartment($department_id)
    {
        $this->cdb->transStart();
        $condition = array(
            'department_id' => $department_id
        );
        $update_data = array( 
            'removed' => 1,   
            'status' => 0,
            'updated_datetime' => date('Y-m-d H:i:s')
        );
        $builder = $this->cdb->table('department');
        $builder->where($condition);
        $result = $builder->update($update_data); 
        $this->cdb->transComplete();

        if ($this->cdb->transStatus() === false) {
            return false;
        } else {
            return $result;
        }
    } 

    function getDepartmentExist($data = [])
    { 
        $condition = array();  

        $condition = array( 
            'removed' => 0,   
            'status' => 1,
            'is_exist' => $data['is_exist']
        );
        $builder = $this->cdb->table('department');
        $builder->select('*');
        $builder->groupStart()
        ->like('name', $data['name'])
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

    function getDepartmentExists($data = [])
    {
        $condition = array();  

        $condition = array( 
            'removed' => 0,   
            'status' => 1,
            'is_exist' => $data['is_exist'],
            'name' => $data['name'],
        );
        $builder = $this->cdb->table('department');
        $builder->select('*');
        $builder->groupStart()
        ->like('name', $data['name'])
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

    public function updateDepartment($department_id,$data = [])
    {
        $condition = array(
            'department_id' => $department_id,
            'name' => $data['name'],
        );
        $update_data = array(   
            'is_exist' => 2,//already existing department in db
            'updated_datetime' => date('Y-m-d H:i:s')
        );
        $builder = $this->cdb->table('department');
        $builder->where($condition);
        $result = $builder->update($update_data); 
        $this->cdb->transComplete();

        if ($this->cdb->transStatus() === false) {
            return false;
        } else {
            return $result;
        }
    } 

    public function updateDepartmentDetails($department_name)
    {
        $condition = array(
            'name' => $department_name,
            'is_exist' => 3,
            'status' => 0,
            'removed' => 0,
        );
        $update_data = array(   
            'is_exist' => 4,//newly existing department in db
            'status' => 2,
        );
        $this->cdb->transStart();
        $builder = $this->cdb->table('department');
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
    
        $builder = $this->cdb->table('department');
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
        $builder = $this->cdb->table('department');
        $builder->where($condition);
        $result = $builder->update($update_data); 
        $this->cdb->transComplete();

        if ($this->cdb->transStatus() === false) {
            return false;
        } else {
            return true;
        }
    } 

    public function updateUploadDepartment()
    {
        $condition = array(   
            'is_exist' => 3,
            'status' => 0,
        );
        $update_data = array(   
            'is_exist' => 0,//already existing department in db
            'status' => 1,
            'updated_datetime' => date('Y-m-d H:i:s')
        );
        $this->cdb->transStart();
        $builder = $this->cdb->table('department');
        $builder->where($condition);
        $result = $builder->update($update_data); 
        $this->cdb->transComplete();

        //already existing departments in db
        $condition1 = array(   
            'is_exist' => 2,
            'status' => 1,
            'removed' => 0
        );
        $update_data1 = array(   
            'is_exist' => 0
        );

        $this->cdb->transStart();
        $builder = $this->cdb->table('department');
        $builder->where($condition1);
        $builder->update($update_data1); 
        $this->cdb->transComplete();

        //delete duplicate entries
        $this->cdb->transStart();
        $builder = $this->cdb->table('department');
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

    public function getDepartmentList($data = [])
    {
        $condition = array();  

        if (isset($data['removed'])) {
            $condition['removed'] = (int)$data['removed'];
        }

        $builder = $this->cdb->table('department');
        $builder->select('*');
        $builder->whereNotIn('is_exist', array('0'));
        $builder->whereNotIn('status', array('1'));
        $builder->where($condition);

        $query = $builder->get();  
        
        if ($query->getNumRows() > 0  ) {
            return $query->getResult();
        } else {
            return 0;
        }
    }

}