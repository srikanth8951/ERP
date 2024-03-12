<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Branch extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'branch_id' => [
                'type' => 'INT',
				'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true
            ],
            'region_id' => [
                'type' => 'INT',
				'constraint' => 11
            ],
            'name' => [
                'type' => 'VARCHAR',
				'constraint' => '500'
            ],
            'code' => [
                'type' => 'VARCHAR',
                'constraint' => '120'
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
                'null' => true
            ]
        ]);

        $this->forge->addPrimaryKey('branch_id');
        $this->forge->createTable('branch', true);
    }

    public function down()
    {
        $this->forge->dropTable('branch', true);
    }
}
