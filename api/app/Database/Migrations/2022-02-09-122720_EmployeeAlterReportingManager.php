<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class EmployeeAlterReportingManager extends Migration
{
    public function up()
    {
        $fields = [
            'reporting_manager' => [
                'type' => 'INT',
                'constraint' => 11,
                'after' => 'leaving_date',
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
