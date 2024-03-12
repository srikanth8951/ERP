<?php

namespace App\Models\Admin;

use Config\Services;
use Config\Database;

class AssetModel
{
    private $cdb;

    public function __construct()
    {
        $this->cdb = Database::connect('default');
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

        $builder = $this->cdb->table('asset a');
        $builder->join('asset_group ag', 'ag.asset_group_id = a.group_id', 'left');
        $builder->join('asset_group ag1', 'ag1.asset_group_id = a.sub_group_id', 'left');
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

    public function getAssets($data = [])
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

        $builder = $this->cdb->table('asset a');
        $builder->join('asset_group ag', 'ag.asset_group_id = a.group_id', 'left');
        $builder->join('asset_group ag1', 'ag1.asset_group_id = a.sub_group_id', 'left');
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

    function getAssetExist($data = [])
    {
        $condition = array();

        $condition = array(
            'status' => 1,
            'is_exist' => $data['is_exist'],
            'name' => $data['name'],
            'sub_group_id'=> $data['sub_id'],
            'group_id'=> $data['group_id']
        );
        $builder = $this->cdb->table('asset');
        $builder->select('*');
        $builder->groupStart()
            ->like('name', $data['name'])
            ->groupEnd();
        $builder->where($condition);

        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            $val = $query->getRow();
            return $val;
        } else {
            return 0;
        }
    }

    function getAssetExists($data = [])
    {
        $condition = array();

        $condition = array(
            'removed' => 0,
            'status' => 1,
            'is_exist' => $data['is_exist'],
            'name' => $data['name'],
        );
        $builder = $this->cdb->table('asset');
        $builder->select('*');
        $builder->groupStart()
            ->like('name', $data['name'])
            ->groupEnd();
        $builder->where($condition);

        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            $val = $query->getRow();
            return $val;
        } else {
            return 0;
        }
    }

    public function updateAsset($id, $data = [])
    {
        $condition = array(
            'asset_id' => $id,
           // 'name' => $data['name'],
        );
        $update_data = array(
            'is_exist' => 2,
            'updated_datetime' => date('Y-m-d H:i:s')
        );
        $builder = $this->cdb->table('asset');
        $builder->where($condition);
        $result = $builder->update($update_data);
        $this->cdb->transComplete();

        if ($this->cdb->transStatus() === false) {
            return false;
        } else {
            return $result;
        }
    }

    public function updateAssetDetails($val)
    {
        $condition = array(
            'name' => $val,
            'is_exist' => 3,
            'status' => 0,
        );
        $update_data = array(
            'is_exist' => 4, //newly existing Employee in db
            'status' => 2,
        );
        $this->cdb->transStart();
        $builder = $this->cdb->table('asset');
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

        $builder = $this->cdb->table('asset');
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
        $builder = $this->cdb->table('asset');
        $builder->where($condition);
        $result = $builder->update($update_data);
        $this->cdb->transComplete();

        if ($this->cdb->transStatus() === false) {
            return false;
        } else {
            return true;
        }
    }

    public function updateUploadAsset()
    {
        $condition = array(
            'is_exist' => 3  );

        $update_data = array(
            'is_exist' => 0, //already existing Employee in db
            'status' => 1,
            'updated_datetime' => date('Y-m-d H:i:s')
        );

        $this->cdb->transStart();
        $builder = $this->cdb->table('asset');
        $builder->where($condition);
        $result = $builder->update($update_data);
        $this->cdb->transComplete();

        //already existing employee in db
        $condition1 = array(
            'is_exist' => 2,
            'status' => 1
        );
        $update_data1 = array(
            'is_exist' => 0
        );

        $this->cdb->transStart();
        $builder = $this->cdb->table('asset');
        $builder->where($condition1);
        $builder->update($update_data1);
        $this->cdb->transComplete();

        //delete duplicate entries
        $this->cdb->transStart();
        $builder = $this->cdb->table('asset');
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

    public function addAsset($asset)
    {
        $this->cdb->transStart();
        $insert_data = array(
            'name' => $asset['name'],
            'group_id' => $asset['group'],
            'sub_group_id' => $asset['sub_group'],
            'compressor_type' => $asset['compressor_type'] ?? '',
            'make' => $asset['make'],
            'model' => $asset['model'],
            'serial_number' => $asset['serial_number'],
            'capacity' => $asset['capacity'],
            'measurement_unit' => $asset['measurement_unit'],
            'quantity' => $asset['quantity'],
            'location' => $asset['location'],
            'status' => $asset['status'],
            'is_exist' => $asset['is_exist'],
            'created_datetime' => date('Y-m-d H:i:s')
        );
        $this->cdb->table('asset')->insert($insert_data);
        $id = $this->cdb->insertID();

        $this->cdb->transComplete();
        if ($this->cdb->transStatus() === false) {
            return false;
        } else {
            return $id;
        }
    }

    public function getAssetData($data = [])
    {
        $condition = array();
        
        if (isset($data['status'])) {
           // $condition['a.status'] = 0;
        }

        $builder = $this->cdb->table('asset a');
        $builder->join('asset_group ag', 'ag.asset_group_id = a.group_id', 'left');
        $builder->join('asset_group ag1', 'ag1.asset_group_id = a.sub_group_id', 'left');
        $builder->whereNotIn('a.is_exist', array('0'));
        $builder->whereNotIn('a.status', array('1'));
        $builder->select('a.*, ag.name as group_name, ag1.name as sub_group_name');
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
}