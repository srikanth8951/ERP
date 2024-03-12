<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CountryState extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'state_id' => [
                'type' => 'INT',
				'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true
            ],
            'country_id' => [
                'type' => 'INT',
				'constraint' => 11
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => '255'
            ],
            'code' => [
                'type' => 'VARCHAR',
				'constraint' => '120'
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
                'null' => TRUE
            ]
        ]);

        $this->forge->addPrimaryKey('state_id');
        $this->forge->createTable('state', true);
    }

    public function down()
    {
        $this->forge->dropTable('state');
    }
}
