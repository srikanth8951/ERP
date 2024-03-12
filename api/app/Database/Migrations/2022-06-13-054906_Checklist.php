<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Checklist extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'checklist_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => '255'
            ],
            'description' => [
                'type' => 'VARCHAR',
                'constraint' => '1000'
            ],
            'type' => [
                'type' => 'INT',
                'constraint' => 11,
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

        $this->forge->addPrimaryKey('checklist_id');
        $this->forge->createTable('checklist', true);
    }

    public function down()
    {
        $this->forge->dropTable('checklist', true);
    }
}
