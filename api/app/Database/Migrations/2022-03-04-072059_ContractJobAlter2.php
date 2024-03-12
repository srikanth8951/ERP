<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ContractJobAlter2 extends Migration
{
    public function up()
    {
        $fields = [
            'job_title' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'after' => 'contract_job_id'
            ],
            'expected_gross_margin' => [
                'type' => 'INT',
                'constraint' => 11,
                'after' => 'contract_value'
            ]
        ];

        $this->forge->addColumn('contract_job', $fields);
    }

    public function down()
    {
        //
    }
}
