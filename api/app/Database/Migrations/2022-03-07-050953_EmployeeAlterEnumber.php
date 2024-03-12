<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class EmployeeAlterEnumber extends Migration
{
    public function up()
    {
        $fields = [
            'employee_number' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'after' => 'employee_id'
            ]
        ];

        $this->forge->addColumn('employee', $fields);
    }

    public function down()
    {
        //
    }
}
