<?php

/**
 * Job Number Formatter
 */
if (!function_exists('formJobNumber')) {
    function formJobNumber($id, $prefix = '')
    {
        $idLen = strlen($id);
        $idExpMinLen = 4;
        $idStr = '';
        $idPrefix = $prefix ? $prefix : '#job';
        if ($idLen > $idExpMinLen) {
            $idStr = $id;
        } else {
            $defLen = $idExpMinLen - $idLen;
            for ($l = 1; $l <= $defLen; $l++) {
                $idStr .= '0';
            }

            $idStr .= $id;
        }

        return $idPrefix . $idStr;
    }
}

/**
 * Get job sequence number
 */
if (!function_exists('setJobSequenceNo')) {
    function setJobSequenceNo($prefix)
    {
        $sequence_number = 0;
        $modelSetting = new \App\Models\SettingModel();
        $code = 'config_contract_job';
        $keyword = 'job_' . str_replace(' ', '_', $prefix) . '_sno';
        $setting = $modelSetting->getSetting($code, $keyword);
        if ($setting) {
            $sequence_number = (int)$setting->value;
            $sequence_number += 1;
            $modelSetting->editSetting($setting->setting_id, $sequence_number);
        } else {
            $sequence_number = 1;
            $modelSetting->addSetting($setting->setting_id, $sequence_number);
        }

        $modelSetting->editSetting($setting->setting_id, $sequence_number);
        return $sequence_number;
    }
}

/**
 * PPM Frequencies
 */
if (!function_exists('getPPMFrequencies')) {
    function getPPMFrequencies()
    {
        return [
            ['code' => 'monthly', 'name' => 'Monthly', 'frequencyNo' => 1, 'frequencyCode' => 'monthly'],
            ['code' => 'bi_monthly', 'name' => 'Bi-Monthly', 'frequencyNo' => 1,'frequencyCode' => 'monthly_twice'],
            ['code' => 'quarterly', 'name' => 'Quarterly', 'frequencyNo' => 3,'frequencyCode' => 'quarterly'],
            ['code' => 'half_yearly','name' => 'Half Yearly','frequencyNo' => 6,'frequencyCode' => 'half_yearly'],
            ['code' => 'yearly', 'name' => 'Yearly', 'frequencyNo' => 12,'frequencyCode' => 'yearly'],
        ];
    }
}

if (!function_exists('getPPMFrequencyByCode')) {
    function getPPMFrequencyByCode($code)
    {
        $result = [];
        $frequencies = getPPMFrequencies();
        foreach ($frequencies as $frequency) {
            if ($frequency['code'] == $code) {
                $result = $frequency;
                break;
            }
        }

        return $result;
    }
}

/**
 * PPM Frequency Statuses
 */
if (!function_exists('getPPMFrequencyStatuses')) {
    function getPPMFrequencyStatuses()
    {
        return [
            ['id' => 1, 'code' => 'pending', 'name' => 'Pending'],
            ['id' => 2, 'code' => 'ongoing', 'name' => 'On-Going'],
            ['id' => 3, 'code' => 'completed', 'name' => 'Completed'],
            ['id' => 4, 'code' => 'incomplete', 'name' => 'Incomplete'],
            ['id' => 5, 'code' => 'upcoming', 'name' => 'Upcoming'],
        ];
    }
}

if (!function_exists('getPPMFrequencyStatusByCode')) {
    function getPPMFrequencyStatusByCode($code)
    {
        $result = [];
        $statuses = getPPMFrequencyStatuses();
        foreach ($statuses as $status) {
            if ($status['code'] == $code) {
                $result = $status;
                break;
            }
        }

        return $result;
    }
}

if (!function_exists('getPPMFrequencyStatusById')) {
    function getPPMFrequencyStatusById($id)
    {
        $result = [];
        $statuses = getPPMFrequencyStatuses();
        foreach ($statuses as $status) {
            if ($status['id'] == $id) {
                $result = $status;
                break;
            }
        }

        return $result;
    }
}

if (!function_exists('getContractJobStatusByName')) {
    function getContractJobStatusByName($name)
    {
        $check_name = 'REPLACE(LOWER(name), " ", "_") = REPLACE(LOWER("'. $name .'")," ", "_")';
        $db = \Config\Database::connect();
        $result = $db->table('contract_status')
            ->select('contract_status_id as id, name, status')
            ->where($check_name)
            ->get();
        return $result->getRow();
    }
}
