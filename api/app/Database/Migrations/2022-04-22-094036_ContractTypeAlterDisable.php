<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ContractTypeAlterDisable extends Migration
{
    public function up()
    {
        $addFields = [
            'disable' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'after' => 'removed',
            ],
        ];
        
        $this->forge->addColumn('contract_type', $addFields);
    }

    public function down()
    {
        //
    }
}
