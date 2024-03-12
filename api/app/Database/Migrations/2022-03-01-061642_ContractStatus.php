<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ContractStatus extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'contract_status_id' => [
                'type' => 'INT',
				'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => '255'
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

        $this->forge->addPrimaryKey('contract_status_id');
        $this->forge->createTable('contract_status', true);
    }

    public function down()
    {
        $this->forge->dropTable('contract_status', true);
    }
}
