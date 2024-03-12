<?php

namespace App\Models\Customer;

use Config\Services;
use Config\Database;

class ContractJobAssetModel
{
    private $cdb;
    private $dbPrefix;

    public function __construct()
    {
        $this->cdb = Database::connect('default');
        $this->dbPrefix = $this->cdb->getPrefix();
        helper('contract_job');
    }

    public function getTotalAssets($data = [])
    {

        $condition = array();
       
        if (isset($data['status'])) {
            $condition['a.status'] = (int)$data['status'];
        }

        if (isset($data['removed'])) {
            $condition['a.removed'] = (int)$data['removed'];
        }

        if (isset($data['is_exist'])) {
            $condition['a.is_exist'] = (int)$data['is_exist'];
        }

        if (isset($data['region_id'])) {
            $condition['emp1.region_id'] = (int)$data['region_id'];
        }

        if (isset($data['branch_id'])) {
            $condition['emp1.branch_id'] = (int)$data['branch_id'];
        }

        if (isset($data['area_id'])) {
            $condition['emp1.area_id'] = (int)$data['area_id'];
        }

        if (isset($data['cam_id'])) {
            $condition['emp2.employee_id'] = (int)$data['cam_id'];
        }

        $builder = $this->cdb->table('asset a');
        $builder->join('asset_group ag', 'ag.asset_group_id = a.group_id', 'left');
        $builder->join('asset_group ag1', 'ag1.asset_group_id = a.sub_group_id', 'left');
        $builder->join('contract_job_asset cja', 'cja.asset_id = a.asset_id');
        $builder->join('contract_job cj', 'cja.contract_job_id = cj.contract_job_id');
        $builder->join('employee emp1', 'emp1.employee_id = cj.engineer_id');
        $builder->join('employee emp2', 'emp2.employee_id = cj.customer_account_manager_id');
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
                    ->orLike('a.location', $searchData)
                    ->orLike('ag.name', $searchData)
                    ->orLike('ag1.name', $searchData)
                    ->groupEnd();
            }
        }

        if (isset($data['except'])) {
            $builder->whereNotIn('a.asset_id', $data['except']);
        }

        $query = $builder->get();

        if ($query->getNumRows() > 0) {
            return $query->getRow()->total;
        } else {
            return 0;
        }
    }

    public function getAssetsList($data = [])
    {
        
        $condition = array();
        
        if (isset($data['status'])) {
            $condition['a.status'] = (int)$data['status'];
        }

        if (isset($data['removed'])) {
            $condition['a.removed'] = (int)$data['removed'];
        }

        if (isset($data['is_exist'])) {
            $condition['a.is_exist'] = (int)$data['is_exist'];
        }

        if (isset($data['region_id'])) {
            $condition['emp1.region_id'] = (int)$data['region_id'];
        }

        if (isset($data['branch_id'])) {
            $condition['emp1.branch_id'] = (int)$data['branch_id'];
        }

        if (isset($data['area_id'])) {
            $condition['emp1.area_id'] = (int)$data['area_id'];
        }

        if (isset($data['cam_id'])) {
            $condition['emp2.employee_id'] = (int)$data['cam_id'];
        }

        $builder = $this->cdb->table('asset a');
        $builder->join('asset_group ag', 'ag.asset_group_id = a.group_id', 'left');
        $builder->join('asset_group ag1', 'ag1.asset_group_id = a.sub_group_id', 'left');
        $builder->join('contract_job_asset cja', 'cja.asset_id = a.asset_id');
        $builder->join('contract_job cj', 'cja.contract_job_id = cj.contract_job_id');
        $builder->join('employee emp1', 'emp1.employee_id = cj.engineer_id');
        $builder->join('employee emp2', 'emp2.employee_id = cj.customer_account_manager_id');
        $builder->select('a.*, ag.name as group_name, ag1.name as sub_group_name');
        if ($condition) {
            $builder->where($condition);
        }

        // Search
        $searchData = $data['search'] ?? '';
        if ($searchData) {
            if ($data['search']) {
                $builder->groupStart()
                    ->like('a.name', $searchData)
                    ->orLike('a.location', $searchData)
                    ->orLike('ag.name', $searchData)
                    ->orLike('ag1.name', $searchData)
                    ->groupEnd();
            }
        }

        if (isset($data['except'])) {
            $builder->whereNotIn('a.asset_id', $data['except']);
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
            $sort = 'a.asset_id';
        }

        if (isset($data['order'])) {
            $order = $data['order'];
        } else {
            $order = 'DESC';
        }
        $builder->orderBy($sort, $order);

        $query = $builder->get();

        if ($query->getNumRows() > 0) {
            return $query->getResult();
        } else {
            return 0;
        }
    }

    // Assets
    public function getAssets($contract_job_id, $data = [])
    {
        $condition = array(
            'cj.contract_job_id' => $contract_job_id
        );

        if (isset($data['status'])) {
            $condition['cj.status'] = (int)$data['status'];
        }

        if (isset($data['asset_id'])) {
            $condition['cj.sector'] = (int)$data['asset_id'];
        }

        if (isset($data['removed'])) {
            $condition['cj.removed'] = (int)$data['removed'];
        }

        $builder = $this->cdb->table('contract_job_asset cja');
        $builder->join('contract_job cj', 'cj.contract_job_id = cja.contract_job_id');
        $builder->join('asset a', 'a.asset_id = cja.asset_id');
        $builder->join('asset_group ag1', 'ag1.asset_group_id = a.group_id', 'left');
        $builder->join('asset_group ag2', 'ag2.asset_group_id = a.sub_group_id', 'left');

        $builder->select('cj.contract_job_id, cja.contract_job_asset_id, a.*, a.name as asset_name, ag1.name as group_name, ag2.name as sub_group_name');
        if ($condition) {
            $builder->where($condition);
        }

        // Search
        $searchData = $data['search'] ?? '';
        if ($searchData) {
            if ($data['search']) {
                $builder->groupStart()
                    ->like('c.company_name', $searchData)
                    ->orLike('cj.job_title', $searchData)
                    ->orLike('cj.job_number', $searchData)
                    ->orLike('cj.sap_job_number', $searchData)
                    ->orLike('cn.name', $searchData)
                    ->orLike('ct.name', $searchData)
                    ->orLike('cs.name', $searchData)
                    ->groupEnd();
            }
        }

        if (isset($data['except'])) {
            $builder->whereNotIn('cj.contract_job_id', $data['except']);
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

        $sort = $data['sort'] ?? '';
        if (!$sort) {
            $sort = 'cj.contract_job_id';
        }

        $order = $data['order'] ?? '';
        if (!$order) {
            $order = 'DESC';
        }
        $builder->orderBy($sort, $order);

        $query = $builder->get();

        if ($query->getNumRows() > 0) {
            return $query->getResult();
        } else {
            return 0;
        }
    }

    public function getAsset($asset_id, $data = [])
    {
        $condition = array(
            'a.asset_id' => (int)$asset_id
        );

        if (isset($data['status'])) {
            $condition['a.status'] = (int)$data['status'];
        }

        if (isset($data['removed'])) {
            $condition['a.removed'] = (int)$data['removed'];
        }

        $builder = $this->cdb->table('asset a');
        $builder->join('asset_group ag', 'ag.asset_group_id = a.group_id', 'left');
        $builder->join('asset_group ag1', 'ag1.asset_group_id = a.sub_group_id', 'left');
        $builder->select('a.*, ag.name as group_name, ag1.name as sub_group_name');
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

    // Assets
    public function getAssetById($contract_job_asset_id, $data = [])
    {
        $condition = array(
            'cja.contract_job_asset_id' => $contract_job_asset_id
        );

        $builder = $this->cdb->table('contract_job_asset cja');
        $builder->join('contract_job cj', 'cj.contract_job_id = cja.contract_job_id');
        $builder->join('asset a', 'a.asset_id = cja.asset_id');
        $builder->join('asset_group ag1', 'ag1.asset_group_id = a.group_id', 'left');
        $builder->join('asset_group ag2', 'ag2.asset_group_id = a.sub_group_id', 'left');

        $builder->select('cj.contract_job_id, cja.contract_job_asset_id, a.*, a.name as asset_name, ag1.name as group_name, ag2.name as sub_group_name');

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

    public function getAssetJobs($asset_id, $data = [])
    {
        $condition = array(
            'a.asset_id' => (int)$asset_id
        );

        if (isset($data['job_status'])) {
            $condition['cj.status'] = (int)$data['job_status'];
        }

        if (isset($data['status'])) {
            $condition['a.status'] = (int)$data['status'];
        }

        if (isset($data['removed'])) {
            $condition['a.removed'] = (int)$data['removed'];
        }

        $builder = $this->cdb->table('asset a');
        $builder->join('contract_job_asset cja', 'cja.asset_id = a.asset_id');
        $builder->join('contract_job cj', 'cja.contract_job_id = cj.contract_job_id');
        $builder->select('cja.*, cj.job_title, cj.job_number');
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

    // Assets
    public function getAssetByAsset($asset_id, $data = [])
    {
        $condition = array(
            'asset_id' => $asset_id
        );

        $builder = $this->cdb->table('asset');

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

    // Checklists
    public function getAssetsChecklists($contract_job_id, $data = [])
    {
        $condition = array(
            'cj.contract_job_id' => $contract_job_id
        );

        if (isset($data['status'])) {
            $condition['ac.status'] = (int)$data['status'];
        }

        $builder = $this->cdb->table('contract_job_asset cja');
        $builder->join('contract_job cj', 'cj.contract_job_id = cja.contract_job_id');
        $builder->join('asset_checklist as ac', 'ac.asset_id = cja.asset_id');
        $builder->join('checklist c', 'c.checklist_id = ac.checklist_id');

        $builder->select('cja.contract_job_id, cja.contract_job_asset_id, ac.asset_checklist_id, c.*');
        if ($condition) {
            $builder->where($condition);
        }

        // Search
        $searchData = $data['search'] ?? '';
        if ($searchData) {
            if ($data['search']) {
                $builder->groupStart()
                    ->like('c.name', $searchData)
                    ->groupEnd();
            }
        }

        if (isset($data['except'])) {
            $builder->whereNotIn('c.checklist_id', $data['except']);
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

        $sort = $data['sort'] ?? '';
        if (!$sort) {
            $sort = 'ac.asset_checklist_id';
        }

        $order = $data['order'] ?? '';
        if (!$order) {
            $order = 'DESC';
        }
        $builder->orderBy($sort, $order);

        $query = $builder->get();

        if ($query->getNumRows() > 0) {
            return $query->getResult();
        } else {
            return 0;
        }
    }

    public function getAssetChecklists($contract_job_asset_id, $data = [])
    {
        $condition = array(
            'cj.status' => 1,
            'cjct.contract_job_asset_id' => (int)$contract_job_asset_id
        );

        if (isset($data['contract_job_ppm_id'])) {
            $condition['cjct.contract_job_ppm_id'] = (int)$data['contract_job_ppm_id'];
        }

        if (isset($data['asset_id'])) {
            $condition['ac.asset_id'] = (int)$data['asset_id'];
        }

        if (isset($data['checklist_id'])) {
            $condition['ac.checklist_id'] = (int)$data['checklist_id'];
        }

        if (isset($data['status'])) {
            $condition['ac.status'] = (int)$data['status'];
        }

        if (isset($data['track_status'])) {
            $condition['cjct.status'] = (int)$data['track_status'];
        }

        $builder = $this->cdb->table('contract_job_checklist_track cjct');
        $builder->join('contract_job cj', 'cj.contract_job_id = cjct.contract_job_id');
        $builder->join('contract_job_asset cja', 'cja.contract_job_asset_id = cjct.contract_job_asset_id');
        $builder->join('asset_checklist ac', 'ac.asset_checklist_id = cjct.asset_checklist_id');
        $builder->join('checklist c', 'c.checklist_id = ac.checklist_id');

        $builder->select('cjct.checklist_track_id AS track_id, cjct.status AS track_status, cjct.contract_job_id, cjct.contract_job_asset_id, cjct.contract_job_ppm_id, ac.asset_checklist_id, c.*');
        if ($condition) {
            $builder->where($condition);
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
        $sort = $data['sort'] ?? '';
        if (!$sort) {
            $sort = 'cjct.checklist_track_id';
        }

        $order = $data['order'] ?? '';
        if (!$order) {
            $order = 'DESC';
        }
        $builder->orderBy($sort, $order);

        $query = $builder->get();
        
        if ($query->getNumRows() > 0) {
            return $query->getResult();
        } else {
            return [];
        }
    }

    public function getAssetChecklistsByAsset($asset_id, $data = [])
    {
        $condition = array(
            'ac.asset_id' => $asset_id
        );

        if (isset($data['status'])) {
            $condition['ac.status'] = (int)$data['status'];
        }

        $builder = $this->cdb->table('asset_checklist as ac');
        $builder->join('checklist c', 'c.checklist_id = ac.checklist_id');

        $builder->select('c.*');
        if ($condition) {
            $builder->where($condition);
        }

        // Search
        $searchData = $data['search'] ?? '';
        if ($searchData) {
            if ($data['search']) {
                $builder->groupStart()
                    ->like('c.name', $searchData)
                    ->groupEnd();
            }
        }

        if (isset($data['except'])) {
            $builder->whereNotIn('c.checklist_id', $data['except']);
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

        $sort = $data['sort'] ?? '';
        if (!$sort) {
            $sort = 'ac.asset_checklist_id';
        }

        $order = $data['order'] ?? '';
        if (!$order) {
            $order = 'DESC';
        }
        $builder->orderBy($sort, $order);

        $query = $builder->get();

        if ($query->getNumRows() > 0) {
            return $query->getResult();
        } else {
            return 0;
        }
    }

    public function getAssetChecklist($checklist_track_id, $data = [])
    {
        $condition = array(
            'cj.status' => 1,
            'cjct.checklist_track_id' => $checklist_track_id
        );

        if (isset($data['asset_id'])) {
            $condition['ac.asset_id'] = (int)$data['asset_id'];
        }

        if (isset($data['checklist_id'])) {
            $condition['ac.checklist_id'] = (int)$data['checklist_id'];
        }

        if (isset($data['status'])) {
            $condition['ac.status'] = (int)$data['status'];
        }

        $builder = $this->cdb->table('contract_job_checklist_track as cjct');
        $builder->join('contract_job cj', 'cj.contract_job_id = cjct.contract_job_id');
        $builder->join('contract_job_asset cja', 'cja.contract_job_asset_id = cjct.contract_job_asset_id');
        $builder->join('asset_checklist as ac', 'ac.asset_checklist_id = cjct.asset_checklist_id');
        $builder->join('checklist c', 'c.checklist_id = ac.checklist_id');

        $builder->select('cjct.checklist_track_id AS track_id, cjct.status AS track_status, cjct.contract_job_id, cjct.contract_job_asset_id, cjct.contract_job_ppm_id, cjct.attachments, ac.asset_checklist_id, c.*');
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

    public function addChecklistTracks($datas)
    {
        $this->cdb->transStart();
        foreach ($datas as $data) {
            $this->cdb->table('contract_job_checklist_track')->insert([
                'contract_job_id' => (int)$data['contract_job_id'],
                'contract_job_asset_id' => (int)$data['contract_job_asset_id'],
                'contract_job_ppm_id' => (int)$data['contract_job_ppm_id'],
                'asset_checklist_id' => (int)$data['asset_checklist_id'],
                'status' => 1,
                'created_datetime' =>date('Y-m-d H:i:s')
            ]);
        }
        $this->cdb->transComplete();

        if ($this->cdb->transStatus() === false) {
            return false;
        } else {
            return true;
        }
    }

    public function setChecklistTrackStatus($checklist_track_id, $status)
    {
        $this->cdb->transStart();

        $status = $status ? (int)$status : 1;
        $update_data = array(
            'status' => $status,  
            'updated_datetime' => date('Y-m-d H:i:s')
        );
        $this->cdb->table('contract_job_checklist_track')
            ->where(['checklist_track_id' => (int)$checklist_track_id])
            ->update($update_data); 
        
        $this->cdb->transComplete();
    
        if ($this->cdb->transStatus() === false) {
            return false;
        } else {
            return true;
        }
    }

    public function setChecklistTrackAttachments($checklist_track_id, $attachments)
    {
        $this->cdb->transStart();
        $attachmentFiles = implode(',', $attachments);
        $update_data = array(
            'attachments' => $attachmentFiles,  
            'updated_datetime' => date('Y-m-d H:i:s')
        );
        $this->cdb->table('contract_job_checklist_track')
            ->where(['checklist_track_id' => (int)$checklist_track_id])
            ->update($update_data); 
        
        $this->cdb->transComplete();
    
        if ($this->cdb->transStatus() === false) {
            return false;
        } else {
            return true;
        }
    }
    
    public function addChecklistTasksTrack($checklist_track_id, $data)
    {
        $this->cdb->transStart();

        $tasks = $data['tasks'];
        foreach ($tasks as $task) {
            $task = (! is_array($task)) ? (array)$task : $task;
            $insert_data = array(
                'checklist_track_id' => (int)$checklist_track_id,
                'task_id' => (int)$task['id'],
                'task_value' => $task['value'],    
                'created_datetime' => date('Y-m-d H:i:s')
            );
            $this->cdb->table('contract_job_checklist_task_track')->insert($insert_data); 
        }
        
        $this->cdb->transComplete();
    
        if ($this->cdb->transStatus() === false) {
            return false;
        } else {
            return true;
        }
    }

}
