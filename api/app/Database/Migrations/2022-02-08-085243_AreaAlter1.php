<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AreaAlter1 extends Migration
{
    public function up()
    {
        $fields = [
            'region_id' =>[
                'type' => 'VARCHAR',
                'constraint' => '120',
                'default' => 0,
                'after' => 'area_id'
            ],
            'branch_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'after' => 'region_id'
            ],
            'code' => [
                'type' => 'VARCHAR',
                'constraint' => '120',
                'default' => 0,
                'after' => 'name'
            ]
        ];

        $this->forge->addColumn('area', $fields);
    }

    public function down()
    {
        //
    }
}
