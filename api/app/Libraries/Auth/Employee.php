<?php

namespace App\Libraries\Auth;

use App\Libraries\Auth\Token as AuthToken;
use App\Libraries\Auth\User as AuthUser;

class Employee
{
    private static $employee_id = 0;
    private static $employee_first_name = '';
    private static $employee_last_name = '';
    private static $employee_email = '';
    private static $employee_mobile = '';
    private static $employee_type = 0;

    public static function isValid($userType = 'none', $userId = null): int
    {
        helper('user');
        if (!$userId) {
            $userId = AuthUser::getId();
        }
        // Get employee user type/group
        $empType = getUserTypeByCode($userType);

        $empTypeId = $empType['type_id'] ?? 0;
        $condition_data = [
            'emp.user_id' => $userId,
            'emp.user_type' => $empTypeId,
        ];

        $builder = \Config\Database::connect();
        $result = $builder
            ->table('employee as emp')
            ->join(
                'user as u',
                'u.user_id = emp.user_id and u.user_type = emp.user_type'
            )
            ->select('emp.*')
            ->where($condition_data)
            ->get();
     
        if ($result->getNumRows()) {
            self::$employee_id = $result->getRow()->employee_id;
        }
        return self::$employee_id;
    }

    public static function getId()
    {
        return self::$employee_id;
    }

    public static function check($type, $id = null)
    {
        if (self::isValid($type, $id)) {
            return self::getId();
		} else {
            return false;
        }
    }
}
