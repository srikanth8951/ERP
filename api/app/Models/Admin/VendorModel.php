<?php

namespace App\Models\Admin;

use Config\Services;
use Config\Database;

class VendorModel
{
    private $cdb;

    public function __construct()
    {
        $this->cdb = Database::connect('default');
    }

    public function getTotalVendors($data = [])
    {
        $condition = array();

        if (isset($data['status'])) {
            $condition['status'] = (int)$data['status'];
        }

        if (isset($data['removed'])) {
            $condition['removed'] = (int)$data['removed'];
        }

        $builder = $this->cdb->table('vendor');
        $builder->select('COUNT(*) AS total');

        if ($condition) {
            $builder->where($condition);
        }

        // Search
        $searchData = $data['search'] ?? '';
        if ($searchData) {
            if ($data['search']) {
                $builder->groupStart()
                    ->like('organization_name', $searchData)
                    ->orLike('code', $searchData)
                    ->orLike('group', $searchData)
                    ->groupEnd();
            }
        }

        if (isset($data['except'])) {
            $builder->whereNotIn('vendor_id', $data['except']);
        }

        $query = $builder->get();

        if ($query->getNumRows() > 0) {
            return $query->getRow()->total;
        } else {
            return 0;
        }
    }

    public function getVendors($data = [])
    {
        $condition = array();

        if (isset($data['status'])) {
            $condition['status'] = (int)$data['status'];
        }

        if (isset($data['sector_id'])) {
            $condition['sector_id'] = (int)$data['sector_id'];
        }

        if (isset($data['removed'])) {
            $condition['removed'] = (int)$data['removed'];
        }

        $builder = $this->cdb->table('vendor');
        $builder->select('*');
        
        if ($condition) {
            $builder->where($condition);
        }

        // Search
        $searchData = $data['search'] ?? '';
        if ($searchData) {
            if ($data['search']) {
                $builder->groupStart()
                    ->like('organization_name', $searchData)
                    ->orLike('code', $searchData)
                    ->orLike('group', $searchData)
                    ->groupEnd();
            }
        }

        if (isset($data['except'])) {
            $builder->whereNotIn('vendor_id', $data['except']);
        }

        if (isset($data['is_exist'])) {
            $builder->where('is_exist', 0);
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
            $sort = 'vendor_id';
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

    public function getVendor($vendor_id, $data = [])
    {
        $condition = array(
            'v.vendor_id' => (int)$vendor_id
        );

        if (isset($data['status'])) {
            $condition['v.status'] = (int)$data['status'];
        }

        $builder = $this->cdb->table('vendor as v');
        $builder->join('countries c', 'c.country_id =  v.country', 'left');
        $builder->join('states s', 's.state_id =  v.state', 'left');
        $builder->join('cities cy', 'cy.city_id =  v.city', 'left');
        $builder->select('v.*, c.name as country_name, s.name as state_name, cy.name as city_name');
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

    public function getVendorByType($data = [])
    {
        $condition = array();

        if (isset($data['status'])) {
            $condition['status'] = (int)$data['status'];
        }

        if (isset($data['removed'])) {
            $condition['removed'] = (int)$data['removed'];
        }

        $builder = $this->cdb->table('vendor');

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

    public function getVendorByName($vendor_name, $data = [])
    {
        $condition = array();

        if (isset($data['removed'])) {
            $condition['removed'] = $data['removed'];
        }

        $builder = $this->cdb->table('vendor');
        $builder->select('*');

        if ($condition) {
            $builder->where($condition);
        }

        // Check name
        $check_name = 'REPLACE(LOWER(organization_name), " ", "_") = REPLACE(LOWER("' . $vendor_name . '")," ", "_")';
        $builder->where($check_name);

        $query = $builder->get();

        if ($query->getNumRows() > 0) {
            return $query->getRow();
        } else {
            return [];
        }
    }

    public function getVendorByEmail($email, $data = array())
    {
        $condition['email'] = $email;
        if (isset($data['status'])) {
            $condition['status'] = (int)$data['status'];
        }

        if (isset($data['removed'])) {
            $condition['removed'] = (int)$data['removed'];
        }

        $builder = $this->cdb->table('vendor');
        $builder->where($condition);

        if (isset($data['except'])) {
            if ($data['except']) {
                $builder->whereNotIn('vendor_id', $data['except']);
            }
        }

        $query = $builder->get();

        if ($query->getNumRows() > 0) {
            return $query->getRow();
        } else {
            return false;
        }
    }

    public function addVendor($data)
    {

        $this->cdb->transStart();
        $insert_data = array(
            'organization_name' => $data['organization_name'],
            'code' => $data['code'] ?? '',
            'group' => $data['group'] ?? '',
            'contact_name' => $data['contact_name'],
            'email' => $data['email'] ?? '',
            'mobile' => $data['mobile'],
            'address1' => $data['address1'] ?? '',
            'address2' => $data['address2'] ?? '',
            'country' => $data['country_id'] ?? 0,
            'state' => $data['state_id'] ?? 0,
            'city' => $data['city_id'] ?? 0,
            'pincode' => $data['pincode'] ?? '',
            'website' => $data['website'] ?? '',
            'region_id' => $data['region_id'] ?? 0,
            'branch_id' => $data['branch_id'] ?? 0,
            'area_id' => $data['area_id'] ?? 0,
            'gst_number' => $data['gst_number'] ?? '',
            'payment_term' => $data['payment_term'] ?? '',
            'pan_number' => $data['pan_number'] ?? '',
            'bank_name' => $data['bank_name'] ?? '',
            'bank_account_person_name' => $data['bank_account_person_name'] ?? '',
            'bank_account_number' => $data['bank_account_number'] ?? '',
            'bank_branch_name' => $data['bank_branch_name'] ?? '',
            'bank_ifsc_code' => $data['bank_ifsc_code'] ?? '',
            'status' => $data['status'] ?? 1,
            // 'is_exist' => $data['is_exist'],
            'created_datetime' => date('Y-m-d H:i:s')
        );
        $this->cdb->table('vendor')->insert($insert_data);
        $venInsertId = $this->cdb->insertID();

        $this->cdb->transComplete();
        if ($this->cdb->transStatus() === false) {
            return false;
        } else {
            return $venInsertId;
        }
    }

    public function getVendorEvaluations($vendor_id, $data = [])
    {
        $condition = array(
            'vendor_id' => (int)$vendor_id
        );

        $builder = $this->cdb->table('vendor_evaluation');

        $builder->select('*');
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

    public function getVendorEvaluation($vendor_evaluation_id, $data = [])
    {
        $condition = array(
            'vendor_evaluation_id' => (int)$vendor_evaluation_id
        );

        $builder = $this->cdb->table('vendor_evaluation');

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

    public function addVendorEvaluation($file, $vendor_id)
    {

        $this->cdb->transStart();
        $insert_data = array(
            'vendor_id' => $vendor_id,
            'file' => $file['image'],
            'created_datetime' => date('Y-m-d H:i:s')
        );
        $this->cdb->table('vendor_evaluation')->insert($insert_data);
        $venEvalInsertId = $this->cdb->insertID();

        $this->cdb->transComplete();
        if ($this->cdb->transStatus() === false) {
            return false;
        } else {
            return $venEvalInsertId;
        }
    }


    public function editVendor($vendor_id, $data)
    {
        $condition = array(
            'vendor_id' => $vendor_id
        );
        $update_data = array(
            'organization_name' => $data['organization_name'],
            'code' => $data['code'] ?? '',
            'group' => $data['group'] ?? '',
            'contact_name' => $data['contact_name'],
            'email' => $data['email'] ?? '',
            'mobile' => $data['mobile'],
            'address1' => $data['address1'] ?? '',
            'address2' => $data['address2'] ?? '',
            'country' => $data['country_id'] ?? 0,
            'state' => $data['state_id'] ?? 0,
            'city' => $data['city_id'] ?? 0,
            'pincode' => $data['pincode'] ?? '',
            'website' => $data['website'] ?? '',
            'region_id' => $data['region_id'] ?? 0,
            'branch_id' => $data['branch_id'] ?? 0,
            'area_id' => $data['area_id'] ?? 0,
            'gst_number' => $data['gst_number'] ?? '',
            'payment_term' => $data['payment_term'] ?? '',
            'pan_number' => $data['pan_number'] ?? '',
            'bank_name' => $data['bank_name'] ?? '',
            'bank_account_person_name' => $data['bank_account_person_name'] ?? '',
            'bank_account_number' => $data['bank_account_number'] ?? '',
            'bank_branch_name' => $data['bank_branch_name'] ?? '',
            'bank_ifsc_code' => $data['bank_ifsc_code'] ?? '',
            'status' => $data['status'] ?? 1,
            // 'is_exist' => $data['is_exist'],
            'created_datetime' => date('Y-m-d H:i:s')
        );

        $this->cdb->transStart();
        $builder = $this->cdb->table('vendor');
        $builder->where($condition);
        $result = $builder->update($update_data);

        $this->cdb->transComplete();

        if ($this->cdb->transStatus() === false) {
            return false;
        } else {
            return $result;
        }
    }

    public function removeVendor($vendor_id)
    {
        $this->cdb->transStart();
        $condition = array(
            'vendor_id' => $vendor_id
        );
        $update_data = array(
            'removed' => 1,
            'status' => 0,
            'updated_datetime' => date('Y-m-d H:i:s')
        );
        $builder = $this->cdb->table('vendor');
        $builder->where($condition);
        $result = $builder->update($update_data);
        $this->cdb->transComplete();

        if ($this->cdb->transStatus() === false) {
            return false;
        } else {
            return $result;
        }
    }

    public function setVendorStatus($vendor_id, $status)
    {
        $this->cdb->transStart();
        $condition = array(
            'vendor_id' => $vendor_id
        );
        $update_data = array(
            'status' => $status,
            'updated_datetime' => date('Y-m-d H:i:s')
        );
        $builder = $this->cdb->table('vendor');
        $builder->where($condition);
        $result = $builder->update($update_data);
        $this->cdb->transComplete();

        if ($this->cdb->transStatus() === false) {
            return false;
        } else {
            return $result;
        }
    }

    public function deleteVendor($vendor_id)
    {
        $this->cdb->transStart();
        $condition = array(
            'vendor_id' => $vendor_id
        );
        $builder = $this->cdb->table('vendor');
        $builder->where($condition);
        $result = $builder->delete();
        $this->cdb->transComplete();

        if ($this->cdb->transStatus() === false) {
            return false;
        } else {
            return $result;
        }
    }

    public function deleteVendorEvaluation($vendor_evaluation_id)
    {
        $this->cdb->transStart();
        $condition = array(
            'vendor_evaluation_id' => $vendor_evaluation_id
        );
        $builder = $this->cdb->table('vendor_evaluation');
        $builder->where($condition);
        $result = $builder->delete();
        $this->cdb->transComplete();

        if ($this->cdb->transStatus() === false) {
            return false;
        } else {
            return $result;
        }
    }

    function getVendorExist($data = [])
    {
        $condition = array();

        $condition = array(
            'removed' => 0,
            'status' => 1,
            'is_exist' => $data['is_exist'],
            'email' => $data['email']
        );
        $builder = $this->cdb->table('vendor');
        $builder->select('*');
        $builder->groupStart()
            ->like('email', $data['email'])
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

    function getVendorExists($data = [])
    {
        $condition = array();

        $condition = array(
            'removed' => 0,
            'status' => 1,
            'is_exist' => $data['is_exist'],
            'email' => $data['email'],
        );
        $builder = $this->cdb->table('vendor');
        $builder->select('*');
        $builder->groupStart()
            ->like('email', $data['email'])
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

    public function updateVendor($id, $data = [])
    {
        $condition = array(
            'vendor_id' => $id,
            'email' => $data['email'],
        );
        $update_data = array(
            'is_exist' => 2,
            'updated_datetime' => date('Y-m-d H:i:s')
        );
        $builder = $this->cdb->table('vendor');
        $builder->where($condition);
        $result = $builder->update($update_data);
        $this->cdb->transComplete();

        if ($this->cdb->transStatus() === false) {
            return false;
        } else {
            return $result;
        }
    }

    public function updateVendorDetails($val)
    {
        $condition = array(
            'email' => $val,
            'is_exist' => 3,
            'status' => 0,
            'removed' => 0,
        );
        $update_data = array(
            'is_exist' => 4, //newly existing Vendor in db
            'status' => 2,
        );
        $this->cdb->transStart();
        $builder = $this->cdb->table('vendor');
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

        $builder = $this->cdb->table('vendor');
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
        $builder = $this->cdb->table('vendor');
        $builder->where($condition);
        $result = $builder->update($update_data);
        $this->cdb->transComplete();

        if ($this->cdb->transStatus() === false) {
            return false;
        } else {
            return true;
        }
    }

    public function updateUploadVendor()
    {
        $condition = array(
            'is_exist' => 3,
            //'status' => 0,
        );

        $update_data = array(
            'is_exist' => 0, //already existing Vendor in db
            'status' => 1,
            'updated_datetime' => date('Y-m-d H:i:s')
        );

        $this->cdb->transStart();
        $builder = $this->cdb->table('vendor');
        $builder->where($condition);
        $result = $builder->update($update_data);

        $builder = $this->cdb->table('vendor');
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

    public function getVendors23($data = [])
    {
        $condition = array();

        if (isset($data['removed'])) {
            $condition[' v.removed'] = (int)$data['removed'];
        }


        $builder = $this->cdb->table('vendor AS v');

        $builder->select(' v.*');
        $builder->whereNotIn(' v.is_exist', array('0'));
        $builder->whereNotIn(' v.status', array('1'));
        $builder->where($condition);

        $query = $builder->get();

        if ($query->getNumRows() > 0) {
            return $query->getResult();
        } else {
            return 0;
        }
    }
}
