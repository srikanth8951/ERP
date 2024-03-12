<?php

namespace App\Libraries\Auth;

use App\Libraries\Auth\Token as AuthToken;
use App\Libraries\Auth\User as AuthUser;

class Customer
{
    private static $customer_id = 0;
    private static $customer_first_name = '';
    private static $customer_last_name = '';
    private static $customer_email = '';
    private static $customer_mobile = '';
    private static $customer_type = 0;

    public static function isValid($userId = null): int
    {
        helper('user');
        if (!$userId) {
            $userId = AuthUser::getId();
        }

        $condition_data = [
            'c.user_id' => $userId,
        ];

        $builder = \Config\Database::connect();
        $result = $builder
            ->table('customer as c')
            ->join(
                'user as u',
                'u.user_id = c.user_id'
            )
            ->select('c.*')
            ->where($condition_data)
            ->get();
        // echo $builder->getLastQuery();
        if ($result->getNumRows()) {
            self::$customer_id = $result->getRow()->customer_id;
        }
        return self::$customer_id;
    }

    public static function getId()
    {
        return self::$customer_id;
    }

    public static function check($id = null)
    {
        if (self::isValid($id)) {
            return self::getId();
		} else {
            return false;
        }
    }
}
