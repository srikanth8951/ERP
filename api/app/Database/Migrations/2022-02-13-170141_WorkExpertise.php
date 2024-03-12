<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class WorkExpertise extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'work_expertise_id' => [
                'type' => 'INT',
				'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true
            ],
            'name' => [
                'type' => 'VARCHAR',
				'constraint' => '500'
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

        $this->forge->addPrimaryKey('work_expertise_id');
        $this->forge->createTable('work_expertise', true);
    }

    public function down()
    {
        $this->forge->dropTable('work_expertise', true);
    }
}
