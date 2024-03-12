<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ContractJob extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'contract_job_id' => [
                'type' => 'INT',
				'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true
            ],
            'job_number' => [
                'type' => 'VARCHAR',
                'constraint' => '255'
            ],
            'contract_nature_id' => [
                'type' => 'INT',
				'constraint' => 11
            ],
            'contract_type_id' => [
                'type' => 'INT',
                'constraint' => 11
            ],
            'purchase_order_number' => [
                'type' => 'VARCHAR',
                'constraint' => '255'
            ],
            'contract_currency_id' => [
                'type' => 'INT',
                'constraint' => 11
            ],
            'contract_value' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2'
            ],
            'customer_account_manager_id' => [
                'type' => 'INT',
                'constraint' => 11
            ],
            'engineer_id' => [
                'type' => 'INT',
                'constraint' => 11
            ],
            'customer_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0
            ],
            'contract_status_id' => [
                'type' => 'INT',
                'constraint' => 11
            ],
            'period' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true
            ],
            'period_fromdate' => [
                'type' => 'DATE',
                'null' => true
            ],
            'period_todate' => [
                'type' => 'DATE',
                'null' => true
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

        $this->forge->addPrimaryKey('contract_job_id');
        $this->forge->createTable('contract_job', true);
    }

    public function down()
    {
        $this->forge->dropTable('contract_job', true);
    }
}
