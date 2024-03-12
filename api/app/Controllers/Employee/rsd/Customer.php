<?php

namespace App\Controllers\Employee\rsd;

use CodeIgniter\RESTful\ResourceController;
use App\Libraries\Auth\User as AuthUser;
use App\Libraries\Auth\Employee as AuthEmployee;
use App\Models\Employee\CustomerModel;
use App\Models\Employee\EmployeeModel;

class Customer extends ResourceController
{
    protected $userType = 'customer';
    protected $empType = 'rsd_head';
    protected $employeeId;

    public function __construct()
    {
        helper('user');
    }


    public function index()
    {

        $this->validatePermission('view_customer');    // Check permission

        if (!$this->isEmployee()) {
            $response = array(
                'status' => 'error',
                'message' => lang('Common.error_employee_login')
            );

            return $this->setResponseFormat("json")->respond($response, 201);
        }

        $modelCustomer = new CustomerModel(); // Load model
        $modelEmp = new EmployeeModel(); // Load model
        $empDetail = $modelEmp->getEmployee($this->employeeId);

        $start = $this->request->getGet('start');
        if ($start) {
            $start = (int)$start;
        } else {
            $start = 1;
        }

        $length = $this->request->getGet('length');
        if ($length) {
            $limit = (int)$length;
        } else {
            $limit = 10;
        }

        $search = $this->request->getGet('search');
        if ($search) {
            $search = $search;
        } else {
            $search = '';
        }

        $sort = $this->request->getGet('sort_column');
        if ($sort) {
            $sort = $sort;
        } else {
            $sort = '';
        }

        $order = $this->request->getGet('sort_order');
        if ($order) {
            $order = $order;
        } else {
            $order = '';
        }

        $user_type = getUserTypeByCode($this->userType);

        $filter_data = array(
            'removed' => 0,
            'search' => $search,
            'status' => 1,
            'start' => ($start - 1),
            'limit' => $limit,
            'sort' => $sort,
            'order' => $order,
            'is_exist' => 0,
            'user_type' => $user_type['type_id'],
            'region_id' => $empDetail->region_id,
        );

        $total_customers = $modelCustomer->getTotalCustomers($filter_data);
        $customers = $modelCustomer->getCustomers($filter_data);

        if ($customers) {
            $response = array(
                'status' => 'success',
                'message' => lang('Customer.Customer.success_list'),
                'customers' => [
                    'type' => $this->userType,
                    'data' => $customers,
                    'pagination' => array(
                        'total' => (int)$total_customers,
                        'length' => $limit,
                        'start' => $start,
                        'records' => count($customers)
                    )
                ]
            );

            return $this->setResponseFormat("json")->respond($response, 200);
        } else {
            $response = array(
                'status' => 'error',
                'message' => lang('Customer.Customer.error_list')
            );
            return $this->setResponseFormat("json")->respond($response, 201);
        }
    }

    public function getCustomer()
    {
        $response = array();
        $this->validatePermission('view_customer');    // Check permission

        if (!$this->isEmployee()) {
            $response = array(
                'status' => 'error',
                'message' => lang('Common.error_employee_login')
            );

            return $this->setResponseFormat("json")->respond($response, 201);
        }

        $modelCustomer = new CustomerModel(); // Load model

        $customer_id = $this->request->getVar('customer_id');
        $customer = $modelCustomer->getCustomer($customer_id);
        // print_r($customer);
        if ($customer) {
            $response['status'] = 'success';
            $response['message'] = lang('Customer.Customer.success_detail');
            $response['customer'] = [
                'type' => $this->userType,
                'data' => $customer
            ];
            return $this->setResponseFormat("json")->respond($response, 200);
        } else {
            $response['status'] = 'error';
            $response['message'] = lang('Customer.Customer.error_detail');
            return $this->setResponseFormat("json")->respond($response, 201);
        }
    }

    protected function validatePermission($permission_name)
    {
        $permission = AuthUser::checkPermission($permission_name);
        if (!$permission) {
            $response = array(
                'status' => 'error',
                'message' => lang('Common.error_permission')
            );

            return $this->setResponseFormat("json")->respond($response, 201);
        }
    }

    protected function isEmployee()
    {
        $this->userId = AuthUser::getId();
        if (AuthEmployee::isValid($this->empType)) {
            $this->employeeId = AuthEmployee::getId();
        }
        return $this->employeeId;
    }
}
