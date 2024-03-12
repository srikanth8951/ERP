<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ContractJobAlterTotalContractValue extends Migration
{
    public function up()
    {
        $fields = [
            'total_contract_value' => [
                'type' => 'DECIMAL',
                'constraint' => '15, 2',
                'after' => 'contract_gst_value',
                'default' => 0
            ]
        ];
        $this->forge->addColumn('contract_job', $fields);
    }

    public function down()
    {
        //
    }
}
