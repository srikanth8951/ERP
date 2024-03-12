<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AssetAlterAndModifyNumberOfCompressor extends Migration
{
    public function up()
    {
        $fields = [
            'compressor_type' => [
                'name' => 'make_compressor',
                'type' => 'VARCHAR',
                'constraint' => '255'
            ],
        ];
        $this->forge->modifyColumn('asset', $fields);

        $addFields = [
            'total_compressor' => [
                'type' => 'INT',
				'constraint' => 11
            ],
        ];
        $this->forge->addColumn('asset', $addFields);
    }

    public function down()
    {
        //
    }
}
