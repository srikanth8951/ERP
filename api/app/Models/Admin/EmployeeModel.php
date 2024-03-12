<?php

namespace App\Models\Admin;

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

        if (isset($data['region_id'])) {
            $condition['emp.region_id'] = $data['region_id'];
        }

        if (isset($data['branch_id'])) {
            $condition['emp.branch_id'] = $data['branch_id'];
        }

        if (isset($data['area_id'])) {
            $condition['emp.area_id'] = $data['area_id'];
        }

        if (isset($data['user_type'])) {
            $condition['emp.user_type'] = (int)$data['user_type'];
        }

        if (isset($data['removed'])) {
            $condition['emp.removed'] = (int)$data['removed'];
        }

        if (isset($data['is_exist'])) {
            $condition['emp.is_exist'] = (int)$data['is_exist'];
        }

        $builder = $this->cdb->table('employee AS emp');
        $builder->join('region rgn', 'rgn.region_id = emp.region_id', 'left');
        $builder->join('branch b', 'b.branch_id = emp.branch_id', 'left');
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
                    ->orLike('emp.mobile', $searchData)
                    ->orLike('emp.email', $searchData)
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
            $condition['emp.region_id'] = $data['region_id'];
        }

        if (isset($data['branch_id'])) {
            $condition['emp.branch_id'] = $data['branch_id'];
        }

        if (isset($data['area_id'])) {
            $condition['emp.area_id'] = $data['area_id'];
        }

        if (isset($data['removed'])) {
            $condition['emp.removed'] = (int)$data['removed'];
        }

        if (isset($data['is_exist'])) {
            $condition['emp.is_exist'] = (int)$data['is_exist'];
        }

        if (isset($data['cam_id'])) {
            $condition['emp.reporting_manager'] = (int)$data['cam_id'];
        }


        $builder = $this->cdb->table('employee emp');

        $builder->select('emp.*, rgn.name as region_name, b.name as branch_name, a.name as area_name');
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
                    ->orLike('emp.mobile', $searchData)
                    ->orLike('emp.email', $searchData)
                    ->orLike('a.name', $searchData)
                    ->orLike('rgn.name', $searchData)
                    ->groupEnd();
            }
        }

        if (isset($data['except'])) {
            $builder->whereNotIn('emp.employee_id', [$data['except']]);
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
        $builder->join('countries c', 'c.country_id = emp.country', 'left');
        $builder->join('states s', 's.state_id = emp.state', 'left');
        $builder->join('cities cs', 'cs.city_id = emp.city', 'left');
        $builder->join('user u', 'u.user_id = emp.user_id', 'left');

        $builder->select('emp.*, u.username AS username, c.name as country_name, s.name as state_name, cs.name as city_name');
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

        if (isset($data['region_id'])) {
            $condition['emp.region_id'] = (int)$data['region_id'];
        }

        if (isset($data['branch_id'])) {
            $condition['emp.branch_id'] = (int)$data['branch_id'];
        }

        if (isset($data['status'])) {
            $condition['emp.status'] = (int)$data['status'];
        }

        if (isset($data['removed'])) {
            $condition['emp.removed'] = (int)$data['removed'];
        }

        $builder = $this->cdb->table('employee AS emp');
        $builder->join('user u', 'u.user_id = emp.user_id');
        $builder->join('countries c', 'c.country_id = emp.country', 'left');
        $builder->join('states s', 's.state_id = emp.state', 'left');
        $builder->join('cities cs', 'cs.city_id = emp.city', 'left');

        $builder->select('emp.*, u.username AS username, c.name as country_name, s.name as state_name, cs.name as city_name');
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

    public function getEmployeeByTypes($employee_type, $data = [])
    {
        $condition = array(
            'emp.user_type' => (int)$employee_type
        );

        if (isset($data['region_id'])) {
            $condition['emp.region_id'] = (int)$data['region_id'];
        }

        if (isset($data['branch_id'])) {
            $condition['emp.branch_id'] = (int)$data['branch_id'];
        }

        if (isset($data['status'])) {
            $condition['emp.status'] = (int)$data['status'];
        }

        if (isset($data['removed'])) {
            $condition['emp.removed'] = (int)$data['removed'];
        }

        $builder = $this->cdb->table('employee AS emp');
        //  $builder->join('employee emp2', 'emp2.branch_id =  b.branch_id', 'left');

        $builder->select('CONCAT(emp.first_name, " ", emp.last_name) as name, emp.email as email');

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

    public function getEmployeesByTypes($employee_type, $data = [])
    {
        $condition = array(
            'emp.user_type' => (int)$employee_type
        );

        if (isset($data['region_id'])) {
            $condition['emp.region_id'] = (int)$data['region_id'];
        }

        if (isset($data['branch_id'])) {
            $condition['emp.branch_id'] = (int)$data['branch_id'];
        }

        if (isset($data['status'])) {
            $condition['emp.status'] = (int)$data['status'];
        }

        if (isset($data['removed'])) {
            $condition['emp.removed'] = (int)$data['removed'];
        }

        $builder = $this->cdb->table('employee AS emp');
        //  $builder->join('employee emp2', 'emp2.branch_id =  b.branch_id', 'left');

        $builder->select('CONCAT(emp.first_name, " ", emp.last_name) as name, emp.email as email');

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

    public function getEmployeeByName($employee_name, $data = [])
    {
        $condition = array();

        if (isset($data['removed'])) {
            $condition['removed'] = $data['removed'];
        }

        if (isset($data['user_type'])) {
            $condition['user_type'] = $data['user_type'];
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
            ->orWhere('username', $data['username'], 'before')
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
        
        $builder->select('emp.*, u.username AS username');        
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

    public function addEmployee($data)
    {

        $this->cdb->transStart();
        $insert_data = array(
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'] ?? '',
            'mobile' => $data['mobile'],
            'address' => $data['address'] ?? '',
            'country' => $data['country_id'] ?? 0,
            'state' => $data['state_id'] ?? 0,
            'city' => $data['city_id'] ?? 0,
            'department_id' => $data['department_id'] ?? 0,
            'designation_id' => $data['designation_id'] ?? 0,
            'region_id' => $data['region_id'] ?? 0,
            'branch_id' => $data['branch_id'] ?? 0,
            'area_id' => $data['area_id'] ?? 0,
            'vendor_id' => $data['vendor_id'] ?? 0,
            'pincode' => $data['pincode'] ?? '',
            'reporting_manager' => $data['reporting_manager'] ?? 0,
            'joining_date' => $data['joining_date'] ?? '',
            'employee_number' => $data['emp_id'] ?? '',
            // 'leaving_date' => $data['leaving_date'] ?? '',    
            'work_expertise' => $data['work_expertise'] ?? 0,
            'status' => $data['work_status'] ?? 1,
            'user_type' => $data['user_type'],
            'is_exist' => $data['is_exist'] ?? 0,
            'created_datetime' => date('Y-m-d H:i:s')
        );
        $this->cdb->table('employee')->insert($insert_data);
        $empInsertId = $this->cdb->insertID();


        if ($data['email'] == 0 && $data['mobile'] == 0 && $data['first_name'] == 0) {
            $this->deleteEmployee($empInsertId);
        }

        // Add user
        if ($data['email'] && $data['user_type'] && $data['password']) {
            $password = password_hash($data['password'], PASSWORD_BCRYPT);
            $user_type = $data['user_type'];
            $user_data = array(
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'username' => $data['username'],
                'password' => $password,
                'email' => $data['email'] ?? '',
                'mobile' => $data['mobile'],
                //'permission' => $user_type['permission'],
                'user_type' => $user_type,
                'status' => 1
            );

            $this->cdb->table('user')->insert($user_data);
            $userInsertID = $this->cdb->insertID();
            if ($userInsertID) {
                $this->setEmployeeUserId($empInsertId, $userInsertID);
            }
        }

        $this->cdb->transComplete();
        if ($this->cdb->transStatus() === false) {
            return false;
        } else {
            return $empInsertId;
        }
    }

    public function editEmployee($employee_id, $data)
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
            'department_id' => $data['department_id'] ?? 0,
            'designation_id' => $data['designation_id'] ?? 0,
            'region_id' => $data['region_id'] ?? 0,
            'branch_id' => $data['branch_id'] ?? 0,
            'area_id' => $data['area_id'] ?? 0,
            'vendor_id' => $data['vendor_id'] ?? 0,
            'pincode' => $data['pincode'] ?? '',
            'reporting_manager' => $data['reporting_manager'] ?? 0,
            'joining_date' => $data['joining_date'] ?? '',
            'employee_number' => $data['emp_id'] ?? '',
            // 'leaving_date' => $data['leaving_date'] ?? '',    
            'work_expertise' => $data['work_expertise'] ?? 0,
            'status' => $data['work_status'] ?? 1,
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
                if ($data['username'] && $data['user_type']) {
                    $user_type = $data['user_type'];
                    $user_data = array(
                        'first_name' => $data['first_name'],
                        'last_name' => $data['last_name'],
                        'username' => $data['username'],
                        'email' => $data['email'] ?? '',
                        'mobile' => $data['mobile'],
                        //'permission' => $user_type['permission'],
                        'user_type' => $user_type,
                        'status' => 1
                    );

                    $this->cdb->table('user')->where([
                        'user_id' => $employee->user_id
                    ])->update($user_data);
                }

                if ($data['password']) {
                    $hashed_password = password_hash($data['password'], PASSWORD_BCRYPT);
                    $reset_data = array(
                        'password' => $hashed_password,
                        'updated_datetime' => date('Y-m-d H:i:s')
                    );
                    $this->cdb->table('user')->where([
                        'user_id' => $employee->user_id
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

    public function setEmployeeUserId($employee_id, $user_id)
    {
        $condition = array(
            'employee_id' => $employee_id
        );
        $update_data = array(
            'user_id' => $user_id,
            'updated_datetime' => date('Y-m-d H:i:s')
        );
        $this->cdb->transStart();
        $builder = $this->cdb->table('employee');
        $builder->where($condition);
        $result = $builder->update($update_data);

        $this->cdb->transComplete();

        if ($this->cdb->transStatus() === false) {
            return false;
        } else {
            return $result;
        }
    }

    public function removeEmployee($employee_id, $user_id)
    {
        $this->cdb->transStart();
        $condition = array(
            'employee_id' => $employee_id
        );
        $condition1 = array(
            'user_id' => $user_id
        );
        $update_data = array(
            'removed' => 1,
            'status' => 0,
            'updated_datetime' => date('Y-m-d H:i:s')
        );
        $builder = $this->cdb->table('employee');
        $builder->where($condition);
        $result = $builder->update($update_data);

        //remove employee taged to user table
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

    public function setEmployeeStatus($employee_id, $status, $user_id)
    {
        $this->cdb->transStart();
        $condition = array(
            'employee_id' => $employee_id
        );
        $condition1 = array(
            'user_id' => $user_id
        );
        $update_data = array(
            'status' => $status,
            'updated_datetime' => date('Y-m-d H:i:s')
        );
        $builder = $this->cdb->table('employee');
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

    public function deleteEmployee($employee_id)
    {
        $this->cdb->transStart();
        $condition = array(
            'employee_id' => $employee_id
        );
        $builder = $this->cdb->table('employee');
        $builder->where($condition);
        $result = $builder->delete();
        $this->cdb->transComplete();

        if ($this->cdb->transStatus() === false) {
            return false;
        } else {
            return $result;
        }
    }

    function getEmployeeExist($data = [])
    {
        $condition = array();

        $condition = array(
            'removed' => 0,
            'status' => 1,
            'is_exist' => $data['is_exist'],
            'email' => $data['email']
        );
        $builder = $this->cdb->table('employee');
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

    function getEmployeeExists($data = [])
    {
        $condition = array();

        $condition = array(
            'removed' => 0,
            'status' => 1,
            'is_exist' => $data['is_exist'],
            'email' => $data['email'],
        );
        $builder = $this->cdb->table('employee');
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

    public function updateEmployee($id, $data = [])
    {
        $condition = array(
            'employee_id' => $id,
            'email' => $data['email'],
        );
        $update_data = array(
            'is_exist' => 2,
            'updated_datetime' => date('Y-m-d H:i:s')
        );
        $builder = $this->cdb->table('employee');
        $builder->where($condition);
        $result = $builder->update($update_data);
        $this->cdb->transComplete();

        if ($this->cdb->transStatus() === false) {
            return false;
        } else {
            return $result;
        }
    }

    public function updateEmployeeDetails($val)
    {
        $condition = array(
            'email' => $val,
            'is_exist' => 3,
            'status' => 0,
            'removed' => 0,
        );
        $update_data = array(
            'is_exist' => 4, //newly existing Employee in db
            'status' => 2,
        );
        $this->cdb->transStart();
        $builder = $this->cdb->table('employee');
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

        $builder = $this->cdb->table('employee');
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
        $builder = $this->cdb->table('employee');
        $builder->where($condition);
        $result = $builder->update($update_data);
        $this->cdb->transComplete();

        if ($this->cdb->transStatus() === false) {
            return false;
        } else {
            return true;
        }
    }

    public function updateUploadEmployee()
    {
        $condition = array(
            'is_exist' => 3,
            //'status' => 0,
        );

        $update_data = array(
            'is_exist' => 0, //already existing Employee in db
            'status' => 1,
            'updated_datetime' => date('Y-m-d H:i:s')
        );

        $this->cdb->transStart();
        $builder = $this->cdb->table('employee');
        $builder->where($condition);
        $result = $builder->update($update_data);
        $this->cdb->transComplete();

        //already existing employee in db
        $condition1 = array(
            'is_exist' => 2,
            'status' => 1,
            'removed' => 0
        );
        $update_data1 = array(
            'is_exist' => 0
        );

        $this->cdb->transStart();
        $builder = $this->cdb->table('employee');
        $builder->where($condition1);
        $builder->update($update_data1);
        $this->cdb->transComplete();

        //delete duplicate entries
        $this->cdb->transStart();
        $builder = $this->cdb->table('employee');
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

    public function getEmployees23($data = [])
    {
        $condition = array();

        if (isset($data['user_type'])) {
            $condition['emp.user_type'] = (int)$data['user_type'];
        }

        if (isset($data['removed'])) {
            $condition['emp.removed'] = (int)$data['removed'];
        }


        $builder = $this->cdb->table('employee emp');

        $builder->select('emp.*,c.name as country');
        $builder->join('countries c', 'emp.country = c.country_id', 'left');
        $builder->whereNotIn('emp.is_exist', array('0'));
        $builder->whereNotIn('emp.status', array('1'));
        $builder->where($condition);

        $query = $builder->get();

        if ($query->getNumRows() > 0) {
            return $query->getResult();
        } else {
            return 0;
        }
    }

    public function getEmployeeDetails($employee_id, $data = [])
    {
        $condition = array(
            'emp.employee_id' => (int)$employee_id
        );


        $builder = $this->cdb->table('employee AS emp');
        $builder->join('region rgn', 'rgn.region_id = emp.region_id', 'left');
        $builder->join('area a', 'a.area_id = emp.area_id', 'left');
        $builder->join('branch b', 'b.branch_id = emp.branch_id', 'left');

        $builder->select('
        rgn.name as region_name, 
        rgn.region_id as region_id,
        a.area_id as area_id, 
        a.name as area_name,
        b.branch_id as branch_id,
        emp.email as engineer_email,
        emp.mobile,CONCAT(emp.first_name, " ", emp.last_name) as engineer_name,');

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
}
