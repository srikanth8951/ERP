<?php

namespace App\Models\Employee;

use CodeIgniter\Database\RawSql;
use Codeigniter\Database\BaseBuilder;

class ChecklistModel
{
	protected $db;
    protected $request;

	public function __construct()
	{
		$this->db = \Config\Database::connect();
        $this->request = \Config\Services::request();

        helper('text');
	}

    public function getTotalChecklists($data = [])
    {
        $condition = array();  

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
            'task.checklist_id' => (int)$checklist_id
        );  

        if(isset($data['division'])){
            $condition['task.division_id'] = (int)$data['division'];
        }

        if(isset($data['removed'])){
            $condition['task.removed'] = (int)$data['removed'];
        }

        $builder = $this->db->table('checklist_task task');
        $builder->select('task.*');

        if($condition){
            $builder->where($condition);
        }
        
        if(isset($data['except'])){
            $builder->where_not_in('task.checklist_task_id', $data['except']);
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
            $sort = 'task.checklist_task_id';
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

    // public function getTasksWithTrack($checklist_id, $checklist_track_id, $data = [])
    // {
    //     // Get latest task tracks
    //     $taskTracks = $this->getLatestChecklistTasksTrack($checklist_track_id);

    //     $condition = array(
    //         'task.checklist_id' => (int)$checklist_id,
    //         'track.checklist_track_id' =>(int)$checklist_track_id
    //     );  

    //     if(isset($data['division'])){
    //         $condition['task.division_id'] = (int)$data['division'];
    //     }

    //     if(isset($data['removed'])){
    //         $condition['task.removed'] = (int)$data['removed'];
    //     }

    //     $builder = $this->db->table('checklist_task task');
    //     $builder->join('contract_job_checklist_task_track track', 'track.task_id = task.checklist_task_id');
    //     $builder->select('task.checklist_task_id, task.checklist_id, task.division_id, task.name, task.type');
    //     $builder->select('track.task_value AS value, track.created_datetime, track.created_datetime AS updated_datetime');
    //     if($condition){
    //         $builder->where($condition);
    //     }

    //     // check & get latest task track
    //     $builder->whereIn('track.checklist_task_track_id', $taskTracks);

    //     if(isset($data['except'])){
    //         $builder->where_not_in('task.checklist_task_id', $data['except']);
    //     }

    //     //Limit
    //     if(isset($data['limit'])){
    //         $limit = 20;
    //         $start = 0;
    //         if(isset($data['start'])){
    //             $start = $data['start'];
    //         } 

    //         if($data['limit']){
    //           $limit = $data['limit'];
    //         }

    //         $builder->limit($limit, $start);
    //     }
              
    //     //Sort
    //     if(isset($data['sort'])){
    //         $sort = $data['sort'];
    //     } else {
    //         $sort = 'task.checklist_task_id';
    //     }

    //     if(isset($data['order'])){
    //         $order = $data['order'];
    //     } else {
    //         $order = 'DESC';
    //     }
    //     $builder->orderBy($sort, $order);

    //     $query = $builder->get();    
        
    //     if ($query->getNumRows() > 0  ) {
    //         return $query->getResult();
    //     } else {
    //         return 0;
    //     }
    // }
    
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

    public function getLatestChecklistTasksTrack($checklist_track_id)
    {
        $query = $this->db->table('contract_job_checklist_task_track track')
            ->select('MAX(track.checklist_task_track_id) AS track_id')
            ->where(['track.checklist_track_id' =>(int)$checklist_track_id ])
            ->groupBy('track.task_id')->get();
        if ($query->getNumRows() > 0  ) {
            $result = [];
            $rows = $query->getResult();
            foreach($rows as $row) {
                $result[] = $row->track_id;
            }
            return $result;
        } else {
            return [];
        }
    }

    public function getLatestChecklistTaskTrack($checklist_track_id, $task_id)
    {
        $query = $this->db->table('contract_job_checklist_task_track track')
            ->select('MAX(track.checklist_task_track_id) AS track_id')
            ->where([
                'track.checklist_track_id' => (int)$checklist_track_id,
                'track.task_id' => (int)$task_id    
            ])
            ->groupBy('track.task_id')->get();
        if ($query->getNumRows() > 0  ) {
            $row = $query->getRow();
            return $row->track_id;
        } else {
            return 0;
        }
    }

    public function getChecklistTaskTrack($checklist_track_id, $task_id)
    {
        $this->db->transStart();
        $trackId = $this->getLatestChecklistTaskTrack($checklist_track_id, $task_id);
        $query = $this->db->table('contract_job_checklist_task_track track')
            ->select('track.*')
            ->where([
                'track.checklist_task_track_id' => (int)$trackId
            ])->get();
        $this->db->transComplete();

        if ($this->db->transStatus() == false) {
            return [];
        } else {
            $result = $query->getRow();
            return $result;
        }
    }
 
}