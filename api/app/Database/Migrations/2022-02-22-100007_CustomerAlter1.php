<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CustomerAlter1 extends Migration
{
    public function up()
    {
        $modifyFields = [
            'sector' => [
                'type' => 'INT',
				'constraint' => 11
            ]
        ];

        $this->forge->modifyColumn('customer', $modifyFields);

        $addFields = [
            'is_exist' => [
                'type' => 'TINYINT',
				'constraint' => 1,
                'default' => 0,
                'after' => 'removed'
            ]
        ];

        $this->forge->addColumn('customer', $addFields);
    }

    public function down()
    {
        //
    }
}
