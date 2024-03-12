<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ChecklistTrackAlter1 extends Migration
{
    public function up()
    {
        $fields = [
            'updated_user' => [
                'type' => 'INT',
                'constraint' => 11,
                'after' => 'attachments'
            ]
        ];
        $this->forge->addColumn('contract_job_checklist_track', $fields);

        $fieldss = [
            'contract_job_ppm_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true
            ]
        ];
        $this->forge->modifyColumn('contract_job_checklist_track', $fieldss);
    }

    public function down()
    {
        //
    }
}
