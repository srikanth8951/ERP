<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UserAlter1 extends Migration
{
    public function up()
    {
        $fields = [
            'recover_email_token' =>[
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
                'after' => 'permission'
            ],
            'recover_email_datetime' => [
                'type' => 'DATETIME',
                'null' => true,
                'after' => 'recover_email_token'
            ]
        ];

        $this->forge->addColumn('user', $fields);
    }

    public function down()
    {
        //
    }
}
