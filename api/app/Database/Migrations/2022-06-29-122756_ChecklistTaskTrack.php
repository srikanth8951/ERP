<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ChecklistTaskTrack extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'checklist_task_track_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true
            ],
            'checklist_track_id' => [
                'type' => 'INT',
                'constraint' => 11
            ],
            'task_id' => [
                'type' => 'INT',
                'constraint' => 11
            ],
            'task_value' => [
                'type' => 'VARCHAR',
                'constraint' => '10000'
            ],
            'created_datetime' => [
                'type' => 'DATETIME'
            ]
        ]);

        $this->forge->addPrimaryKey('checklist_task_track_id');
        $this->forge->createTable('contract_job_checklist_task_track', true);
    }

    public function down()
    {
        $this->forge->dropTable('contract_job_checklist_task_track', true);
    }
}
