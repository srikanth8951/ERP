<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AssetChecklist extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'asset_checklist_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true
            ],
            'asset_id' => [
                'type' => 'INT',
                'constraint' => 11
            ],
            'checklist_id' => [
                'type' => 'INT',
                'constraint' => 11
            ],
            'status' => [
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

        $this->forge->addPrimaryKey('asset_checklist_id');
        $this->forge->createTable('asset_checklist', true);
    }

    public function down()
    {
        $this->forge->dropTable('asset_checklist', true);
    }
}
