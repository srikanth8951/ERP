<?php

define('ADMIN_USER', 1);
define('NATIONAL_HEAD_USER', 201);
define('AISD_HEAD_USER', 210);
define('REGION_HEAD_USER', 211);
define('AREA_HEAD_USER', 212);
define('ENGINEER_USER', 213);
define('MANAGER_USER', 214);
define('SUPERVISOR_USER', 215);
define('TECHNICIAN_USER', 216);
define('DATA_MANAGEMENT_USER', 217);
define('CLIENT_ACCOUNT_MANAGER_USER', 219);
define('RSD_HEAD_USER', 220);
define('ASD_HEAD_USER', 221);
define('CUSTOMER_USER', 222);
define('VENDOR_USER', 223);
define('STORE_USER', 224);

/**
 * Get User Types
 */
if (!function_exists('getUserTypes')) {
    function getUserTypes()
    {
        $users = [
            [
                'type_id' => NATIONAL_HEAD_USER,
                'code' => 'national_head',
                'name' => 'National Head',
                'group' => 'national_head',
            ],
            [
                'type_id' => AISD_HEAD_USER,
                'code' => 'aisd_head',
                'name' => 'AiSD Head',
                'group' => 'aisd_head',
            ],
            [
                'type_id' => RSD_HEAD_USER,
                'code' => 'rsd_head',
                'name' => 'RSD Head',
                'group' => 'rsd_head',
            ],
            [
                'type_id' => ASD_HEAD_USER,
                'code' => 'asd_head',
                'name' => 'ASD Head',
                'group' => 'asd_head',
            ],
            [
                'type_id' => REGION_HEAD_USER,
                'code' => 'region_head',
                'name' => 'Region Head',
                'group' => 'region_head',
            ],
            [
                'type_id' => AREA_HEAD_USER,
                'code' => 'area_head',
                'name' => 'Area Head',
                'group' => 'area_head',
            ],
            [
                'type_id' => ENGINEER_USER,
                'code' => 'engineer',
                'name' => 'Engineer',
                'group' => 'engineer',
            ],
            [
                'type_id' => MANAGER_USER,
                'code' => 'o&m_manager',
                'name' => 'O&M manager',
                'group' => 'o&m_manager',
            ],
            [
                'type_id' => SUPERVISOR_USER,
                'code' => 'supervisor',
                'name' => 'Supervisor',
                'group' => 'supervisor',
            ],
            [
                'type_id' => TECHNICIAN_USER,
                'code' => 'technician',
                'name' => 'Technician',
                'group' => 'technician',
            ],
            [
                'type_id' => DATA_MANAGEMENT_USER,
                'code' => 'data_management',
                'name' => 'Data Management',
                'group' => 'data_management',
            ],
            [
                'type_id' => CLIENT_ACCOUNT_MANAGER_USER,
                'code' => 'client_account_manager',
                'name' => 'Client Account Manager',
                'group' => 'client_account_manager',
            ],
            [
                'type_id' => CUSTOMER_USER,
                'code' => 'customer',
                'name' => 'Customer',
                'group' => 'customer',
            ],
            [
                'type_id' => VENDOR_USER,
                'code' => 'vendor',
                'name' => 'Vendor',
                'group' => 'vendor',
            ],
            [
                'type_id' => STORE_USER,
                'code' => 'store',
                'name' => 'Store',
                'group' => 'store',
            ],
            [
                'type_id' => ADMIN_USER,
                'code' => 'admin',
                'name' => 'Admin',
                'group' => 'admin',
            ],
        ];

        return $users;
    }
}

if (!function_exists('getUserTypesByGroup')) {
    function getUserTypesByGroup($group)
    {
        $usersz = [];
        $users = getUserTypes();
        foreach ($users as $user) {
            if ($user['group'] == $group) {
                $userz[] = $user;
            }
        }

        return $userz;
    }
}

/**
 * Get Module Action
 */
if (!function_exists('getUserTypeById')) {
    function getUserByTypeId($type_id)
    {
        $type_user = [];
        $users = getUserTypes();
        foreach ($users as $user) {
            if ($user['type_id'] == (int) $type_id) {
                $type_user = $user;
                break;
            }
        }

        return $type_user;
    }
}

if (!function_exists('getUserTypeByCode')) {
    function getUserTypeByCode($code)
    {
        $type_user = [];
        $users = getUserTypes();
        foreach ($users as $user) {
            if ($user['code'] == $code) {
                $type_user = $user;
                break;
            }
        }

        return $type_user;
    }
}

if (!function_exists('getUserTypeIdByCode')) {
    function getUserTypeIdByCode($type_name)
    {
        $type_id = 0;
        $users = getUserTypes();
        foreach ($users as $user) {
            if ($user['code'] == $type_name) {
                $type_id = $user['type_id'];
                break;
            }
        }

        return $type_id;
    }
}

/**
 * Get Module Action URL
 */
if (!function_exists('getUserTypeCodeById')) {
    function getUserTypeCodeById($user_type)
    {
        $type_name = '';
        $users = getUserTypes();
        foreach ($users as $user) {
            if ($user['type_id'] == (int) $user_type) {
                $type_name = $user['code'];
                break;
            }
        }

        return $type_name;
    }
}
