<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RegionAlter1 extends Migration
{
    public function up()
    {
        $fields = [
            'code' => [
                'type' => 'VARCHAR',
                'constraint' => '120',
                'default' => 0,
                'after' => 'name'
            ]
        ];

        $this->forge->addColumn('region', $fields);
    }

    public function down()
    {
        //
    }
}
