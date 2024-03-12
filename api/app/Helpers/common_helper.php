<?php
/**
 * Measurement Units
 */
function getMeasurementUnits()
{
    return [
        ['name' => 'TR','code' => 'tr'],
        ['name' => 'CFM','code' => 'cfm'],
        ['name' => 'HP','code' => 'hp'],
        ['name' => 'KVA','code' => 'kva'],
        ['name' => 'KW','code' => 'kw'],
        ['name' => 'No','code' => 'no'],
        ['name' => 'KVAR','code' => 'kvar'],
        ['name' => 'Nos','code' => 'nos'],
        ['name' => 'W','code' => 'w'],
        ['name' => 'Hz','code' => 'hz'],
        ['name' => 'VOLTS','code' => 'volts'],
        ['name' => 'Ohm','code' => 'ohm'],
        ['name' => 'Mili Ohm','code' => 'mili_ohm'],
        ['name' => 'Mega Ohm','code' => 'mega_ohm'],
        ['name' => 'Giga Ohm','code' => 'giga_ohm'],
        ['name' => 'Tera Ohm','code' => 'tera_ohm'],
        ['name' => 'AMP','code' => 'amp'],
        ['name' => 'TDS','code' => 'tds'],
        ['name' => 'Ph','code' => 'ph'],
        ['name' => 'Pa','code' => 'pa'],
        ['name' => 'Kg/sq cm','code' => 'kg_sq_cm'],
        ['name' => 'Deg C','code' => 'deg_c'],
        ['name' => 'Deg F','code' => 'deg_f'],
        ['name' => 'Deg K','code' => 'deg_k'],
        ['name' => 'dB','code' => 'db'],
        ['name' => 'm/s','code' => 'm_s'],
        ['name' => '%','code' => 'precentage'],
        ['name' => 'MP','code' => 'mp'],
    ];
}

/**
 * Get Checklist Types
 */
if (!function_exists('getChecklistTypes')) {
    function getChecklistTypes()
    {
        return [
            ['id' => 1, 'name' => 'Type 1', 'code' => 'task'],
            [
                'id' => 2,
                'name' => 'Type 2 (Sub division)',
                'code' => 'task_with_division',
            ],
        ];
    }
}

if (!function_exists('getChecklistTypeByCode')) {
    function getChecklistTypeByCode($code)
    {
        $typeData = [];
        $types = getChecklistTypes();
        foreach ($types as $type) {
            if ($type['code'] == $code) {
                $typeData = $type;
                break;
            }
        }
        return $typeData;
    }
}

if (!function_exists('getChecklistTypeById')) {
    function getChecklistTypeById($id)
    {
        $typeData = [];
        $types = getChecklistTypes();
        foreach ($types as $type) {
            if ($type['id'] == $id) {
                $typeData = $type;
                break;
            }
        }
        return $typeData;
    }
}

/**
 * Get Checklist Task Types
 */
if (!function_exists('getChecklistTaskTypes')) {
    function getChecklistTaskTypes()
    {
        return [
            ['id' => 1, 'name' => 'Checkbox', 'code' => 'checkbox'],
            ['id' => 2, 'name' => 'Textbox', 'code' => 'textbox'],
            ['id' => 3, 'name' => 'None', 'code' => 'none'],
        ];
    }
}

if (!function_exists('getChecklistTaskTypeByCode')) {
    function getChecklistTaskTypeByCode($code)
    {
        $typeData = [];
        $types = getChecklistTaskTypes();
        foreach ($types as $type) {
            if ($type['code'] == $code) {
                $typeData = $type;
                break;
            }
        }
        return $typeData;
    }
}

if (!function_exists('getChecklistTaskTypeById')) {
    function getChecklistTaskTypeById($id)
    {
        $typeData = [];
        $types = getChecklistTaskTypes();
        foreach ($types as $type) {
            if ($type['id'] == $id) {
                $typeData = $type;
                break;
            }
        }
        return $typeData;
    }
}

/**
 * Get Checklist Task Types
 */
if (!function_exists('getStoreRequestStatus')) {
    function getStoreRequestStatus()
    {
        return [
            ['id' => 1, 'name' => 'Request raised', 'code' => 'RAISED'],
            ['id' => 2, 'name' => 'Request cancelled', 'code' => 'CANCELLED'],
            ['id' => 3, 'name' => 'Approved by RSDH', 'code' => 'RSDAPRV'],
            ['id' => 4, 'name' => 'Rejected by RSDH', 'code' => 'RSDRJ'],
            ['id' => 5, 'name' => 'Approved by ASDH', 'code' => 'ASDAPRV'],
            ['id' => 6, 'name' => 'Rejected by ASDH', 'code' => 'ASDRJ'],
            ['id' => 7, 'name' => 'Approved by RH', 'code' => 'RHAPRV'],
            ['id' => 8, 'name' => 'Rejected by RH', 'code' => 'RHAPRV'],
            ['id' => 9, 'name' => 'Issued', 'code' => 'ISSUED'],
        ];
    }
}

if (!function_exists('getStoreRequestStatusByCode')) {
    function getStoreRequestStatusByCode($code)
    {
        $typeData = [];
        $types = getStoreRequestStatus();
        foreach ($types as $type) {
            if ($type['code'] == $code) {
                $typeData = $type;
                break;
            }
        }
        return $typeData;
    }
}

if (!function_exists('getStoreRequestStatusById')) {
    function getStoreRequestStatusById($id)
    {
        $typeData = [];
        $types = getStoreRequestStatus();
        foreach ($types as $type) {
            if ($type['id'] == $id) {
                $typeData = $type;
                break;
            }
        }
        return $typeData;
    }
}

?>
