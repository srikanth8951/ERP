<?php

namespace App\Libraries\Auth;

use App\Libraries\Auth\Token as AuthToken;
use App\Libraries\Auth\User as AuthUser;

class Admin
{
    private static $user_id = 0;

    public static function isValid($userType = 'none', $userId = null): int
    {
        helper('user');
        $db = \Config\Database::connect();

        if (!$userId) {
            $userId = AuthUser::getId();
        }
        // Get user type/group
        $userType = getUserTypeByCode($userType);

        $userTypeId = $userType['type_id'] ?? 0;
        $condition_data = [
            'user_type' => $userTypeId,
            'user_id' => $userId,
        ];

      
        $result = $db
            ->table('user')
            ->select('*')
            ->where($condition_data)
            ->get();
        if ($result->getNumRows()) {
            self::$user_id = $result->getRow()->user_id;
        }
        return self::$user_id;
    }

    public static function getId()
    {
        return self::$user_id;
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
