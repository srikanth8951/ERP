<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class EmployeeAlter1 extends Migration
{
    public function up()
    {
        $modifyFields = [
            'department' => [
                'name' => 'department_id',
                'type' => 'INT',
                'default' => 0
            ],
            'designation' => [
                'name' => 'designation_id',
                'type' => 'INT',
                'default' => 0
            ],
            'region' => [
                'name' => 'region_id',
                'type' => 'INT',
                'default' => 0
            ],
            'branch' => [
                'name' => 'branch_id',
                'type' => 'INT',
                'default' => 0
            ],
            'area' => [
                'name' => 'area_id',
                'type' => 'INT',
                'default' => 0
            ]
        ];
        $this->forge->modifyColumn('employee', $modifyFields);
    }

    public function down()
    {
        //
    }
}
