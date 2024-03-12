<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ContractType extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'contract_type_id' => [
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

        $this->forge->addPrimaryKey('contract_type_id');
        $this->forge->createTable('contract_type', true);
    }

    public function down()
    {
        $this->forge->dropTable('contract_type', true);
    }
}
