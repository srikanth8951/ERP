<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ContractJobAlterLocationLatLng extends Migration
{
    public function up()
    {
        $fields = [
            'geolocation_lattitude' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'after' => 'ppm_frequency'
            ],
            'geolocation_longitude' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'after' => 'geolocation_lattitude'
            ]
        ];

        $this->forge->addColumn('contract_job', $fields);
    }

    public function down()
    {
        //
    }
}
