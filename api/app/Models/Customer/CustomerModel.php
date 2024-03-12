<?php

namespace App\Models\Customer;

use Config\Services;
use Config\Database;

class CustomerModel
{
    private $cdb;

    public function __construct()
    {
        $this->cdb = Database::connect('default');
    }

    public function getCustomerByID($user_id, $data = [])
    {
        $condition = array(
            'u.user_id' => (int)$user_id
        );


        if (isset($data['status'])) {
            $condition['c.status'] = (int)$data['status'];
        }

        if (isset($data['removed'])) {
            $condition['c.removed'] = (int)$data['removed'];
        }

        $builder = $this->cdb->table('customer AS c');
        $builder->join('user u', 'u.user_id = c.user_id');

        $builder->select('c.*, u.username AS username, u.image as user_image');
        if ($condition) {
            $builder->where($condition);
        }

        $query = $builder->get();
        // echo $this->cdb->getLastQuery();
        if ($query->getNumRows() > 0) {
            return $query->getRow();
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
        $builder->join('user u', 'u.user_id = c.user_id');

        $builder->select('c.*, u.username AS username');
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

    public function editProfile($customer_id, $data)
    {
        $condition = array(
            'customer_id' => $customer_id
        );
        $update_data = array(
             'company_name' => $data['company_name'],
             'sector' => $data['sector'] ?? 0,
             'billing_address' => $data['billing_address'] ?? '',
             'billing_address_contact_name' => $data['billing_address_contact_name'],
             'billing_address_email' => $data['billing_address_email'] ?? '',
             'billing_address_mobile' => $data['billing_address_mobile'],
             'billing_address_country' => $data['billing_address_country_id'] ?? 0,
             'billing_address_state' => $data['billing_address_state_id'] ?? 0,
             'billing_address_city' => $data['billing_address_city_id'] ?? 0,
             'billing_address_pincode' => $data['billing_address_pincode'] ?? '',
             'website' => $data['website'] ?? '',
             'gst_number' => $data['gst_number'] ?? '',
             'pan_number' => $data['pan_number'] ?? '',
             'status' => $data['status'] ?? 1,
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
                        'user_type' => $user_type,
                        'status' => 1
                    );
                    
                    if (isset($data['image']) && $data['image']) {
                        $user_data['image'] = $data['image'];
                    }
                    $this->cdb->table('user')->where([
                        'user_id' => $customer->user_id
                    ])->update($user_data);
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

    public function getEmployeeByValidation($data = array())
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
            ->Where('username', $data['username'], 'before')
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
}
