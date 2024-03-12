<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ContractJobAlter1 extends Migration
{
    public function up()
    {
        $fieldsz = [
            'created_user' => [
                'type' => 'INT',
                'constraint' => 11,
                'after' => 'period_todate'
            ],
        ];

        $this->forge->addColumn('contract_job', $fieldsz);
    }

    public function down()
    {
        //
    }
}
