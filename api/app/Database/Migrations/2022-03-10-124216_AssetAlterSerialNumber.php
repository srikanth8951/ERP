<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AssetAlterSerialNumber extends Migration
{
    public function up()
    {
        $fields = [
            'serial_number' => [
                'type' => 'INT',
                'constraint' => 11,
                'after' => 'capacity',
                'default' => 0
            ]
        ];
        $this->forge->addColumn('asset', $fields);
    }

    public function down()
    {
        //
    }
}
