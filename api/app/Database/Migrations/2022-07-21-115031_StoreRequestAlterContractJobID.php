<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class StoreRequestAlterContractJobID extends Migration
{
    public function up()
    {
        $fields = [
            'contract_job_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'after' => 'requested_by'
            ],
        ];

        $this->forge->addColumn('store_request', $fields);
    }

    public function down()
    {
        //
    }
}
