<?php

namespace App\Models\Admin;

use Config\Services;
use Config\Database;
use CodeIgniter\Events\Events;
use App\Libraries\AppJobManager;

class ContractJobModel
{
    private $cdb;
    private $dbPrefix;

    public function __construct()
    {
        $this->cdb = Database::connect('default');
        $this->dbPrefix = $this->cdb->getPrefix();
        helper('contract_job');
    }

    public function getTotalContractJobs($data = [])
    {
        $condition = array();

        if (isset($data['status'])) {
            $condition['cj.status'] = (int)$data['status'];
        }

        if (isset($data['sector_id'])) {
            $condition['cj.sector'] = (int)$data['sector_id'];
        }

        if (isset($data['removed'])) {
            $condition['cj.removed'] = (int)$data['removed'];
        }

        if (isset($data['contract_status_id'])) {
            $condition['cj.contract_status_id'] = (int)$data['contract_status_id'];
        }

        $builder = $this->cdb->table('contract_job cj');
        $builder->join('customer c', 'c.customer_id = cj.customer_id');
        $builder->join('contract_nature cn', 'cn.contract_nature_id = cj.contract_nature_id', 'left');
        $builder->join('contract_type ct', 'ct.contract_type_id = cj.contract_type_id', 'left');
        $builder->join('contract_status cs', 'cs.contract_status_id = cj.contract_status_id', 'left');
        $builder->select('COUNT(*) AS total');

        if ($condition) {
            $builder->where($condition);
        }

        if (isset($data['contract_status'])) {
            if (is_array($data['contract_status'])) {
                $builder->whereIn('cj.contract_status_id', $data['contract_status']);
            } else {
                $builder->where('cj.contract_status_id', (int)$data['contract_status']);
            }
        }

        if (isset($data['contract_type_id'])) {
            if (is_array($data['contract_type_id'])) {
                $builder->whereIn('cj.contract_type_id', $data['contract_type_id']);
            } else {
                $builder->where('cj.contract_type_id', (int)$data['contract_type_id']);
            }
        }

        // Search
        $searchData = $data['search'] ?? '';
        if ($searchData) {
            if ($data['search']) {
                $builder->groupStart()
                    ->like('c.company_name', $searchData)
                    ->orLike('cj.job_number', $searchData)
                    ->orLike('cj.job_title', $searchData)
                    ->orLike('cj.sap_job_number', $searchData)
                    ->orLike('cn.name', $searchData)
                    ->orLike('ct.name', $searchData)
                    ->orLike('cs.name', $searchData)
                    ->groupEnd();
            }
        }

        if (isset($data['contract_job_id'])) {
            $builder->whereIn('cj.contract_job_id', $data['contract_job_id']);
        }

        if (isset($data['except'])) {
            $builder->whereNotIn('cj.contract_job_id', $data['except']);
        }

        $query = $builder->get();

        if ($query->getNumRows() > 0) {
            return $query->getRow()->total;
        } else {
            return 0;
        }
    }

    public function getContractJobs($data = [])
    {
        $condition = array();

        if (isset($data['status'])) {
            $condition['cj.status'] = (int)$data['status'];
        }

        if (isset($data['sector_id'])) {
            $condition['cj.sector'] = (int)$data['sector_id'];
        }

        if (isset($data['removed'])) {
            $condition['cj.removed'] = (int)$data['removed'];
        }

        if (isset($data['contract_status_id'])) {
            $condition['cj.contract_status_id'] = (int)$data['contract_status_id'];
        }

        $builder = $this->cdb->table('contract_job cj');
        $builder->join('customer c', 'c.customer_id = cj.customer_id');
        $builder->join('contract_nature cn', 'cn.contract_nature_id = cj.contract_nature_id', 'left');
        $builder->join('contract_type ct', 'ct.contract_type_id = cj.contract_type_id', 'left');
        $builder->join('contract_status cs', 'cs.contract_status_id = cj.contract_status_id', 'left');

        $builder->select('cj.*, cn.name AS contract_nature_name, ct.name AS contract_type_name, cs.name AS contract_status_name');
        $builder->select('(SELECT (CASE WHEN cj.contract_job_id IS NULL THEN 0 ELSE 1 END) AS has_parent FROM ' . $this->dbPrefix . 'contract_job cj1 WHERE cj1.parent = cj.contract_job_id AND cj1.removed = 0) AS is_parent');
        if ($condition) {
            $builder->where($condition);
        }

        if (isset($data['contract_status'])) {
            if (is_array($data['contract_status'])) {
                $builder->whereIn('cj.contract_status_id', $data['contract_status']);
            } else {
                $builder->where('cj.contract_status_id', (int)$data['contract_status']);
            }
        }

        if (isset($data['contract_type_id'])) {
            if (is_array($data['contract_type_id'])) {
                $builder->whereIn('cj.contract_type_id', $data['contract_type_id']);
            } else {
                $builder->where('cj.contract_type_id', (int)$data['contract_type_id']);
            }
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

        if (isset($data['contract_job_id'])) {
            $builder->whereIn('cj.contract_job_id', $data['contract_job_id']);
        }

        if (isset($data['except'])) {
            $builder->whereNotIn('cj.contract_job_id', [$data['except']]);
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
            return [];
        }
    }

    public function getContractJob($contract_job_id, $data = [])
    {
        $condition = array(
            'cj.contract_job_id' => (int)$contract_job_id
        );

        if (isset($data['status'])) {
            $condition['cj.status'] = (int)$data['status'];
        }

        $builder = $this->cdb->table('contract_job cj');
        $builder->join('contract_nature cn', 'cn.contract_nature_id = cj.contract_nature_id', 'left');
        $builder->join('contract_type ct', 'ct.contract_type_id = cj.contract_type_id', 'left');
        $builder->join('contract_status cs', 'cs.contract_status_id = cj.contract_status_id', 'left');
        $builder->join('employee emp1', 'emp1.employee_id = cj.customer_account_manager_id', 'left');
        $builder->join('employee emp2', 'emp2.employee_id = cj.engineer_id', 'left');
        $builder->join('customer c', 'c.customer_id = cj.customer_id', 'left');

        $builder->select('cj.*,c.*, (SELECT CONCAT(cc.code, " - ", cc.symbol) FROM ' . $this->dbPrefix . 'currency cc WHERE cc.currency_id = cj.contract_currency_id) as contract_currency_name,
         cn.name AS contract_nature_name, ct.name AS contract_type_name, cs.name AS contract_status_name,
          CONCAT(emp1.first_name, " ", emp1.last_name) as customer_account_manager_name, 
          CONCAT(emp2.first_name, " ", emp2.last_name) as engineer_name');
        $builder->select('(SELECT (CASE WHEN cj.contract_job_id IS NULL THEN 0 ELSE 1 END) is_parent FROM ' . $this->dbPrefix . 'contract_job cj1 WHERE cj1.parent = cj.contract_job_id AND cj1.removed = 0) AS is_parent');
        if ($condition) {
            $builder->where($condition);
        }

        $query = $builder->get();
        // print_r($this->cdb->getLastQuery());
        if ($query->getNumRows() > 0) {
            // print_r($query->getRow());
            return $query->getRow();

        } else {
            return 0;
        }
    }

    public function getJobByType($data = [])
    {
        $condition = array();

        if (isset($data['status'])) {
            $condition['cj.status'] = (int)$data['status'];
        }

        if (isset($data['removed'])) {
            $condition['cj.removed'] = (int)$data['removed'];
        }

        $builder = $this->cdb->table('contract_job AS cj');
        $builder->join('user u', 'u.user_id =  cj.user_id');

        $builder->select('cj.*, u.username AS username');
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

    public function getContractJobByJno($job_number, $data = [])
    {
        $condition = array();

        if (isset($data['removed'])) {
            $condition['removed'] = $data['removed'];
        }

        $builder = $this->cdb->table('contract_job');
        $builder->select('*');

        if ($condition) {
            $builder->where($condition);
        }

        // Check name
        $check_name = 'REPLACE(LOWER(job_number), " ", "_") = REPLACE(LOWER("' . $job_number . '")," ", "_")';
        $builder->where($check_name);

        $query = $builder->get();

        if ($query->getNumRows() > 0) {
            return $query->getRow();
        } else {
            return [];
        }
    }

    public function getJobByEmail($email, $data = array())
    {
        $condition['email'] = $email;
        if (isset($data['status'])) {
            $condition['status'] = (int)$data['status'];
        }

        if (isset($data['removed'])) {
            $condition['removed'] = (int)$data['removed'];
        }

        $builder = $this->cdb->table('contract_job');
        $builder->where($condition);

        if (isset($data['except'])) {
            if ($data['except']) {
                $builder->whereNotIn('contract_job_id', $data['except']);
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

    public function getCustomer($customer_id, $data = [])
    {
        $condition = array(
            'c.customer_id' => (int)$customer_id
        );

        if (isset($data['status'])) {
            $condition['c.status'] = (int)$data['status'];
        }

        $builder = $this->cdb->table('customer AS c');
        $builder->join('user u', 'u.user_id =  c.user_id');

        $builder->select('c.*, u.username as username');
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

    public function addContractJob($data)
    {
        
        $this->cdb->transStart();
        $period_fromdate = $data['period_fromdate'] ?? '';
        $period_todate = $data['period_todate'] ?? '';
        // Add contract job 
        $job_data = array(
            'created_user' => $data['created_user'],
            'job_title' => $data['job_title'],
            'sap_job_number' => $data['sap_job_number'],
            'contract_nature_id' => $data['contract_nature'] ?? '',
            'contract_type_id' => $data['contract_type'],
            'deployed_people_number' => $data['deployed_people_number'],
            'purchase_order_number' => $data['po_number'],
            'contract_currency_id' => $data['contract_currency'] ?? '',
            'contract_gst_value' => $data['contract_gst_value'] ?? '',
            'contract_value' => $data['contract_value'] ?? '',
            'total_contract_value' => $data['contract_value_total'] ?? '',
            'expected_gross_margin' => $data['expected_gross_margin'] ?? '',
            'customer_account_manager_id' => $data['customer_account_manager'] ?? 0,
            'engineer_id' => $data['engineer'] ?? 0,
            'contract_status_id' => $data['contract_status'] ?? 0,
            'period' => $data['period'] ?? '',
            'period_fromdate' => $period_fromdate ? dbdate_format($period_fromdate) : '',
            'period_todate' => $period_todate ? dbdate_format($period_todate) : '',
            'ppm_frequency' => $data['ppm_frequency'] ?? '',
            'geolocation_lattitude' => $data['job_location_lattitude'] ?? '',
            'geolocation_longitude' => $data['job_location_longitude'] ?? '',
            'geolocation_range' => $data['job_location_range'] ?? 0,
            'process_type' => $data['type'] ?? 'new',
            'status' => 1,
            'created_datetime' => date('Y-m-d H:i:s')
        );

        $this->cdb->table('contract_job')->insert($job_data);
        $jobInsertId = $this->cdb->insertID();

        // Find and Update job parent
        $jobParentPath = '';
        $paths = [];
        array_push($paths, $jobInsertId);
        $jobParentPath = implode(',', $paths);
        $this->cdb->table('contract_job')->where('contract_job_id', $jobInsertId)->update([
            'parent' => 0,
            'parent_path' => $jobParentPath,
            'updated_datetime' => date('Y-m-d H:i:s')
        ]);

        // Get & Update job number
        $code = 'config_contract_job';
        $keyword = 'job_' . preg_replace('/[\s-]/', '_', strtolower($data['job_prefix'])) . '_sno';
        $setting = $this->cdb->table('setting')->where([
            'keyword' => $keyword,
            'code' => $code
        ])->get()->getRow();
        if ($setting) {
            $sequence_number = (int)$setting->value;
            $sequence_number += 1;
            $this->cdb->table('setting')
                ->where([
                    'setting_id' => $setting->setting_id
                ])->update([
                    'value' => $sequence_number,
                    'is_serialized' => 0,
                    'updated_datetime' => date('Y-m-d H:i:s')
                ]);
        } else {
            $sequence_number = 1;
            $this->cdb->table('setting')
                ->insert([
                    'code' => $code,
                    'keyword' => $keyword,
                    'value' => $sequence_number,
                    'is_serialized' => 0,
                    'created_datetime' => date('Y-m-d H:i:s')
                ]);
        }
        $jobSequenceNumber = $sequence_number;
        $jobNumber = formJobNumber($jobSequenceNumber, $data['job_prefix']);
        $this->cdb->table('contract_job')->where('contract_job_id', $jobInsertId)->update([
            'job_number' => $jobNumber,
            'updated_datetime' => date('Y-m-d H:i:s')
        ]);

        // Add customer
        if ($data['customer_type'] == 'exist') {
            $cusInsertId = $data['customer_id'];
        } else {
            $insert_data = array(
                'company_name' => $data['customer_company_name'],
                'sector' => $data['customer_sector'] ?? 0,
                'billing_address' => $data['customer_billing_address'] ?? '',
                'billing_address_contact_name' => $data['customer_billing_address_contact_name'],
                'billing_address_email' => $data['customer_billing_address_email'] ?? '',
                'site_address' => $data['customer_site_address'] ?? '',
                'billing_address_mobile' => $data['customer_billing_address_mobile'],
                'billing_address_country' => $data['customer_billing_address_country'] ?? 0,
                'billing_address_state' => $data['customer_billing_address_state'] ?? 0,
                'billing_address_city' => $data['customer_billing_address_city'] ?? 0,
                'billing_address_pincode' => $data['customer_billing_address_pincode'] ?? '',
                'site_address_contact_name' => $data['customer_site_address_contact_name'],
                'site_address_email' => $data['customer_site_address_email'] ?? '',
                'site_address_mobile' => $data['customer_site_address_mobile'],
                'site_address_country' => $data['customer_site_address_country'] ?? 0,
                'site_address_state' => $data['customer_site_address_state'] ?? 0,
                'site_address_city' => $data['customer_site_address_city'] ?? 0,
                'site_address_pincode' => $data['customer_site_address_pincode'] ?? '',
                'website' => $data['customer_website'] ?? '',
                'gst_number' => $data['customer_gst_number'] ?? '',
                'payment_term' => $data['customer_payment_term'] ?? '',
                'pan_number' => $data['customer_pan_number'] ?? '',
                'status' => $data['customer_status'] ?? 1,
                'created_datetime' => date('Y-m-d H:i:s')
            );
            $this->cdb->table('customer')->insert($insert_data);
            $cusInsertId = $this->cdb->insertID();

            // Add user
            if ($data['user_type'] && $data['customer_password']) {
                $password = password_hash($data['customer_password'], PASSWORD_BCRYPT);
                $user_type = $data['user_type'];
                $user_data = array(
                    'first_name' => $data['customer_company_name'] ?? '',
                    'last_name' => $data['last_name'] ?? '',
                    'username' => $data['customer_username'],
                    'password' => $password,
                    // 'email' => $data['customer_billing_address_email'] ?? '',
                    'mobile' => $data['customer_billing_address_mobile'],
                    'user_type' => $user_type,
                    'status' => 1
                );

                $this->cdb->table('user')->insert($user_data);
                $userInsertID = $this->cdb->insertID();
                if ($userInsertID) {
                    $this->setCustomerUserId($cusInsertId, $userInsertID);
                }
            }
        }

        $this->cdb->table('contract_job')->where('contract_job_id', $jobInsertId)->update([
            'customer_id' => $cusInsertId,
            'updated_datetime' => date('Y-m-d H:i:s')
        ]);
        
        $assetDatas = $data['assets'] ?? [];
        if ($assetDatas) {
            foreach ($assetDatas as $asset) {

                // Add asset data
                $asset_data = [
                    'name' => $asset['name'],
                    'group_id' => $asset['group'],
                    'sub_group_id' => $asset['sub_group'],
                    'make_compressor' => $asset['make_compressor'] ?? '',
                    'total_compressor' => $asset['total_compressor'] ?? '',
                    'make' => $asset['make'],
                    'model' => $asset['model'],
                    'serial_number' => $asset['serial_number'],
                    'capacity' => $asset['capacity'],
                    'measurement_unit' => $asset['measurement_unit'],
                    'quantity' => $asset['quantity'],
                    'location' => $asset['location'],
                    'status' => 1,
                    'created_datetime' => date('Y-m-d H:i:s')
                ];
                $this->cdb->table('asset')->insert($asset_data);
                $assetId = $this->cdb->insertID();
                
                // Add asset to contact job
                $this->cdb->table('contract_job_asset')->insert([
                    'contract_job_id' => $jobInsertId,
                    'asset_id' => $assetId,
                    'status' => 1,
                    'created_datetime' => date('Y-m-d H:i:s')
                ]);
                $jobAssetId = $this->cdb->insertID();

                // Insert asset ppm checklist
                $assetPPMChecklists = $asset['checklist']['ppm'] ?? [];
                foreach ($assetPPMChecklists as $assetPPMChecklist) {
                    $this->cdb->table('asset_checklist')->insert([
                        'asset_id' => $assetId,
                        'checklist_id' => $assetPPMChecklist,
                        'type' => 'ppm',
                        'status' => 1,
                        'created_datetime' => date('Y-m-d H:i:s')
                    ]);
                    $assetPPMChecklistId = $this->cdb->insertID();
                } 

                // Insert asset daily checklist
                $assetDailyChecklists = $asset['checklist']['daily'] ?? [];
                foreach ($assetDailyChecklists as $assetDailyChecklist) {
                    $this->cdb->table('asset_checklist')->insert([
                        'asset_id' => $assetId,
                        'checklist_id' => $assetDailyChecklist,
                        'type' => 'daily',
                        'status' => 1,
                        'created_datetime' => date('Y-m-d H:i:s')
                    ]);
                    $assetDailyChecklistId = $this->cdb->insertID();

                    // Set Qrcode
                    if ($assetDailyChecklistId) {
                        AppJobManager::run('cron/ContractJob/setQrcode', [
                            'asset_checklist_id' => $asset_checklist_id
                        ]);
                    }
                } 
            }
        }

        $this->cdb->transComplete();
        if ($this->cdb->transStatus() === false) {
            return false;
        } else {
            return $jobInsertId;
        }
    }

    public function updateContractJob($data)
    {
        $this->cdb->transStart();
        $period_fromdate = $data['period_fromdate'] ?? '';
        $period_todate = $data['period_todate'] ?? '';
        if($data['type'] == 'renew'){

            //Get & Update job sequence number
            $code = 'config_contract_job';
            $keyword = 'job_' . preg_replace('/[\s-]/', '_', strtolower($data['job_prefix'])) . '_sno';
            $setting = $this->cdb->table('setting')->where([
                'keyword' => $keyword,
                'code' => $code
            ])->get()->getRow();
            if ($setting) {
                $sequence_number = (int)$setting->value;
                $sequence_number += 1;
                $this->cdb->table('setting')
                    ->where([
                        'setting_id' => $setting->setting_id
                    ])->update([
                        'value' => $sequence_number,
                        'is_serialized' => 0,
                        'updated_datetime' => date('Y-m-d H:i:s')
                    ]);
            } else {
                $sequence_number = 1;
                $this->cdb->table('setting')
                    ->insert([
                        'code' => $code,
                        'keyword' => $keyword,
                        'value' => $sequence_number,
                        'is_serialized' => 0,
                        'created_datetime' => date('Y-m-d H:i:s')
                    ]);
            }
            $jobSequenceNumber = $sequence_number;
            $jobNumber = formJobNumber($jobSequenceNumber, $data['job_prefix']);
            $contractNatureId = $data['contract_nature'] ?? '';
            $contractTypeId = $data['contract_type'];
        } else{
            $jobNumber = $data['parent_job']->job_number;
            $contractNatureId = $data['parent_job']->contract_nature_id;
            $contractTypeId = $data['parent_job']->contract_type_id;
        }

        // Add contract job 
        $job_data = array(
            'created_user' => $data['created_user'],
            'job_title' => $data['job_title'],
            'job_number' => $data['job_number'] ?? $jobNumber ,
            'sap_job_number' => $data['sap_job_number'],
            'contract_nature_id' => $contractNatureId,
            'contract_type_id' => $contractTypeId,
            'deployed_people_number' => $data['deployed_people_number'],
            'purchase_order_number' => $data['po_number'],
            'contract_currency_id' => $data['contract_currency'] ?? '',
            'contract_gst_value' => $data['contract_gst_value'] ?? '',
            'contract_value' => $data['contract_value'] ?? '',
            'total_contract_value' => $data['contract_value_total'] ?? '',
            'expected_gross_margin' => $data['expected_gross_margin'] ?? '',
            'customer_account_manager_id' => $data['customer_account_manager'] ?? 0,
            'engineer_id' => $data['engineer'] ?? 0,
            'contract_status_id' => $data['contract_status'] ?? 0,
            'period' => $data['period'] ?? '',
            'period_fromdate' => $period_fromdate ? dbdate_format($period_fromdate) : '',
            'period_todate' => $period_todate ? dbdate_format($period_todate) : '',
            'ppm_frequency' => $data['ppm_frequency'] ?? '',
            'geolocation_lattitude' => $data['job_location_lattitude'] ?? '',
            'geolocation_longitude' => $data['job_location_longitude'] ?? '',
            'geolocation_range' => $data['job_location_range'] ?? 0,
            'process_type' => $data['type'] ?? 'update',
            'status' => 1,
            'created_datetime' => date('Y-m-d H:i:s')
        );

        $this->cdb->table('contract_job')->insert($job_data);
        $jobInsertId = $this->cdb->insertID();

        // Find and Update job parent
        $jobParent = $data['parent_job'] ?? false;
        if ($jobParent) {
            $jobParentPath = '';
            $paths = [];
            if ($jobParent->parent_path) {
                $paths = explode(',', $jobParent->parent_path);
            }
            array_push($paths, $jobInsertId);
            $jobParentPath = implode(',', $paths);
            $this->cdb->table('contract_job')->where('contract_job_id', $jobInsertId)->update([
                'parent' => $jobParent->contract_job_id,
                'parent_path' => $jobParentPath,
                'updated_datetime' => date('Y-m-d H:i:s')
            ]);
        }

        // Add customer
        if ($data['customer_type'] == 'exist') {
            $cusInsertId = $data['customer_id'];
        } else {
            $insert_data = array(
                'company_name' => $data['customer_company_name'],
                'sector' => $data['customer_sector'] ?? 0,
                'billing_address' => $data['customer_billing_address'] ?? '',
                'billing_address_contact_name' => $data['customer_billing_address_contact_name'],
                'billing_address_email' => $data['customer_billing_address_email'] ?? '',
                'site_address' => $data['customer_site_address'] ?? '',
                'billing_address_mobile' => $data['customer_billing_address_mobile'],
                'billing_address_country' => $data['customer_billing_address_country'] ?? 0,
                'billing_address_state' => $data['customer_billing_address_state'] ?? 0,
                'billing_address_city' => $data['customer_billing_address_city'] ?? 0,
                'billing_address_pincode' => $data['customer_billing_address_pincode'] ?? '',
                'site_address_contact_name' => $data['customer_site_address_contact_name'],
                'site_address_email' => $data['customer_site_address_email'] ?? '',
                'site_address_mobile' => $data['customer_site_address_mobile'],
                'site_address_country' => $data['customer_site_address_country'] ?? 0,
                'site_address_state' => $data['customer_site_address_state'] ?? 0,
                'site_address_city' => $data['customer_site_address_city'] ?? 0,
                'site_address_pincode' => $data['customer_site_address_pincode'] ?? '',
                'website' => $data['customer_website'] ?? '',
                'gst_number' => $data['customer_gst_number'] ?? '',
                'payment_term' => $data['customer_payment_term'] ?? '',
                'pan_number' => $data['customer_pan_number'] ?? '',
                'status' => $data['customer_status'] ?? 1,
                'created_datetime' => date('Y-m-d H:i:s')
            );
            $this->cdb->table('customer')->insert($insert_data);
            $cusInsertId = $this->cdb->insertID();

            // Add user
            if ($data['user_type'] && $data['customer_password']) {
                $password = password_hash($data['customer_password'], PASSWORD_BCRYPT);
                $user_type = $data['user_type'];
                $user_data = array(
                    'first_name' => $data['customer_company_name'] ?? '',
                    'last_name' => $data['last_name'] ?? '',
                    'username' => $data['customer_username'],
                    'password' => $password,
                    // 'email' => $data['customer_billing_address_email'] ?? '',
                    'mobile' => $data['customer_billing_address_mobile'],
                    'user_type' => $user_type,
                    'status' => 1
                );

                $this->cdb->table('user')->insert($user_data);
                $userInsertID = $this->cdb->insertID();
                if ($userInsertID) {
                    $this->setCustomerUserId($cusInsertId, $userInsertID);
                }
            }
        }

        // Update customer to contract/job table
        $this->cdb->table('contract_job')->where('contract_job_id', $jobInsertId)->update([
            'customer_id' => $cusInsertId,
            'updated_datetime' => date('Y-m-d H:i:s')
        ]);

        $assetDatas = $data['assets'] ?? [];
        if ($assetDatas) {

            foreach ($assetDatas as $asset) {

                // Add asset data
                $asset_data = [
                    'name' => $asset['name'],
                    'group_id' => $asset['group'],
                    'sub_group_id' => $asset['sub_group'],
                    'make_compressor' => $asset['make_compressor'] ?? '',
                    'total_compressor' => $asset['total_compressor'] ?? '',
                    'make' => $asset['make'],
                    'model' => $asset['model'],
                    'serial_number' => $asset['serial_number'],
                    'capacity' => $asset['capacity'],
                    'measurement_unit' => $asset['measurement_unit'],
                    'quantity' => $asset['quantity'],
                    'location' => $asset['location'],
                    'status' => 1,
                    'created_datetime' => date('Y-m-d H:i:s')
                ];
                $this->cdb->table('asset')->insert($asset_data);
                $assetId = $this->cdb->insertID();
                
                // Add asset to contact job
                $this->cdb->table('contract_job_asset')->insert([
                    'contract_job_id' => $jobInsertId,
                    'asset_id' => $assetId,
                    'status' => 1,
                    'created_datetime' => date('Y-m-d H:i:s')
                ]);
                $jobAssetId = $this->cdb->insertID();

                // Insert asset ppm checklist
                $assetPPMChecklists = $asset['checklist']['ppm'] ?? [];
                foreach ($assetPPMChecklists as $assetPPMChecklist) {
                    $this->cdb->table('asset_checklist')->insert([
                        'asset_id' => $assetId,
                        'checklist_id' => $assetPPMChecklist,
                        'type' => 'ppm',
                        'status' => 1,
                        'created_datetime' => date('Y-m-d H:i:s')
                    ]);
                    $assetPPMChecklistId = $this->cdb->insertID();
                } 

                // Insert asset daily checklist
                $assetDailyChecklists = $asset['checklist']['daily'] ?? [];
                foreach ($assetDailyChecklists as $assetDailyChecklist) {
                    $this->cdb->table('asset_checklist')->insert([
                        'asset_id' => $assetId,
                        'checklist_id' => $assetDailyChecklist,
                        'type' => 'daily',
                        'status' => 1,
                        'created_datetime' => date('Y-m-d H:i:s')
                    ]);
                    $assetDailyChecklistId = $this->cdb->insertID();
                    if ($assetDailyChecklistId) {
                        
                    }
                } 
                
            }
        }

        $this->cdb->transComplete();
        if ($this->cdb->transStatus() === false) {
            return false;
        } else {
            return $jobInsertId;
        }
    }

    
    public function setContractJobStatus($contract_job_id, $status)
    {
        $this->cdb->transStart();
        $condition = array(
            'contract_job_id' => $contract_job_id
        );
        $update_data = array(
            'status' => $status,
            'updated_datetime' => date('Y-m-d H:i:s')
        );
        $builder = $this->cdb->table('contract_job');
        $builder->where($condition);
        $result = $builder->update($update_data);
        $this->cdb->transComplete();

        if ($this->cdb->transStatus() === false) {
            return false;
        } else {
            return $result;
        }
    }

    public function setContractStatus($contract_job_id, $contract_status_id)
    {
        $this->cdb->transStart();
        $condition = array(
            'contract_job_id' => $contract_job_id
        );
        $update_data = array(
            'contract_status_id' => $contract_status_id,
            'updated_datetime' => date('Y-m-d H:i:s')
        );
        $builder = $this->cdb->table('contract_job');
        $builder->where($condition);
        $result = $builder->update($update_data);
        $this->cdb->transComplete();
        echo $this->cdb->getLastQuery();
        if ($this->cdb->transStatus() === false) {
            return false;
        } else {
            return $result;
        }
    }

    public function disableContractJob($contract_job_id)
    {
        $this->cdb->transStart();
        $condition = array(
            'contract_job_id' => $contract_job_id
        );
        $update_data = array(
            'status' => 0,
            'updated_datetime' => date('Y-m-d H:i:s')
        );
        $builder = $this->cdb->table('contract_job');
        $builder->where($condition);
        $result = $builder->update($update_data);

        $this->cdb->transComplete();

        if ($this->cdb->transStatus() === false) {
            return false;
        } else {
            return $result;
        }
    }

    public function removeContractJob($contract_job_id, $status)
    {
        $this->cdb->transStart();
        $condition = array(
            'contract_job_id' => $contract_job_id
        );
        $update_data = array(
            'removed' => $status,
            // 'status' => 0,
            'updated_datetime' => date('Y-m-d H:i:s')
        );
        $builder = $this->cdb->table('contract_job');
        $builder->where($condition);
        $result = $builder->update($update_data);

        $this->cdb->transComplete();

        if ($this->cdb->transStatus() === false) {
            return false;
        } else {
            return $result;
        }
    }

    
    // Assets
    public function getContractJobAssets($contract_job_id, $data = [])
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

        $builder = $this->cdb->table('contract_job_asset acj');
        $builder->join('contract_job cj', 'cj.contract_job_id = acj.contract_job_id');
        $builder->join('asset a', 'a.asset_id = acj.asset_id');
        $builder->join('asset_group ag1', 'ag1.asset_group_id = a.group_id', 'left');
        $builder->join('asset_group ag2', 'ag2.asset_group_id = a.sub_group_id', 'left');

        $builder->select('cj.contract_job_id, a.*, a.name as asset_name, ag1.name as group_name, ag2.name as sub_group_name');
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

    public function getContractJobAssetsChecklists($contract_job_id, $data = [])
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

        $builder = $this->cdb->table('contract_job_asset acj');
        $builder->join('contract_job cj', 'cj.contract_job_id = acj.contract_job_id');
        $builder->join('asset a', 'a.asset_id = acj.asset_id');
        $builder->join('asset_group ag1', 'ag1.asset_group_id = a.group_id', 'left');
        $builder->join('asset_group ag2', 'ag2.asset_group_id = a.sub_group_id', 'left');
        $builder->join('asset_checklist ac', 'a.asset_id = ac.asset_id');

        $builder->select('cj.contract_job_id, acj.contract_job_asset_id, ac.asset_checklist_id');
        $builder->select('a.*, a.name as asset_name, ag1.name as group_name, ag2.name as sub_group_name');
        
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

    // Assets
    public function getContractJobAssetById($asset_id, $data = [])
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
    public function getContractJobAssetChecklists($asset_id, $data = [])
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

    // PPM Frequency
    public function getPPMFrequencies($contract_job_id, $data = [])
    {
        $condition = array(
            'cj.contract_job_id' => $contract_job_id
        );

        if (isset($data['status'])) {
            $condition['cj.status'] = (int)$data['status'];
        }

        if (isset($data['removed'])) {
            $condition['cj.removed'] = (int)$data['removed'];
        }

        $builder = $this->cdb->table('contract_job_ppm cjp');
        $builder->join('contract_job cj', 'cj.contract_job_id = cjp.contract_job_id');

        $builder->select('cjp.*');
        if ($condition) {
            $builder->where($condition);
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
            $sort = 'cjp.contract_job_ppm_id';
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

    public function addPPMFrequencies($contract_job_id, $datas)
    {
        $this->cdb->transStart();
        if ($datas) {
            foreach ($datas as $data) {
                $builder = $this->cdb->table('contract_job_ppm');
                $builder->insert([
                    'contract_job_id' => (int)$contract_job_id,
                    'start_date' => $data['start'],
                    'end_date' => $data['end'],
                    'status' => 1,
                    'created_datetime' => date('Y-m-d H:i:s')
                ]);
            }
        }
        $this->cdb->transComplete();

        if ($this->cdb->transStatus() === false) {
            return false;
        } else {
            // update ppm status
            AppJobManager::run('cron/ContractJob/updatePPMFrquenciesStatus', [
                'contract_job_id' => $contract_job_id
            ]);
            return true;
        }
    }

    public function setPPMFrequenciesStatus($contract_job_id, $status)
    {
        $this->cdb->transStart();
        $builder = $this->cdb->table('contract_job_ppm');
        $builder->where(['contract_job_id' => (int)$contract_job_id]);
        $builder->update([
            'status' => (int)$status,
            'updated_datetime' => date('Y-m-d H:i:s')
        ]);
        $this->cdb->transComplete();

        if ($this->cdb->transStatus() === false) {
            return false;
        } else {
            return true;
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

    // Checklist Track
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

    public function addChecklistTrack($data)
    {
        $this->cdb->transStart();
        
        $this->cdb->table('contract_job_checklist_track')->insert([
            'contract_job_id' => $data['contract_job_id'],
            'contract_job_asset_id' => $data['contract_job_asset_id'],
            'asset_checklist_id' => $data['asset_checklist_id'],
            'status' => 1,
            'created_datetime' =>date('Y-m-d H:i:s')
        ]);
        
        $this->cdb->transComplete();

        if ($this->cdb->transStatus() === false) {
            return false;
        } else {
            return true;
        }
    }

}
