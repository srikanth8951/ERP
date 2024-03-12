<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Employee extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'employee_id' => [
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
            'uid' => [
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
            'address' => [
                'type' => 'VARCHAR',
                'constraint' => '2000'
            ],
            'city' => [
                'type' => 'VARCHAR',
				'constraint' => '120'
            ],  
            'state' => [
                'type' => 'VARCHAR',
                'constraint' => '500'
            ],
            'country' => [
                'type' => 'VARCHAR',
				'constraint' => '500'
            ],
            'pincode' => [
                'type' => 'VARCHAR',
                'constraint' => '6'
            ],
            'department' => [
                'type' => 'INT',
                'constraint' => 11
            ],
            'designation' => [
                'type' => 'INT',
                'constraint' => 11
            ],
            'region' => [
                'type' => 'INT',
                'constraint' => 11
            ],
            'branch' => [
                'type' => 'INT',
                'constraint' => 11
            ],
            'area' => [
                'type' => 'INT',
                'constraint' => 11
            ],
            'joining_date' => [
                'type' => 'DATE'
            ],
            'leaving_date' => [
                'type' => 'DATE'
            ],
            'work_expertise' => [
                'type' => 'INT',
				'constraint' => 11,
                'default' => 0
            ],
            'work_status' => [
                'type' => 'TINYINT',
				'constraint' => 1,
                'default' => 0
            ], 
            'user_id' => [
                'type' => 'INT',
				'constraint' => 11,
                'default' => 0
            ],  
            'user_type' => [
                'type' => 'INT',
				'constraint' => 11,
                'default' => 0
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

        $this->forge->addPrimaryKey('employee_id');
        $this->forge->createTable('employee', true);
    }

    public function down()
    {
        $this->forge->dropTable('employee', true);
    }
}
