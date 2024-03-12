<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ContractTypeAlterPrefix extends Migration
{
    public function up()
    {
        $fields = [
            'job_prefix' => [
                'type' => 'VARCHAR',
                'constraint' => '16',
                'after' => 'name'
            ]
        ];

        $this->forge->addColumn('contract_type', $fields);
    }

    public function down()
    {
        //
    }
}
