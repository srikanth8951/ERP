<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ContractJobAsset extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'contract_job_asset_id' => [
                'type' => 'INT',
				'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true
            ],
            'contract_job_id' => [
                'type' => 'INT',
                'constraint' => 11
            ],
            'asset_id' => [
                'type' => 'INT',
				'constraint' => 11
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
            'created_datetime'=> [
                'type' => 'DATETIME'
            ],
            'updated_datetime' => [
                'type' => 'DATETIME',
                'null' => TRUE
            ]
        ]);

        $this->forge->addPrimaryKey('contract_job_asset_id');
        $this->forge->createTable('contract_job_asset', true);
    }

    public function down()
    {
        $this->forge->dropTable('contract_job_asset', true);
    }
}
