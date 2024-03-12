<?php
//Get Statuses
if (!function_exists('getStatuses')) {
    function getStatuses()
    {
        return [
            0 => 'Inctive',
            1 => 'Active',
        ];
    }
}

if (!function_exists('getQStatuses')) {
    function getQStatuses()
    {
        return [
            0 => 'No',
            1 => 'Yes',
        ];
    }
}

if (!function_exists('getPPMFrequencies')) {
    function getPPMFrequencies()
    {
        return [
            ['code' => 'monthly', 'name' => 'Monthly'],
            ['code' => 'bi_monthly', 'name' => 'Bi-Monthly'],
            ['code' => 'quarterly', 'name' => 'Quarterly'],
            ['code' => 'half_yearly', 'name' => 'Half Yearly'],
            ['code' => 'yearly', 'name' => 'Yearly'],
        ];
    }
}

/**
 * Contract Types
 */
if (!function_exists('getContractTypes')) {
    function getContractTypes($except = [])
    {
        $types = [];
        $typess = [
            ['id' => 1, 'name' => 'CAMC', 'code' => 'camc'],
            ['id' => 2, 'name' => 'LAMC', 'code' => 'lamc'],
            ['id' => 3, 'name' => 'Mix', 'code' => 'mix'],
            ['id' => 4, 'name' => 'O&M', 'code' => 'oandm'],
            ['id' => 5, 'name' => 'Warrenty', 'code' => 'warrenty'],
        ];

        foreach ($typess as $tkey => $type) {
            if (in_array($type['id'], $except)) {
                $typez = [];
            } else {
                $typez = $type;
            }
            
            if ($typez) {
                $types[] = $typez;
            } 
        }

        return $types;
    }
}

if (!function_exists('getContractTypeById')) {
    function getContractTypeById($id)
    {
        $typeData = [];
        $types = getContractTypes();
        foreach ($types as $type) {
            if ($type['id'] == $id) {
                $typeData = $type;
                break;
            }
        }
        return $typeData;
    }
}

if (!function_exists('getContractTypeByCode')) {
    function getContractTypeByCode($code)
    {
        $typeData = [];
        $types = getContractTypes();
        foreach ($types as $type) {
            if ($type['code'] == $code) {
                $typeData = $type;
                break;
            }
        }
        return $typeData;
    }
}

