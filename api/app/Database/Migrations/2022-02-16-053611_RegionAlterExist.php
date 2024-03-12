<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RegionAlterExist extends Migration
{
    public function up()
    {
        $fields = [
            'is_exist' => [
                'type' => 'TINYINT',
				'constraint' => 1,
                'default' => 0,
                'after' => 'removed'
            ]
        ];

        $this->forge->addColumn('region', $fields);
    }

    public function down()
    {
        //
    }
}
