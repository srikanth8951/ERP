<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UserLoginAlter1 extends Migration
{
    public function up()
    {
        $fields = [
            'ul_remember_status' => [
                'type' => 'TINYINT',
                'constraint' => 11,
                'default' => 0,
                'after' => 'ul_user_agent'
            ]
        ];

        $this->forge->addColumn('user_login', $fields);
    }

    public function down()
    {
        //
    }
}
