<?php

namespace App\Models\Employee;

use Config\Services;
use Config\Database;

class CustomerModel
{
    private $cdb;

    public function __construct()
    {
        $this->cdb = Database::connect('default');
    }

    public function getTotalCustomers($data = [])
    {
        $condition = array();

        if (isset($data['status'])) {
            $condition[' c.status'] = (int)$data['status'];
        }

        if (isset($data['removed'])) {
            $condition[' c.removed'] = (int)$data['removed'];
        }

        if (isset($data['region_id'])) {
            $condition[' emp1.region_id'] = $data['region_id'];
        }

        if (isset($data['branch_id'])) {
            $condition[' emp1.branch_id'] = $data['branch_id'];
        }

        if (isset($data['cam_id'])) {
            $condition[' emp2.employee_id'] = $data['cam_id'];
        }

        // $this->db->distinct();
        $builder = $this->cdb->table('customer AS c');
        $builder->join('contract_job cj', 'cj.customer_id =  c.customer_id');
        $builder->join('employee emp1', 'emp1.employee_id =  cj.engineer_id');
        $builder->join('employee emp2', 'emp2.employee_id =  cj.customer_account_manager_id');
        $builder->select('COUNT(DISTINCT(c.customer_id)) AS total');

        if ($condition) {
            $builder->where($condition);
        }

        // Search
        $searchData = $data['search'] ?? '';
        if ($searchData) {
            if ($data['search']) {
                $builder->groupStart()
                    ->like('c.company_name', $searchData)
                    ->groupEnd();
            }
        }

        if (isset($data['except'])) {
            $builder->whereNotIn(' c.customer_id', $data['except']);
        }

        $query = $builder->get();
        
        if ($query->getNumRows() > 0) {
            return $query->getRow()->total;
        } else {
            return 0;
        }
    }

    public function getCustomers($data = [])
    {
        $condition = array();

        if (isset($data['status'])) {
            $condition[' c.status'] = $data['status'];
        }

        if (isset($data['sector_id'])) {
            $condition[' c.sector'] = $data['sector_id'];
        }

        if (isset($data['removed'])) {
            $condition[' c.removed'] = $data['removed'];
        }

        if (isset($data['region_id'])) {
            $condition[' emp1.region_id'] = $data['region_id'];
        }

        if (isset($data['branch_id'])) {
            $condition[' emp1.branch_id'] = $data['branch_id'];
        }

        if (isset($data['cam_id'])) {
            $condition[' emp2.employee_id'] = $data['cam_id'];
        }

        $builder = $this->cdb->table('customer c');
        $builder->distinct();
        $builder->select('c.*');
        $builder->join('contract_job cj', 'cj.customer_id =  c.customer_id');
        $builder->join('employee emp1', 'emp1.employee_id =  cj.engineer_id');
        $builder->join('employee emp2', 'emp2.employee_id =  cj.customer_account_manager_id');
        if ($condition) {
            $builder->where($condition);
        }

        // Search
        $searchData = $data['search'] ?? '';
        if ($searchData) {
            if ($data['search']) {
                $builder->groupStart()
                    ->like('c.company_name', $searchData)
                    ->groupEnd();
            }
        }

        if (isset($data['except'])) {
            $builder->whereNotIn(' c.customer_id', $data['except']);
        }

        if (isset($data['is_exist'])) {
            $builder->where(' c.is_exist', 0);
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
            $sort = ' c.customer_id';
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

    public function getCustomer($customer_id, $data = [])
    {
        $condition = array(
            'c.customer_id' => (int)$customer_id
        );

        if (isset($data['status'])) {
            $condition['c.status'] = (int)$data['status'];
        }

        $builder = $this->cdb->table('customer AS c');

        $builder->join('countries ct', 'ct.country_id =  c.billing_address_country', 'left');
        $builder->join('countries ct2', 'ct2.country_id =  c.site_address_country', 'left');
        $builder->join('states s', 's.state_id =  c.billing_address_state', 'left');
        $builder->join('states s2', 's2.state_id =  c.site_address_state', 'left');
        $builder->join('cities cy1', 'cy1.city_id =  c.billing_address_city', 'left');
        $builder->join('cities cy2', 'cy2.city_id =  c.site_address_city', 'left');
        $builder->join('customer_sector cs', 'cs.customer_sector_id =  c.sector', 'left');
        $builder->join('payment_term pt', 'pt.payment_term_id =  c.payment_term', 'left');
        $builder->join('user u', 'u.user_id =  c.user_id');

        $builder->select('c.*, u.username as username, cs.title as customer_sector_title, pt.title as payment_term_title, 
        ct.name as billing_address_country_name, ct2.name as site_address_country_name, s.name as billing_address_state_name, 
        s2.name as site_address_state_name, cy1.name as billing_address_city_name, cy2.name as site_address_city_name');

        // $builder->select('c.*');
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

    public function getCustomerDetail($user_id, $data = [])
    {
        $condition = array(
            'u.user_id' => (int)$user_id
        );

        if (isset($data['status'])) {
            $condition['c.status'] = (int)$data['status'];
        }

        $builder = $this->cdb->table('customer AS c');
        $builder->join('user u', 'u.user_id =  c.user_id');

        $builder->select('c.*');
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

    public function getCustomerByType($data = [])
    {
        $condition = array();

        if (isset($data['status'])) {
            $condition[' c.status'] = (int)$data['status'];
        }

        if (isset($data['removed'])) {
            $condition[' c.removed'] = (int)$data['removed'];
        }

        $builder = $this->cdb->table('customer AS c');
        $builder->join('user u', 'u.user_id =  c.user_id');

        $builder->select(' c.*, u.username AS username');
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

    public function getCustomerByName($customer_name, $data = [])
    {
        $condition = array();

        if (isset($data['removed'])) {
            $condition['removed'] = $data['removed'];
        }

        $builder = $this->cdb->table('customer');
        $builder->select('*');

        if ($condition) {
            $builder->where($condition);
        }

        // Check name
        $check_name = 'REPLACE(LOWER(name), " ", "_") = REPLACE(LOWER("' . $customer_name . '")," ", "_")';
        $builder->where($check_name);

        $query = $builder->get();

        if ($query->getNumRows() > 0) {
            return $query->getRow();
        } else {
            return [];
        }
    }

    public function getCustomerByEmail($email, $data = array())
    {
        $condition['email'] = $email;
        if (isset($data['status'])) {
            $condition['status'] = (int)$data['status'];
        }

        if (isset($data['removed'])) {
            $condition['removed'] = (int)$data['removed'];
        }

        $builder = $this->cdb->table('customer');
        $builder->where($condition);

        if (isset($data['except'])) {
            if ($data['except']) {
                $builder->whereNotIn('customer_id', $data['except']);
            }
        }

        $query = $builder->get();

        if ($query->getNumRows() > 0) {
            return $query->getRow();
        } else {
            return false;
        }
    }

    public function editCustomer($customer_id, $data)
    {
        $condition = array(
            'customer_id' => $customer_id
        );
        $update_data = array(
            'name' => $data['name'],
            'company_name' => $data['company_name'],
            'job_number' => $data['job_number'] ?? '',
            'sector' => $data['sector'] ?? 0,
            'contact_name' => $data['contact_name'],
            'email' => $data['email'] ?? '',
            'billing_address' => $data['billing_address'] ?? '',
            'site_address' => $data['site_address'] ?? '',
            'billing_address_mobile' => $data['billing_address_mobile'],
            'billing_address_country' => $data['billing_address_country'] ?? 0,
            'billing_address_state' => $data['billing_address_state'] ?? 0,
            'billing_address_city' => $data['billing_address_city'] ?? 0,
            'billing_address_pincode' => $data['billing_address_pincode'] ?? '',
            'site_address_mobile' => $data['site_address_mobile'],
            'site_address_country' => $data['site_address_country'] ?? 0,
            'site_address_state' => $data['site_address_state'] ?? 0,
            'site_address_city' => $data['site_address_city'] ?? 0,
            'site_address_pincode' => $data['site_address_pincode'] ?? '',
            'website' => $data['website'] ?? '',
            'gst_number' => $data['gst_number'] ?? '',
            'payment_term' => $data['payment_term'] ?? '',
            'pan_number' => $data['pan_number'] ?? '',
            'status' => $data['status'] ?? 1,
            // 'is_exist' => $data['is_exist'],
            'updated_datetime' => date('Y-m-d H:i:s')
        );

        $this->cdb->transStart();
        $builder = $this->cdb->table('customer');
        $builder->where($condition);
        $result = $builder->update($update_data);

        // Update user
        $customer = $this->getCustomer($customer_id);
        if ($customer) {
            if ($customer->user_id != 0) {
                if ($data['username'] && $data['user_type']) {
                    $user_type = $data['user_type'];
                    $user_data = array(
                        'first_name' => $data['company_name'] ?? '',
                        'last_name' => $data['last_name'] ?? '',
                        'username' => $data['username'],
                        'email' => $data['email'] ?? '',
                        'mobile' => $data['billing_address_mobile'],
                        //'permission' => $user_type['permission'],
                        'user_type' => $user_type,
                        'status' => 1
                    );

                    $this->cdb->table('user')->where([
                        'user_id' => $customer->user_id
                    ])->update($user_data);
                }

                if ($data['password']) {
                    $hashed_password = password_hash($data['password'], PASSWORD_BCRYPT);
                    $reset_data = array(
                        'password' => $hashed_password,
                        'updated_datetime' => date('Y-m-d H:i:s')
                    );
                    $this->cdb->table('user')->where([
                        'user_id' => $customer->user_id
                    ])->update($reset_data);
                }
            }
        }

        $this->cdb->transComplete();

        if ($this->cdb->transStatus() === false) {
            return false;
        } else {
            return $result;
        }
    }

    public function setCustomerUserId($customer_id, $user_id)
    {
        $condition = array(
            'customer_id' => $customer_id
        );
        $update_data = array(
            'user_id' => $user_id,
            'updated_datetime' => date('Y-m-d H:i:s')
        );
        $this->cdb->transStart();
        $builder = $this->cdb->table('customer');
        $builder->where($condition);
        $result = $builder->update($update_data);

        $this->cdb->transComplete();

        if ($this->cdb->transStatus() === false) {
            return false;
        } else {
            return $result;
        }
    }

    public function removeCustomer($customer_id)
    {
        $this->cdb->transStart();
        $condition = array(
            'customer_id' => $customer_id
        );
        $update_data = array(
            'removed' => 1,
            'status' => 0,
            'updated_datetime' => date('Y-m-d H:i:s')
        );
        $builder = $this->cdb->table('customer');
        $builder->where($condition);
        $result = $builder->update($update_data);
        $this->cdb->transComplete();

        if ($this->cdb->transStatus() === false) {
            return false;
        } else {
            return $result;
        }
    }

    public function setCustomerStatus($customer_id, $status)
    {
        $this->cdb->transStart();
        $condition = array(
            'customer_id' => $customer_id
        );
        $update_data = array(
            'status' => $status,
            'updated_datetime' => date('Y-m-d H:i:s')
        );
        $builder = $this->cdb->table('customer');
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
