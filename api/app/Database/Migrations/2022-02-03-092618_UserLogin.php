<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UserLogin extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'user_login_id' => [
                'type' => 'INT',
				'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true
            ],
            'user_id' => [
                'type' => 'INT',
				'constraint' => 11
            ],
            'ul_ip_address' => [
                'type' => 'VARCHAR',
				'constraint' => '500'
            ],
            'ul_user_agent' => [
                'type' => 'VARCHAR',
                'constraint' => '10000'
            ],
            'ul_status' => [
                'type' => 'TINYINT',
				'constraint' => 1,
                'default' => 0
            ],
            'ul_expiry_datetime' => [
                'type' => 'TEXT',
                'null' => true
            ],
            'ul_created_datetime' => [
                'type' => 'DATETIME'
            ],
            'ul_updated_datetime' => [
                'type' => 'DATETIME',
                'null' => TRUE
            ]
        ]);

        $this->forge->addPrimaryKey('user_login_id');
        $this->forge->createTable('user_login', true);
    }

    public function down()
    {
        $this->forge->dropTable('user_login', true);
    }
}
