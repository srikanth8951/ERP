<?php

namespace App\Models\Employee;

use Config\Services;
use Config\Database;

class EmployeeModel
{
    private $cdb;

    public function __construct()
    {
        $this->cdb = Database::connect('default');
    }

    public function getTotalEmployees($data = [])
    {
        $condition = array();

        if (isset($data['status'])) {
            $condition['emp.status'] = (int)$data['status'];
        }

        if (isset($data['designation_id'])) {
            $condition['emp.designation_id'] = (int)$data['designation_id'];
        }

        if (isset($data['user_type'])) {
            $condition['emp.user_type'] = (int)$data['user_type'];
        }

        if (isset($data['removed'])) {
            $condition['emp.removed'] = (int)$data['removed'];
        }

        $builder = $this->cdb->table('employee AS emp');
        $builder->join('region rgn', 'rgn.region_id = emp.region_id', 'left');
        $builder->join('area a', 'a.area_id = emp.area_id', 'left');
        $builder->select('COUNT(*) AS total');

        if ($condition) {
            $builder->where($condition);
        }

        // Search
        $searchData = $data['search'] ?? '';
        if ($searchData) {
            if ($data['search']) {
                $builder->groupStart()
                    ->like('CONCAT(emp.first_name, " ", emp.last_name)', $searchData)
                    ->orLike('a.name', $searchData)
                    ->orLike('rgn.name', $searchData)
                    ->groupEnd();
            }
        }

        if (isset($data['except'])) {
            $builder->whereNotIn('emp.employee_id', $data['except']);
        }

        $query = $builder->get();

        if ($query->getNumRows() > 0) {
            return $query->getRow()->total;
        } else {
            return 0;
        }
    }

    public function getEmployees($data = [])
    {
        $condition = array();

        if (isset($data['status'])) {
            $condition['emp.status'] = (int)$data['status'];
        }

        if (isset($data['designation_id'])) {
            $condition['emp.designation_id'] = (int)$data['designation_id'];
        }

        if (isset($data['user_type'])) {
            $condition['emp.user_type'] = (int)$data['user_type'];
        }

        if (isset($data['region_id'])) {
            $condition['emp.region_id'] = (int)$data['region_id'];
        }

        if (isset($data['branch_id'])) {
            $condition['emp.branch_id'] = (int)$data['branch_id'];
        }

        if (isset($data['area_id'])) {
            $condition['emp.area_id'] = (int)$data['area_id'];
        }

        if (isset($data['cam_id'])) {
            $condition['emp.reporting_manager'] = (int)$data['cam_id'];
        }

        if (isset($data['removed'])) {
            $condition['emp.removed'] = (int)$data['removed'];
        }

        $builder = $this->cdb->table('employee emp');

        $builder->select('emp.*, rgn.name as region_name, a.name as area_name');
        $builder->join('region rgn', 'rgn.region_id = emp.region_id', 'left');
        $builder->join('branch b', 'b.branch_id = emp.branch_id', 'left');
        $builder->join('area a', 'a.area_id = emp.area_id', 'left');
        if ($condition) {
            $builder->where($condition);
        }

        // Search
        $searchData = $data['search'] ?? '';
        if ($searchData) {
            if ($data['search']) {
                $builder->groupStart()
                    ->like('CONCAT(emp.first_name, " ", emp.last_name)', $searchData)
                    ->orLike('a.name', $searchData)
                    ->orLike('rgn.name', $searchData)
                    ->groupEnd();
            }
        }

        if (isset($data['except'])) {
            $builder->whereNotIn('emp.employee_id', $data['except']);
        }

        if (isset($data['is_exist'])) {
            $builder->where('emp.is_exist', 0);
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
            $sort = 'emp.employee_id';
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

    public function getEmployee($employee_id, $data = [])
    {
        $condition = array(
            'emp.employee_id' => (int)$employee_id
        );

        if (isset($data['status'])) {
            $condition['emp.status'] = (int)$data['status'];
        }

        $builder = $this->cdb->table('employee AS emp');
        $builder->join('user u', 'u.user_id = emp.user_id');

        $builder->select('emp.*, u.username AS username');
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

    public function getEmployeeByType($employee_type, $data = [])
    {
        $condition = array(
            'emp.user_type' => (int)$employee_type
        );

        if (isset($data['status'])) {
            $condition['emp.status'] = (int)$data['status'];
        }

        if (isset($data['removed'])) {
            $condition['emp.removed'] = (int)$data['removed'];
        }

        $builder = $this->cdb->table('employee AS emp');
        $builder->join('user u', 'u.user_id = emp.user_id');

        $builder->select('emp.*, u.username AS username');
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

    public function getEmployeeByName($employee_name, $data = [])
    {
        $condition = array();

        if (isset($data['removed'])) {
            $condition['removed'] = $data['removed'];
        }

        $builder = $this->cdb->table('employee');
        $builder->select('*');

        if ($condition) {
            $builder->where($condition);
        }

        // Check name
        $check_name = 'REPLACE(LOWER(name), " ", "_") = REPLACE(LOWER("' . $employee_name . '")," ", "_")';
        $builder->where($check_name);

        $query = $builder->get();

        if ($query->getNumRows() > 0) {
            return $query->getRow();
        } else {
            return [];
        }
    }

    public function getEmployeeByEmail($email, $data = array())
    {
        $condition['email'] = $email;
        if (isset($data['status'])) {
            $condition['status'] = (int)$data['status'];
        }

        if (isset($data['removed'])) {
            $condition['removed'] = (int)$data['removed'];
        }

        $builder = $this->cdb->table('employee');
        $builder->where($condition);

        if (isset($data['except'])) {
            if ($data['except']) {
                $builder->whereNotIn('employee_id', $data['except']);
            }
        }

        $query = $builder->get();

        if ($query->getNumRows() > 0) {
            return $query->getRow();
        } else {
            return false;
        }
    }

    public function editProfile($employee_id, $data)
    {
        $condition = array(
            'employee_id' => $employee_id
        );

        $update_data = array(
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'] ?? '',
            'mobile' => $data['mobile'],
            'address' => $data['address'] ?? '',
            'country' => $data['country_id'] ?? 0,
            'state' => $data['state_id'] ?? 0,
            'city' => $data['city_id'] ?? 0,
            'pincode' => $data['pincode'] ?? '',
            'updated_datetime' => date('Y-m-d H:i:s')
        );

        $this->cdb->transStart();
        $builder = $this->cdb->table('employee');
        $builder->where($condition);
        $result = $builder->update($update_data);

        // Update user
        $employee = $this->getEmployee($employee_id);
        if ($employee) {
            if ($employee->user_id != 0) {
                if ($data['user_type']) {
                    $user_type = $data['user_type'];
                    $user_data = array(
                        'first_name' => $data['first_name'],
                        'last_name' => $data['last_name'],
                        'email' => $data['email'] ?? '',
                        'mobile' => $data['mobile'],
                        'user_type' => $user_type,
                        'updated_datetime' => date('Y-m-d H:i:s')
                    );

                    if (isset($data['image']) && $data['image']) {
                        $user_data['image'] = $data['image'];
                    }

                    $this->cdb->table('user')->where([
                        'user_id' => $employee->user_id
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

    public function getEmployeeByID($user_id, $data = [])
    {
        $condition = array(
            'u.user_id' => (int)$user_id
        );


        if (isset($data['status'])) {
            $condition['emp.status'] = (int)$data['status'];
        }

        if (isset($data['removed'])) {
            $condition['emp.removed'] = (int)$data['removed'];
        }

        $builder = $this->cdb->table('employee AS emp');
        $builder->join('user u', 'u.user_id = emp.user_id');

        $builder->select('emp.*, u.username AS username, u.image as user_image');
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
            ->where('email', $data['email'], 'before')
            // ->orWhere('username', $data['username'], 'before')
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
