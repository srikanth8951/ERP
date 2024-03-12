<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ChecklistTrack extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'checklist_track_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true
            ],
            'contract_job_id' => [
                'type' => 'INT',
                'constraint' => 11
            ],
            'contract_job_asset_id' => [
                'type' => 'INT',
                'constraint' => 11
            ],
            'contract_job_ppm_id' => [
                'type' => 'INT',
                'constraint' => 11
            ],
            'asset_checklist_id' => [
                'type' => 'INT',
                'constraint' => 11
            ],
            'attachments' => [
                'type' => 'VARCHAR',
                'constraint' => '10000'
            ],
            'status' => [
                'type' => 'INT',
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

        $this->forge->addPrimaryKey('checklist_track_id');
        $this->forge->createTable('contract_job_checklist_track', true);
    }

    public function down()
    {
        $this->forge->dropTable('contract_job_checklist_track', true);
    }
}
