<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $password = '123456';
        $permissions = [];

        $userData = [
            'first_name' => 'Sterling',
            'last_name' => 'Wilson',
            'username' => 'admin',
            'email' => 'admin@sterlingwilson.com',
            'mobile' => '7904633883',
            'password' => password_hash($password, PASSWORD_BCRYPT),
            'user_type' => 1,
            'permission' => json_encode($permissions),
            'status' => 1,
            'created_datetime' => date('Y-m-d H:i:s')
        ];

        $this->db->table('user')->insert($userData);
    }
}
