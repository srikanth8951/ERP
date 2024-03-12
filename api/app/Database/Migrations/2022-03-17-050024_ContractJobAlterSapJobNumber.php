<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ContractJobAlterSapJobNumber extends Migration
{
    public function up()
    {
        $fields = [
            'sap_job_number' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'after' => 'job_number',
            ]
        ];

        $this->forge->addColumn('contract_job', $fields);
    }

    public function down()
    {
        //
    }
}
