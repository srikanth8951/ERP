<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Setting extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'setting_id' => [
                'type' => 'INT',
				'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true
            ],
            'code' => [
                'type' => 'VARCHAR',
                'constraint' => '255'
            ],
            'keyword' => [
                'type' => 'VARCHAR',
				'constraint' => '20'
            ],
            'value' => [
                'type' => 'VARCHAR',
                'constraint' => '10000'
            ],
            'is_serialized' => [
                'type' => 'TEXT'
            ],
            'created_datetime'=> [
                'type' => 'DATETIME'
            ],
            'updated_datetime' => [
                'type' => 'DATETIME',
                'null' => TRUE
            ]
        ]);

        $this->forge->addPrimaryKey('setting_id');
        $this->forge->createTable('setting', true);
    }

    public function down()
    {
        $this->forge->dropTable('setting', true);
    }
}
