<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class EmployeeAlterVendor extends Migration
{
    public function up()
    {
        $fields = [
            'vendor_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'after' => 'reporting_manager',
                'default' => 0
            ]
        ];
        
        $this->forge->addColumn('employee', $fields);
    }

    public function down()
    {
        //
    }
}
