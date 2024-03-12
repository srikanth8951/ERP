<?php

namespace App\Models\Employee;

use Config\Services;
use Config\Database;
use App\Libraries\AppJobManager;

class ContractJobPPMModel
{
    private $cdb;
    private $dbPrefix;

    public function __construct()
    {
        $this->cdb = Database::connect('default');
        $this->dbPrefix = $this->cdb->getPrefix();
        helper('contract_job');
    }

    // PPM Frequency
    public function getPPMFrequencies($contract_job_id, $data = [])
    {
        $condition = array(
            'cj.contract_job_id' => $contract_job_id
        );

        if (isset($data['job_status'])) {
            $condition['cj.status'] = (int)$data['job_status'];
        }

        if (isset($data['job_removed'])) {
            $condition['cj.removed'] = (int)$data['job_removed'];
        }

        $builder = $this->cdb->table('contract_job_ppm cjp');
        $builder->join('contract_job cj', 'cj.contract_job_id = cjp.contract_job_id');

        $builder->select('cjp.*, cj.job_title as contract_job_name, cj.job_number as job_number, cj.ppm_frequency as ppm_frequency');
        if ($condition) {
            $builder->where($condition);
        }

        if (isset($data['ppm_status'])) {
            if (is_array($data['ppm_status'])) {
                $builder->whereIn('cjp.status', $data['ppm_status']);
            } else {
                $builder->where('cjp.status', (int)$data['ppm_status']);
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
            return [];
        }
    }

    public function getPPMFrequency($contract_job_ppm_id, $data = [])
    {
        $condition = array(
            'cjp.contract_job_ppm_id' => $contract_job_ppm_id
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

        $query = $builder->get();

        if ($query->getNumRows() > 0) {
            return $query->getRow();
        } else {
            return [];
        }
    }

    public function getCurrentPPMFrequency($contract_job_id, $data = [])
    {
        $currentDate = date('Y-m-d');
        $condition = array(
            'cj.contract_job_id' => $contract_job_id,
            'cjp.start_date <= ' => $currentDate,
            'cjp.end_date >=' => $currentDate,
            'cj.status' => 1
        );

        $builder = $this->cdb->table('contract_job_ppm cjp');
        $builder->join('contract_job cj', 'cj.contract_job_id = cjp.contract_job_id');

        $builder->select('cjp.*');
        if ($condition) {
            $builder->where($condition);
        }

        $query = $builder->get();
        // echo $this->cdb->getLastQuery();
        if ($query->getNumRows() > 0) {
            return $query->getRow();
        } else {
            return [];
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

    public function setPPMFrequencyStatus($contract_job_ppm_id, $status)
    {
        $this->cdb->transStart();
        $builder = $this->cdb->table('contract_job_ppm');
        $builder->where(['contract_job_ppm_id' => (int)$contract_job_ppm_id]);
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
}