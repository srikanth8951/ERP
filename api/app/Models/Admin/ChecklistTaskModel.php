<?php

namespace App\Models\Admin;

class ChecklistTaskModel
{
	protected $db;
    protected $request;

	public function __construct()
	{
		$this->db = \Config\Database::connect();
        $this->request = \Config\Services::request();

        helper('text');
	}

    // division
    public function getTotalTaskDivisions($checklist_id, $data = [])
    {
        $condition = array(
            'checklist_id' => (int)$checklist_id
        );  

        if(isset($data['removed'])){
            $condition['removed'] = (int)$data['removed'];
        }

        $builder = $this->db->table('checklist_division');
        $builder->select('COUNT(*) AS total');
        if($condition){
            $builder->where($condition);
        }
        
        if(isset($data['except'])){
            $builder->where_not_in('checklist_division_id', $data['except']);
        }

        $query = $builder->get();    

        if ($query->getNumRows() > 0  ) {
            return $query->getRow()->total;
        } else {
            return 0;
        }
    }

    public function getTaskDivisions($checklist_id, $data = [])
    {
        $condition = array(
            'checklist_id' => (int)$checklist_id
        );  

        if(isset($data['removed'])){
            $condition['removed'] = (int)$data['removed'];
        }

        $builder = $this->db->table('checklist_division');
        $builder->select('*');
        if($condition){
            $builder->where($condition);
        }
        
        if(isset($data['except'])){
            $builder->where_not_in('checklist_division_id', $data['except']);
        }

        //Limit
        if(isset($data['limit'])){
            $limit = 20;
            $start = 0;
            if(isset($data['start'])){
                $start = $data['start'];
            } 

            if($data['limit']){
              $limit = $data['limit'];
            }

            $builder->limit($limit, $start);
        }
              
        //Sort
        if(isset($data['sort'])){
            $sort = $data['sort'];
        } else {
            $sort = 'checklist_division_id';
        }

        if(isset($data['order'])){
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

    public function getTaskDivision($division_id, $data = [])
    {
        $condition = array(
            'checklist_division_id' => (int)$division_id
        );  

        $builder = $this->db->table('checklist_division');
        $builder->select('*');
        if($condition){
            $builder->where($condition);
        }
        
        $query = $builder->get();    

        if ($query->getNumRows() > 0  ) {
            return $query->getRow();
        } else {
            return 0;
        }
    }
    
    public function addTaskDivision($data)
    {
        helper('user');
        $this->db->transStart();
        $insert_data = array(
            'checklist_id' => (int)$data['checklist_id'],
            'name' => $data['division_name'],   
            'created_datetime' => date('Y-m-d H:i:s')
        );
        $builder = $this->db->table('checklist_division');
        $builder->insert($insert_data); 
        $insertId = $this->db->insertID();

        $this->db->transComplete();

        if ($this->db->transStatus() === false) {
            return false;
        } else {
            return $insertId;
        }

        
    } 

    
    public function editTaskDivision($division_id, $data)
    {
        $this->db->transStart();
        $condition = array(
            'checklist_division_id' => $division_id
        );
        $update_data = array(
            'name' => $data['division_name'],  
            'updated_datetime' => date('Y-m-d H:i:s')
        );
        $builder = $this->db->table('checklist_division');
        $builder->where($condition);
        $result = $builder->update($update_data); 

        $this->db->transComplete();

        if ($this->db->transStatus() === false) {
            return false;
        } else {
            return $result;
        }
    }

    public function removeTaskDivision($division_id)
    {
        $division = $this->getTaskDivision($division_id);

        $this->db->transStart();
        $update_data = array( 
            'removed' => 1,  
            'updated_datetime' => date('Y-m-d H:i:s')
        );
        $builder = $this->db->table('checklist_division');
        $builder->where(['checklist_division_id' => $division_id]);
        $result = $builder->update($update_data); 

        // remove task
        $update_data1 = array( 
            'removed' => 1,  
            'updated_datetime' => date('Y-m-d H:i:s')
        );
        $builder2 = $this->db->table('checklist_task');
        $builder2->where(['checklist_id' => $division->checklist_id, 'division_id' => $division_id]);
        $result2 = $builder2->update($update_data1);

        $this->db->transComplete();

        if ($this->db->transStatus() === false) {
            return false;
        } else {
            return $result;
        }
    } 

    public function deleteTaskDivision($division_id)
    {
        $division = $this->getTaskDivision($division_id);

        $this->db->transStart();
        $condition = array(
            'checklist_division_id' => $division_id
        );
        $builder = $this->db->table('checklist_division');
        $builder->where($condition);
        $result = $builder->delete();
        
        // remove task
        $update_data1 = array( 
            'removed' => 1,  
            'updated_datetime' => date('Y-m-d H:i:s')
        );
        $builder2 = $this->db->table('checklist_task');
        $builder2->where(['checklist_id' => $division->checklist_id, 'division_id' => $division_id]);
        $result2 = $builder2->update($update_data1);

        $this->db->transComplete();

        if ($this->db->transStatus() === false) {
            return false;
        } else {
            return $result;
        }
    }

    // Task
	public function getTotalTasks($checklist_id, $data = [])
    {
        $condition = array(
            'checklist_id' => (int)$checklist_id
        );  

        if(isset($data['division'])){
            $condition['division_id'] = (int)$data['division'];
        }

        if(isset($data['removed'])){
            $condition['removed'] = (int)$data['removed'];
        }

        $builder = $this->db->table('checklist_task');
        $builder->select('COUNT(*) AS total');
        if($condition){
            $builder->where($condition);
        }
        
        if(isset($data['except'])){
            $builder->where_not_in('checklist_task_id', $data['except']);
        }

        $query = $builder->get();    

        if ($query->getNumRows() > 0  ) {
            return $query->getRow()->total;
        } else {
            return 0;
        }
    }

    public function getTasks($checklist_id, $data = [])
    {
        $condition = array(
            'checklist_id' => (int)$checklist_id
        );  

        if(isset($data['division'])){
            $condition['division_id'] = (int)$data['division'];
        }

        if(isset($data['removed'])){
            $condition['removed'] = (int)$data['removed'];
        }

        $builder = $this->db->table('checklist_task');
        $builder->select('*');
        if($condition){
            $builder->where($condition);
        }
        
        if(isset($data['except'])){
            $builder->where_not_in('checklist_task_id', $data['except']);
        }

        //Limit
        if(isset($data['limit'])){
            $limit = 20;
            $start = 0;
            if(isset($data['start'])){
                $start = $data['start'];
            } 

            if($data['limit']){
              $limit = $data['limit'];
            }

            $builder->limit($limit, $start);
        }
              
        //Sort
        if(isset($data['sort'])){
            $sort = $data['sort'];
        } else {
            $sort = 'checklist_task_id';
        }

        if(isset($data['order'])){
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

    public function getTask($task_id, $data = [])
    {
        $condition = array(
            'checklist_task_id' => (int)$task_id
        );  

        if(isset($data['division'])){
            $condition['division_id'] = (int)$data['division'];
        }

        $builder = $this->db->table('checklist_task');
        $builder->select('*');
        if($condition){
            $builder->where($condition);
        }
        
        $query = $builder->get();    

        if ($query->getNumRows() > 0  ) {
            return $query->getRow();
        } else {
            return 0;
        }
    }
    
    public function addTask($data)
    {
        helper('user');
        $this->db->transStart();
        $insert_data = array(
            'checklist_id' => (int)$data['checklist_id'],
            'name' => $data['task_name'],            
            'type' => (int)$data['task_type'],
            'created_datetime' => date('Y-m-d H:i:s')
        );
        if (isset($data['division'])) {
            $insert_data['division_id'] = (int)$data['division'];
        }

        $builder = $this->db->table('checklist_task');
        $builder->insert($insert_data); 
        $insertId = $this->db->insertID();

        $this->db->transComplete();

        if ($this->db->transStatus() === false) {
            return false;
        } else {
            return $insertId;
        }

        
    } 

    
    // public function editTask($task_id, $data)
    // {
    //     $this->db->transStart();
    //     $condition = array(
    //         'checklist_task_id' => $task_id
    //     );
    //     $update_data = array(
    //         'name' => $data['task_name'],            
    //         'type' => (int)$data['task_type'],
    //         'updated_datetime' => date('Y-m-d H:i:s')
    //     );
    //     $builder = $this->db->table('checklist_task');
    //     $builder->where($condition);
    //     $result = $builder->update($update_data); 

    //     $this->db->transComplete();

    //     if ($this->db->transStatus() === false) {
    //         return false;
    //     } else {
    //         return $result;
    //     }
    // }

    public function removeTask($task_id)
    {
        
        $condition = array(
            'checklist_task_id' => $task_id
        );
        $update_data = array( 
            'removed' => 1,  
            'updated_datetime' => date('Y-m-d H:i:s')
        );
        $builder = $this->db->table('checklist_task');
        $builder->where($condition);
        $result = $builder->update($update_data); 
        return $result;
    } 

    public function deleteTask($task_id)
    {
        
        $condition = array(
            'checklist_task_id' => $task_id
        );
        $builder = $this->db->table('checklist_task');
        $builder->where($condition);
        $result = $builder->delete(); 
        return $result;
    } 
}