<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ContractJobPPMFrequency extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'contract_job_ppm_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true
            ],
            'contract_job_id' => [
                'type' => 'INT',
                'constraint' => 1
            ],
            'start_date' => [
                'type' => 'DATE'
            ],
            'end_date' => [
                'type' => 'DATE'
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
        $this->forge->addPrimaryKey('contract_job_ppm_id');
        $this->forge->createTable('contract_job_ppm', true);
    }

    public function down()
    {
        $this->forge->dropTable('contract_job_ppm', true);
    }
}
