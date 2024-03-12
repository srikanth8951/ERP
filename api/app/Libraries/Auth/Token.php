<?php

namespace App\Libraries\Auth;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Token
{
    private static $key = "b866f9d677dca01f020d93e8c5ade3edb6d1fe6a";
    private static $algo = 'HS256';

    public static function generateJWT($data)
    {
        $jwt = JWT::encode($data, self::$key, self::$algo);
        return $jwt;
    }

    public static function decodeJWT($token)
    {	
        try { 	
            $decoded = JWT::decode($token, new Key(self::$key, self::$algo));
            $decodedData = (array) $decoded;
            return [
                'status' => 'success',
                'data' => $decodedData
            ];
        } catch(\Exception $e){
            return [
                'status' => 'error',
                'message' => 'Error: '. $e->getMessage()
            ];
        }
    }

    public static function getJWT($authorization)
    {
        $authArr = explode(' ', $authorization);
        $auth_token = isset($authArr[1]) ? $authArr[1]: '';
        if ($auth_token) {
            return self::decodeJWT($auth_token);
        } else {
            return [
                'status' => 'error',
                'message' => 'Error: Authorization token format!'
            ];
        }
    }

    public static function formJWT($data)
    {
        $auth_token = self::generateJWT($data);
        return 'Bearer ' . $auth_token;
    }
}