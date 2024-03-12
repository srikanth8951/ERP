<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ContractJobAlter3 extends Migration
{
    public function up()
    {
        $fields = [
            'geolocation_range' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
                'after' => 'geolocation_longitude'
            ],
            'process_type' => [
                'type' => 'ENUM',
                'constraint' => ['new', 'update', 'renew'],
                'default' => 'new',
                'after' => 'created_user'
            ],
            'is_expired' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
                'after' => 'process_type'
            ],
            'parent' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
                'after' => 'is_expired'
            ],
            'parent_path' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'parent'
            ]
        ];

        $this->forge->addColumn('contract_job', $fields);
    }

    public function down()
    {
        //
    }
}
