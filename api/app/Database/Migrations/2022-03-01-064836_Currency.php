<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Currency extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'currency_id' => [
                'type' => 'INT',
				'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => '255'
            ],
            'code' => [
                'type' => 'VARCHAR',
                'constraint' => '12'
            ],
            'symbol' => [
                'type' => 'VARCHAR',
                'constraint' => '6'
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

        $this->forge->addPrimaryKey('currency_id');
        $this->forge->createTable('currency', true);
    }

    public function down()
    {
        $this->forge->dropTable('currency', true);
    }
}
