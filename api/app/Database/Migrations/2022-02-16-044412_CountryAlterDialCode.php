<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CountryAlterDialCode extends Migration
{
    public function up()
    {
        $fields = [
            'dial_code' => [
                'type' => 'VARCHAR',
				'constraint' => '120',
                'null' => true,
                'after' => 'code'
            ]
        ];

        $this->forge->addColumn('country', $fields);
    }

    public function down()
    {
        //
    }
}
