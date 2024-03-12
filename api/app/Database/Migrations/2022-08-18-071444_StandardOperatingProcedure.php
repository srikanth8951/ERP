<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class StandardOperatingProcedure extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'standard_operating_procedure_id' => [
                'type' => 'INT',
				'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true
            ],
            'title' => [
                'type' => 'VARCHAR',
				'constraint' => '500'
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true
            ],
            'status' => [
                'type' => 'TINYINT',
				'constraint' => 1,
                'default' => 0
            ],
            'removed' => [
                'type' => 'TINYINT',
				'constraint' => 1,
                'default' => 0
            ],
            'created_datetime' => [
                'type' => 'DATETIME'
            ],
            'updated_datetime' => [
                'type' => 'DATETIME',
                'null' => true
            ]
        ]);
        $this->forge->addPrimaryKey('standard_operating_procedure_id');
        $this->forge->createTable('standard_operating_procedure', true);
    }

    public function down()
    {
        $this->forge->dropTable('standard_operating_procedure', true);
    }
}
