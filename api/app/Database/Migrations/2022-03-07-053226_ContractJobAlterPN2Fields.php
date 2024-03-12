<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ContractJobAlterPN2Fields extends Migration
{
    public function up()
    {
        $fields = [
            'deployed_people_number' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'after' => 'contract_type_id'
            ],
            'ppm_frequency' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'after' => 'deployed_people_number'
            ]
        ];

        $this->forge->addColumn('contract_job', $fields);
    }

    public function down()
    {
        //
    }
}
