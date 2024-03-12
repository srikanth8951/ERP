<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class WorkExpertiseAlterIsExist extends Migration
{
    public function up()
    {
        $fields = [
            'is_exist' => [
                'type' => 'TINYINT',
				'constraint' => 1,
                'default' => 0,
                'after' => 'removed'
            ]
        ];

        $this->forge->addColumn('work_expertise', $fields);
    }

    public function down()
    {
        //
    }
}
