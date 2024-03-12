<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ChecklistAlterGroup extends Migration
{
    public function up()
    {
        $addFields = [
            'group' => [
                'type' => 'ENUM',
                'constraint' => ['normal','ppm','daily'],
                'default' => 'normal',
                'after' => 'checklist_id'
            ]
        ];
        $this->forge->addColumn('checklist', $addFields);
    }

    public function down()
    {
        //
    }
}
