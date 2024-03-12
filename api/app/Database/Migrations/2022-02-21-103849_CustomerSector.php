<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CustomerSector extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'customer_sector_id' => [
                'type' => 'INT',
				'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true
            ],
            'title' => [
                'type' => 'VARCHAR',
                'constraint' => '255'
            ],
            'type_id' => [
                'type' => 'INT',
                'constraint' => 11
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

        $this->forge->addPrimaryKey('customer_sector_id');
        $this->forge->createTable('customer_sector', true);
    }

    public function down()
    {
        $this->forge->dropTable('customer_sector', true);
    }
}
