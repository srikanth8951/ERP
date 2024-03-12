<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class User extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'user_id' => [
                'type' => 'INT',
				'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true
            ],
            'first_name' => [
                'type' => 'VARCHAR',
                'constraint' => '255'
            ],
            'last_name' => [
                'type' => 'VARCHAR',
				'constraint' => '500'
            ],
            'username' => [
                'type' => 'VARCHAR',
				'constraint' => '500'
            ],
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => '2000'
            ],
            'mobile' => [
                'type' => 'VARCHAR',
				'constraint' => '20'
            ],
            'password' => [
                'type' => 'TEXT'
            ],
            'user_type' => [
                'type' => 'INT',
				'constraint' => 11
            ],
            'permission' => [
                'type' => 'TEXT'
            ],
            'status' => [
                'type' => 'TINYINT',
				'constraint' => 1,
                'default' => 0
            ],
            'removed' => [
                'type' => 'TINYINT',
				'constraint' => 1,
                'default' => 0
            ],
            'created_datetime' => [
                'type' => 'DATETIME'
            ],
            'updated_datetime' => [
                'type' => 'DATETIME',
                'null' => TRUE
            ]
        ]);

        $this->forge->addPrimaryKey('user_id');
        $this->forge->createTable('user', true);
    }

    public function down()
    {
        $this->forge->dropTable('user', true);
    }
}
