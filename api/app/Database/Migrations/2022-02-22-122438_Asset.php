<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Asset extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'asset_id' => [
                'type' => 'INT',
				'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => '255'
            ],
            'group_id' => [
                'type' => 'INT',
				'constraint' => 11
            ],
            'sub_group_id' => [
                'type' => 'INT',
                'constraint' => 11
            ],
            'compressor_type' => [
                'type' => 'VARCHAR',
                'constraint' => '255'
            ],
            'make' => [
                'type' => 'VARCHAR',
                'constraint' => '255'
            ],
            'model' => [
                'type' => 'VARCHAR',
                'constraint' => '255'
            ],
            'capacity' => [
                'type' => 'INT',
                'constraint' => 11
            ],
            'measurement_unit' => [
                'type' => 'VARCHAR',
                'constraint' => '255'
            ],
            'quantity' => [
                'type' => 'INT',
                'constraint' => 11
            ],
            'location' => [
                'type' => 'VARCHAR',
                'constraint' => '255'
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
            'is_exist' => [
                'type' => 'TINYINT',
				'constraint' => 4,
                'default' => 0
            ],
            'created_datetime'=> [
                'type' => 'DATETIME'
            ],
            'updated_datetime' => [
                'type' => 'DATETIME',
                'null' => TRUE
            ]
        ]);

        $this->forge->addPrimaryKey('asset_id');
        $this->forge->createTable('asset', true);
    }

    public function down()
    {
        $this->forge->dropTable('asset', true);
    }
}
