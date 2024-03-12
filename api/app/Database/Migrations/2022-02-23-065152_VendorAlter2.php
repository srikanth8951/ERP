<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class VendorAlter2 extends Migration
{
    public function up()
    {
        $fields = [
            'region_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0
            ],
            'branch_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0
            ],
            'area_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0
            ]
        ];

        $this->forge->addColumn('vendor', $fields);
    }

    public function down()
    {
        //
    }
}
