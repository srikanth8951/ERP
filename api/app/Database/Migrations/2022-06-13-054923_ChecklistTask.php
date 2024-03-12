<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ChecklistTask extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'checklist_task_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true
            ],
            'checklist_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => '255'
            ],
            'type' => [
                'type' => 'INT',
                'constraint' => 11
            ],
            'division_id' => [
                'type' => 'INT',
                'constraint' => 11
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

        $this->forge->addPrimaryKey('checklist_task_id');
        $this->forge->createTable('checklist_task', true);
    }

    public function down()
    {
        $this->forge->dropTable('checklist_task', true);
    }
}
