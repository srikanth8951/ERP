<?php

namespace App\Models\Employee;

use Config\Services;
use Config\Database;

class StoreRequestModel
{
    private $cdb;

    public function __construct()
    {
        $this->cdb = Database::connect('default');
    }

    public function getTotalRequests($data = [])
    {
        $condition = array();

        if (isset($data['status'])) {
            $condition['sr.status'] = $data['status'];
        }

        if (isset($data['removed'])) {
            $condition['sr.removed'] = $data['removed'];
        }

        if (isset($data['request_id'])) {
            $condition['sr.request_id'] = $data['request_id'];
        }

        if (isset($data['requested_by'])) {
            $condition['sr.requested_by'] = $data['requested_by'];
        }

        if (isset($data['region_id'])) {
            $condition['emp.region_id'] = $data['region_id'];
        }

        if (isset($data['branch_id'])) {
            $condition['emp.branch_id'] = $data['branch_id'];
        }

        $builder = $this->cdb->table('store_request sr');
        // $builder->join('store_request_product srp', 'srp.request_id = sr.request_id');
        $builder->join('employee emp', 'emp.user_id = sr.requested_by');
        $builder->select('COUNT(*) AS total');
        if ($condition) {
            $builder->where($condition);
        }

        // Search
        $searchData = $data['search'] ?? '';
        if ($searchData) {
            if ($data['search']) {
                $builder->groupStart()
                    ->like('sr.title', $searchData)
                    ->groupEnd();
            }
        }

        if (isset($data['except'])) {
            $builder->whereNotIn('sr.request_id', $data['except']);
        }

        $query = $builder->get();
        // echo $this->cdb->getLastQuery();
        if ($query->getNumRows() > 0) {
            return $query->getRow()->total;
        } else {
            return 0;
        }
    }

    public function getRequests($data = [])
    {
        $condition = array();

        if (isset($data['status'])) {
            $condition['sr.status'] = $data['status'];
        }

        if (isset($data['removed'])) {
            $condition['sr.removed'] = $data['removed'];
        }

        if (isset($data['request_id'])) {
            $condition['sr.request_id'] = $data['request_id'];
        }

        if (isset($data['requested_by'])) {
            $condition['sr.requested_by'] = $data['requested_by'];
        }

        if (isset($data['region_id'])) {
            $condition['emp.region_id'] = $data['region_id'];
        }

        if (isset($data['branch_id'])) {
            $condition['emp.branch_id'] = $data['branch_id'];
        }

        $builder = $this->cdb->table('store_request sr');
        $builder->join('employee emp', 'emp.user_id = sr.requested_by');
        $builder->select('sr.*, CONCAT(emp.first_name, " ", emp.last_name) as enginner_name');
        if ($condition) {
            $builder->where($condition);
        }

        // Search
        $searchData = $data['search'] ?? '';
        if ($searchData) {
            if ($data['search']) {
                $builder->groupStart()
                    ->like('sr.title', $searchData)
                    ->groupEnd();
            }
        }

        if (isset($data['except'])) {
            $builder->whereNotIn('sr.request_id', $data['except']);
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
            $sort = 'sr.request_id';
        }

        if (isset($data['order'])) {
            $order = $data['order'];
        } else {
            $order = 'DESC';
        }
        $builder->orderBy($sort, $order);

        $query = $builder->get();
        // echo $this->cdb->getLastQuery();
        if ($query->getNumRows() > 0) {
            return $query->getResult();
        } else {
            return 0;
        }
    }

    public function getRequestProducts($request_id, $data = [])
    {
        $condition = array();

        if (isset($request_id)) {
            $condition['srp.request_id'] = $request_id;
        }

        $builder = $this->cdb->table('store_request_product srp');
        $builder->join('store_product sp', 'sp.product_id = srp.product_id');
        $builder->join('store_category c', 'c.category_id = sp.category_id');
        $builder->join('store_category sc', 'sc.category_id = sp.sub_category_id');
        $builder->select(
            'srp.*, 
            srp.quantity as requested_quantity, 
            sp.*,
            c.name as category_name,
            sc.name as sub_category_name'
        );
        if ($condition) {
            $builder->where($condition);
        }

        if (isset($data['except'])) {
            $builder->whereNotIn('srp.request_id', $data['except']);
        }

        //Sort
        $sortData = $data['sort'] ?? '';
        if ($sortData) {
            $sort = $sortData;
        } else {
            $sort = 'srp.request_product_id';
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

    public function getRequestProduct($request_id, $product_id, $data = [])
    {
        $condition = array(
            'srp.request_id' => (int)$request_id,
            'srp.product_id' => (int)$product_id
        );

        $builder = $this->cdb->table('store_request_product srp');
        $builder->join('store_product sp', 'sp.product_id = srp.product_id');
        $builder->join('store_category c', 'c.category_id = sp.category_id');
        $builder->join('store_category sc', 'sc.category_id = sp.sub_category_id');
        $builder->select(
            'srp.*, 
            srp.quantity as requested_quantity, 
            sp.*,
            c.name as category_name,
            sc.name as sub_category_name'
        );
        if ($condition) {
            $builder->where($condition);
        }

        if (isset($data['except'])) {
            $builder->whereNotIn('srp.request_id', $data['except']);
        }

        //Sort
        $sortData = $data['sort'] ?? '';
        if ($sortData) {
            $sort = $sortData;
        } else {
            $sort = 'srp.request_product_id';
        }

        if (isset($data['order'])) {
            $order = $data['order'];
        } else {
            $order = 'DESC';
        }
        $builder->orderBy($sort, $order);

        $query = $builder->get();

        if ($query->getNumRows() > 0) {
            return $query->getRow();
        } else {
            return 0;
        }
    }

    public function getRequest($request_id, $data = [])
    {
        $condition = array(
            'sr.request_id' => $request_id
        );

        if (isset($data['status'])) {
            $condition['sr.status'] = (int)$data['status'];
        }

        $builder = $this->cdb->table('store_request sr');
        $builder->join('employee emp', 'emp.user_id = sr.requested_by');
        $builder->join('region r', 'r.region_id = emp.region_id');
        $builder->join('branch b', 'b.branch_id = emp.branch_id');
        $builder->join('area a', 'emp.area_id = emp.area_id');
        $builder->select(
            'sr.*,
            CONCAT(emp.first_name, " ", emp.last_name) as enginner_name, 
            emp.mobile as employee_mobile,
            r.name as region_name,
            b.name as branch_name,
            a.name as area_name'
        );
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

    public function getRequestAprrovals($request_id, $data = [])
    {
        $condition = array(
            'sra.request_id' => $request_id
        );

        $builder = $this->cdb->table('store_request_approval sra');
        $builder->join('employee emp1', 'emp1.employee_id = sra.approved_by, left');
        $builder->join('employee emp2', 'emp2.employee_id = sra.rejected_by, left');
        $builder->select('sra.*, emp1.*, emp2.*');
        if ($condition) {
            $builder->where($condition);
        }

        $query = $builder->get();

        if ($query->getNumRows() > 0) {
            return $query->getREsult();
        } else {
            return 0;
        }
    }

    public function getRequestByName($request_title, $data = [])
    {
        $condition = array();

        if (isset($data['removed'])) {
            $condition['sr.removed'] = $data['removed'];
        }

        $builder = $this->cdb->table('store_request sr');
        $builder->join('store_request_product srp', 'srp.request_id = sr.request_id');
        $builder->select('sr.*, srp.*');
        if ($condition) {
            $builder->where($condition);
        }

        // Check name
        $check_name = 'REPLACE(LOWER(title), " ", "_") = REPLACE(LOWER("' . $request_title . '")," ", "_")';
        $builder->where($check_name);

        if (isset($data['except'])) {
            $builder->whereNotIn('request_id', $data['except']);
        }

        $query = $builder->get();

        if ($query->getNumRows() > 0) {
            return $query->getRow();
        } else {
            return 0;
        }
    }

    public function addRequest($data)
    {

        $insert_data = array(
            'title' => $data['title'],
            'requested_by' => $data['requested_by'],
            'description' => $data['description'],
            'contract_job_id' => $data['contract_job_id'],
            'status' => $data['status'],
            'removed' => $data['removed'],
            'created_datetime' => date('Y-m-d H:i:s')
        );
        $this->cdb->transStart();
        $this->cdb->table('store_request')->insert($insert_data);
        $insertId = $this->cdb->insertID();

        // Update request number
        $requestNumber = $this->formRequestNumber($insertId, 'REQ-');
        $this->cdb->table('store_request')->where('request_id', $insertId)->update([
            'request_number' => $requestNumber,
            'updated_datetime' => date('Y-m-d H:i:s')
        ]);

        $this->cdb->transComplete();

        if ($this->cdb->transStatus() === false) {
            return false;
        } else {
            return $insertId;
        }
    }

    public function editRequest($request_id, $data)
    {

        $condition = array(
            'request_id' => $request_id
        );
        $update_data = array(
            'title' => $data['title'],
            'requested_by' => $data['requested_by'],
            'description' => $data['description'],
            'contract_job_id' => $data['contract_job_id'],
            'status' => $data['status'],
            'removed' => $data['removed'],
            'updated_datetime' => date('Y-m-d H:i:s')
        );
        $this->cdb->transStart();
        $builder = $this->cdb->table('store_request');
        $builder->where($condition);
        $result = $builder->update($update_data);

        $this->cdb->transComplete();

        if ($this->cdb->transStatus() === false) {
            return false;
        } else {
            return $result;
        }
    }

    public function updateRequest($request_id, $data)
    {

        $condition = array(
            'request_id' => $request_id
        );
        $update_data = array(
            'status' => $data['status'],
            'updated_datetime' => date('Y-m-d H:i:s')
        );
        $this->cdb->transStart();
        $builder = $this->cdb->table('store_request');
        $builder->where($condition);
        $result = $builder->update($update_data);

        $this->cdb->transComplete();

        if ($this->cdb->transStatus() === false) {
            return false;
        } else {
            return $result;
        }
    }

    public function removeRequest($request_id)
    {

        $condition = array(
            'request_id' => $request_id
        );
        $update_data = array(
            'removed' => 1,
            'status' => 0,
            'updated_datetime' => date('Y-m-d H:i:s')
        );
        $this->cdb->transStart();
        $builder = $this->cdb->table('store_product');
        $builder->where($condition);
        $result = $builder->update($update_data);
        $this->cdb->transComplete();

        if ($this->cdb->transStatus() === false) {
            return false;
        } else {
            return $result;
        }
    }

    public function addProductRequest($request_id, $datas)
    {

        $this->cdb->transStart();
        foreach ($datas as $data) {
            $data = (!is_array($data)) ? (array)$data : $data;
            $insert_data = array(
                'request_id' => $request_id,
                'product_id' => $data['id'],
                'quantity' => $data['quantity'],
            );
            $this->cdb->table('store_request_product')->insert($insert_data);
        }

        $this->cdb->transComplete();

        if ($this->cdb->transStatus() === false) {
            return false;
        } else {
            return true;
        }
    }

    public function addRequestApproval($request_id, $request = [])
    {

        $this->cdb->transStart();

        $insert_data = array(
            'request_id' => $request_id,
            'is_approved' => $request['is_approved'],
            'approved_by' => $request['approved_by'],
            'created_datetime' => date('Y-m-d H:i:s')
        );
        $this->cdb->table('store_request_approval')->insert($insert_data);
        $insertId = $this->cdb->insertID();

        $this->cdb->transComplete();

        if ($this->cdb->transStatus() === false) {
            return false;
        } else {
            return $insertId;
        }
    }

    public function isuueRequestProduct($request_id, $products = [], $request = [])
    {

        $this->cdb->transStart();
        foreach ($products as $product) {
            $this->cdb->table('store_product')
                ->where(['product_id' => (int)$product->product_id])
                ->set('quantity', 'quantity - ' . $product->requested_quantity, false)
                ->update();

            // Add stock detail for tracking
            $stock_data = array(
                'product_id' => (int)$product->product_id,
                'updated_by' => (int)$request['approved_by'],
                'quantity' => (int)$product->requested_quantity,
                'operation' => 'minus',
                'created_datetime' => date('Y-m-d H:i:s')
            );
            $this->cdb->table('store_product_stock')
                ->insert($stock_data);
        }

        $insert_data = array(
            'request_id' => $request_id,
            'is_approved' => $request['is_approved'],
            'approved_by' => $request['approved_by'],
            'created_datetime' => date('Y-m-d H:i:s')
        );
        $this->cdb->table('store_request_approval')->insert($insert_data);
        $insertId = $this->cdb->insertID();

        $this->cdb->transComplete();

        if ($this->cdb->transStatus() === false) {
            return false;
        } else {
            return $insertId;
        }
    }

    public function addRequestReject($request_id, $data = [])
    {

        $insert_data = array(
            'request_id' => $request_id,
            'is_rejected' => $data['is_rejected'],
            'rejected_by' => $data['rejected_by'],
            'created_datetime' => date('Y-m-d H:i:s')
        );
        $this->cdb->transStart();
        $this->cdb->table('store_request_approval')->insert($insert_data);
        $insertId = $this->cdb->insertID();

        $this->cdb->transComplete();

        if ($this->cdb->transStatus() === false) {
            return false;
        } else {
            return $insertId;
        }
    }

    public function formRequestNumber($id, $prefix = '')
    {
        $idLen = strlen($id);
        $idExpMinLen = 4;
        $idStr = '';
        $idPrefix = $prefix ? $prefix : '#REQ-';
        if ($idLen > $idExpMinLen) {
            $idStr = $id;
        } else {
            $defLen = ($idExpMinLen - $idLen);
            for ($l = 1; $l <= $defLen; $l++) {
                $idStr .= '0';
            }

            $idStr .= $id;
        }

        return $idPrefix . $idStr;
    }
}
