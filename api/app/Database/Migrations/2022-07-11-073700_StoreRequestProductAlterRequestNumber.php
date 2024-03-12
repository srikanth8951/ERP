<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class StoreRequestProductAlterRequestNumber extends Migration
{
    public function up()
    {
        $fields = [
            'request_number' => [
                'type' => 'VARCHAR',
                'constraint' => '16',
                'after' => 'request_id'
            ],
        ];

        $this->forge->addColumn('store_request', $fields);
    }

    public function down()
    {
        //
    }
}
