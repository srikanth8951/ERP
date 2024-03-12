<?php

namespace App\Models\Admin;

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

        if (isset($data['sector_id'])) {
            $condition[' c.sector'] = (int)$data['sector_id'];
        }

        if (isset($data['removed'])) {
            $condition[' c.removed'] = (int)$data['removed'];
        }

        $builder = $this->cdb->table('customer AS c');
        $builder->join('customer_sector cs', 'cs.customer_sector_id =  c.sector', 'left');
        $builder->join('payment_term pt', 'pt.payment_term_id =  c.payment_term', 'left');
        $builder->select('COUNT(*) AS total');

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
            $condition[' c.status'] = (int)$data['status'];
        }

        if (isset($data['sector_id'])) {
            $condition[' c.sector'] = (int)$data['sector_id'];
        }

        if (isset($data['removed'])) {
            $condition[' c.removed'] = (int)$data['removed'];
        }

        $builder = $this->cdb->table('customer c');

        $builder->select(' c.*, u.username as username, cs.title as customer_sector_title, pt.title as payment_term_title');
        $builder->join('user u', 'u.user_id =  c.user_id', 'left');
        $builder->join('customer_sector cs', 'cs.customer_sector_id =  c.sector', 'left');
        $builder->join('payment_term pt', 'pt.payment_term_id =  c.payment_term', 'left');
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

    public function getCustomerValidation($data = array())
    {
        // print_r($data);
        if (isset($data['removed'])) {
            $condition['removed'] = (int)$data['removed'];
        }

        if (isset($data['status'])) {
            $condition['status'] = (int)$data['status'];
        }

        $builder = $this->cdb->table('user');
        $builder->groupStart()
            // ->where('email', $data['email'], 'before')
            ->where('username', $data['username'], 'before')
            ->groupEnd();
        $builder->where($condition);

        if (isset($data['except'])) {
            if ($data['except']) {
                $builder->whereNotIn('user_id', $data['except']);
            }
        }

        $query = $builder->get();
        // echo  $this->cdb->getLastQuery();
        // exit;

        if ($query->getNumRows() > 0) {
            return $query->getRow();
        } else {
            return false;
        }
    }

    public function addCustomer($data)
    {

        $this->cdb->transStart();
        $insert_data = array(
            // 'name' => $data['contact_name'],
            'company_name' => $data['company_name'],
            'sector' => $data['sector'] ?? 0,
            'billing_address' => $data['billing_address'] ?? '',
            'billing_address_contact_name' => $data['billing_address_contact_name'],
            'billing_address_email' => $data['billing_address_email'] ?? '',
            'site_address' => $data['site_address'] ?? '',
            'billing_address_mobile' => $data['billing_address_mobile'],
            'billing_address_country' => $data['billing_address_country_id'] ?? 0,
            'billing_address_state' => $data['billing_address_state_id'] ?? 0,
            'billing_address_city' => $data['billing_address_city_id'] ?? 0,
            'billing_address_pincode' => $data['billing_address_pincode'] ?? '',
            'site_address_contact_name' => $data['site_address_contact_name'],
            'site_address_email' => $data['site_address_email'] ?? '',
            'site_address_mobile' => $data['site_address_mobile'],
            'site_address_country' => $data['site_address_country_id'] ?? 0,
            'site_address_state' => $data['site_address_state_id'] ?? 0,
            'site_address_city' => $data['site_address_city_id'] ?? 0,
            'site_address_pincode' => $data['site_address_pincode'] ?? '',
            'website' => $data['website'] ?? '',
            'gst_number' => $data['gst_number'] ?? '',
            'payment_term' => $data['payment_term'] ?? '',
            'pan_number' => $data['pan_number'] ?? '',
            // 'geolocation_lattitude' => $data['customer_location_lattitude'] ?? '',
            // 'geolocation_longitude' => $data['customer_location_longitude'] ?? '',
            'status' => $data['status'] ?? 1,
            'is_exist' => $data['is_exist'],
            'created_datetime' => date('Y-m-d H:i:s')
        );
        $this->cdb->table('customer')->insert($insert_data);
        $cusInsertId = $this->cdb->insertID();

        //upload validation
        if (
            $data['billing_address_contact_name'] == '' &&
            $data['company_name'] == '' &&
            $data['billing_address_email'] == '' &&
            $data['billing_address_mobile'] == '' &&
            $data['username'] == '' &&
            $data['password']
        ) {
            $this->removeCustomer($cusInsertId);
        }
        //end

        // Add user
        if ($data['username'] && $data['user_type'] && $data['password']) {
            $password = password_hash($data['password'], PASSWORD_BCRYPT);
            $user_type = $data['user_type'];
            $user_data = array(
                'first_name' => $data['company_name'] ?? '',
                'last_name' => $data['last_name'] ?? '',
                'username' => $data['username'],
                'password' => $password,
                // 'email' => $data['billing_address_email'] ?? '',
                'mobile' => $data['billing_address_mobile'],
                //'permission' => $user_type['permission'],
                'user_type' => $user_type,
                'status' => 1
            );

            $this->cdb->table('user')->insert($user_data);
            $userInsertID = $this->cdb->insertID();
            if ($userInsertID) {
                $this->setCustomerUserId($cusInsertId, $userInsertID);
            }
        }

        $this->cdb->transComplete();
        if ($this->cdb->transStatus() === false) {
            return false;
        } else {
            return $cusInsertId;
        }
    }

    public function editCustomer($customer_id, $data)
    {
        $condition = array(
            'customer_id' => $customer_id
        );
        $update_data = array(
            // 'name' => $data['contact_name'],
            'company_name' => $data['company_name'],
            'sector' => $data['sector'] ?? 0,
            'billing_address' => $data['billing_address'] ?? '',
            'billing_address_contact_name' => $data['billing_address_contact_name'],
            'billing_address_email' => $data['billing_address_email'] ?? '',
            'site_address' => $data['site_address'] ?? '',
            'billing_address_mobile' => $data['billing_address_mobile'],
            'billing_address_country' => $data['billing_address_country_id'] ?? 0,
            'billing_address_state' => $data['billing_address_state_id'] ?? 0,
            'billing_address_city' => $data['billing_address_city_id'] ?? 0,
            'billing_address_pincode' => $data['billing_address_pincode'] ?? '',
            'site_address_contact_name' => $data['site_address_contact_name'],
            'site_address_email' => $data['site_address_email'] ?? '',
            'site_address_mobile' => $data['site_address_mobile'],
            'site_address_country' => $data['site_address_country_id'] ?? 0,
            'site_address_state' => $data['site_address_state_id'] ?? 0,
            'site_address_city' => $data['site_address_city_id'] ?? 0,
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
                        // 'email' => $data['billing_address_email'] ?? '',
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

    public function removeCustomer($customer_id, $user_id)
    {
        $this->cdb->transStart();
        $condition = array(
            'customer_id' => $customer_id
        );
        $condition1 = array(
            'user_id' => $user_id
        );
        $update_data = array(
            'removed' => 1,
            'status' => 0,
            'updated_datetime' => date('Y-m-d H:i:s')
        );
        $builder = $this->cdb->table('customer');
        $builder->where($condition);
        $result = $builder->update($update_data);

        $builder = $this->cdb->table('user');
        $builder->where($condition1);
        $result = $builder->update($update_data);
        $this->cdb->transComplete();

        if ($this->cdb->transStatus() === false) {
            return false;
        } else {
            return $result;
        }
    }

    public function setCustomerStatus($customer_id, $status, $user_id)
    {
        $this->cdb->transStart();
        $condition = array(
            'customer_id' => $customer_id
        );
        $condition1 = array(
            'user_id' => $user_id
        );
        $update_data = array(
            'status' => $status,
            'updated_datetime' => date('Y-m-d H:i:s')
        );
        $builder = $this->cdb->table('customer');
        $builder->where($condition);
        $result = $builder->update($update_data);

        $builder = $this->cdb->table('user');
        $builder->where($condition1);
        $result = $builder->update($update_data);
        $this->cdb->transComplete();

        if ($this->cdb->transStatus() === false) {
            return false;
        } else {
            return $result;
        }
    }

    public function deleteCustomer($customer_id)
    {
        $this->cdb->transStart();
        $condition = array(
            'customer_id' => $customer_id
        );
        $builder = $this->cdb->table('customer');
        $builder->where($condition);
        $result = $builder->delete();
        $this->cdb->transComplete();

        if ($this->cdb->transStatus() === false) {
            return false;
        } else {
            return $result;
        }
    }

    function getCustomerExist($data = [])
    {
        $condition = array();

        $condition = array(
            'c.removed' => 0,
            'c.status' => 1,
            'c.is_exist' => $data['is_exist'],
            'u.username' => $data['username']
        );
        $builder = $this->cdb->table('customer c');
        $builder->join('user u', 'u.user_id =  c.user_id');
        $builder->select('c.*');
        $builder->groupStart()
            ->like('u.username', $data['username'])
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

    function getCustomerExists($data = [])
    {
        $condition = array();

        $condition = array(
            'c.removed' => 0,
            'c.status' => 1,
            'c.is_exist' => $data['is_exist'],
            'u.username' => $data['username']
        );
        $builder = $this->cdb->table('customer c');
        $builder->join('user u', 'u.user_id =  c.user_id');
        $builder->select('c.*, u.username as username');
        $builder->groupStart()
            ->like('u.username', $data['username'])
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

    public function updateCustomer($id, $data = [])
    {
        $condition = array(
            'customer_id' => $id,
            // 'email' => $data['email'],
        );
        $update_data = array(
            'is_exist' => 2,
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

    public function updateCustomerDetails($username)
    {
        $condition = array(
            // 'username' => $username,
            'is_exist' => 3,
            'status' => 0,
            'removed' => 0,
        );
        $update_data = array(
            'is_exist' => 4, //newly existing Customer in db
            'status' => 2,
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

    public function cancelUpload()
    {
        $this->cdb->transStart();

        $builder = $this->cdb->table('customer');
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
        $builder = $this->cdb->table('customer');
        $builder->where($condition);
        $result = $builder->update($update_data);
        $this->cdb->transComplete();

        if ($this->cdb->transStatus() === false) {
            return false;
        } else {
            return true;
        }
    }

    public function updateUploadCustomer()
    {
        $condition = array(
            'is_exist' => 3,
            //'status' => 0,
        );

        $update_data = array(
            'is_exist' => 0, //already existing Customer in db
            'status' => 1,
            'updated_datetime' => date('Y-m-d H:i:s')
        );

        $this->cdb->transStart();
        $builder = $this->cdb->table('customer');
        $builder->where($condition);
        $result = $builder->update($update_data);

        $builder = $this->cdb->table('customer');
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

    public function getCustomersDetails($data = [])
    {
        $condition = array();

        if (isset($data['user_type'])) {
            // $condition[' c.user_type'] = (int)$data['user_type'];
        }

        if (isset($data['removed'])) {
            $condition[' c.removed'] = (int)$data['removed'];
        }


        $builder = $this->cdb->table('customer c');

        $builder->select(' c.*');
        $builder->whereNotIn(' c.is_exist', array('0'));
        $builder->whereNotIn(' c.status', array('1'));
        $builder->where($condition);

        $query = $builder->get();

        if ($query->getNumRows() > 0) {
            return $query->getResult();
        } else {
            return 0;
        }
    }
}
