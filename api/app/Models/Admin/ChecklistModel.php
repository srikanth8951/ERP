<?php 

namespace App\Models\Admin;

use Config\Services;
use Config\Database;

class ChecklistModel
{
    private $cdb;

	public function __construct()
    {        
        $this->cdb = Database::connect('default');
	}

    public function getTotalChecklists($data = [])
    {
        $condition = array();  

        if (isset($data['group'])) {
            $condition['group'] = $data['group'];
        }

        if (isset($data['status'])) {
            $condition['status'] = (int)$data['status'];
        }

        if (isset($data['removed'])) {
            $condition['removed'] = (int)$data['removed'];
        }

        $builder = $this->cdb->table('checklist');
        $builder->select('COUNT(*) AS total');
        if ($condition) {
            $builder->where($condition);
        }
        
        if (isset($data['except'])) {
            $builder->whereNotIn('checklist_id', $data['except']);
        }

        $query = $builder->get();    

        if ($query->getNumRows() > 0  ) {
            return $query->getRow()->total;
        } else {
            return 0;
        }
    }

    public function getChecklists($data = [])
    {
        $condition = array();  

        if (isset($data['group'])) {
            $condition['group'] = $data['group'];
        }

        if (isset($data['status'])) {
            $condition['status'] = (int)$data['status'];
        }

        if (isset($data['removed'])) {
            $condition['removed'] = (int)$data['removed'];
        }

        $builder = $this->cdb->table('checklist');
        $builder->select('*');
        if ($condition) {
            $builder->where($condition);
        }

        if (isset($data['checklist_id'])) {
            if (is_array($data['checklist_id'])) {
                $builder->whereIn('checklist_id', $data['checklist_id']);
            } else {
                $builder->where('checklist_id', (int)$data['checklist_id']);
            }
        }

        if (isset($data['except'])) {
            $builder->whereNotIn('checklist_id', $data['except']);
        }

        if (isset($data['search'])) {
            $builder->GroupStart()->Like('name', $data['search'])->GroupEnd();
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
        if (isset($data['sort'])) {
            $sort = $data['sort'];
        } else {
            $sort = 'checklist_id';
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

    public function getChecklist($checklist_id, $data = [])
    {
        $condition = array(
            'checklist_id' => (int)$checklist_id
        );  

        if (isset($data['status'])) {
            $condition['status'] = (int)$data['status'];
        }

        $builder = $this->cdb->table('checklist');
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

    public function getChecklistByName($checklist_name, $data = [])
    {
        $condition = array();  

        if (isset($data['removed'])) {
            $condition['removed'] = $data['removed'];
        }

        $builder = $this->cdb->table('checklist');
        $builder->select('*');
        if ($condition) {
            $builder->where($condition);
        }
        
        // Check name
        $check_name = 'REPLACE(LOWER(name), " ", "_") = REPLACE(LOWER("'. $checklist_name .'")," ", "_")';
        $builder->where($check_name);

        $query = $builder->get();    

        if ($query->getNumRows() > 0  ) {
            return $query->getRow();
        } else {
            return 0;
        }
    }

    public function addChecklist($data)
    {
        $this->cdb->transStart();
        $insert_data = array(
            'name' => esc($data['name']),
            'group' => esc($data['group']),  
            'description' => esc($data['description']),  
            'type' => (int)$data['type'],    
            'status' => (int)$data['status'],
            'created_datetime' =>date('Y-m-d H:i:s')
        );
        $this->cdb->table('checklist')->insert($insert_data); 
        $insertId = $this->cdb->insertID();
        $this->cdb->transComplete();

        if ($this->cdb->transStatus() === false) {
            return false;
        } else {
            return $insertId;
        }
    } 

    public function editChecklist($checklist_id, $data)
    {
        $this->cdb->transStart();
        $condition = array(
            'checklist_id' => $checklist_id
        );
        $update_data = array(
            'name' => esc($data['name']),  
            'group' => esc($data['group']), 
            'description' => esc($data['description']),  
            'type' => (int)$data['type'],      
            'status' => (int)$data['status'],
            'updated_datetime' => date('Y-m-d H:i:s')
        );
        $builder = $this->cdb->table('checklist');
        $builder->where($condition);
        $result = $builder->update($update_data); 

        $this->cdb->transComplete();

        if ($this->cdb->transStatus() === false) {
            return false;
        } else {
            return $result;
        }
    }

    public function removeChecklist($checklist_id)
    {
        $this->cdb->transStart();
        $condition = array(
            'checklist_id' => $checklist_id
        );
        $update_data = array( 
            'removed' => 1,   
            'status' => 0,
            'updated_datetime' => date('Y-m-d H:i:s')
        );
        $builder = $this->cdb->table('checklist');
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