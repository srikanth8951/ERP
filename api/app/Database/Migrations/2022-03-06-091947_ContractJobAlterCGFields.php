<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ContractJobAlterCGFields extends Migration
{
    public function up()
    {
        $fields = [
            'contract_gst_value' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
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
