<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ContractNatureAlterCode extends Migration
{
    public function up()
    {
        $fields = [
            'code' => [
                'type' => 'VARCHAR',
                'constraint' => '500',
                'after' => 'name'
            ]
        ];
        $this->forge->addColumn('contract_nature', $fields);
    }

    public function down()
    {
        //
    }
}
