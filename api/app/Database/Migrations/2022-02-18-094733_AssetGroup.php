<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AssetGroup extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'asset_group_id' => [
                'type' => 'INT',
				'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => '255'
            ],
            'parent' => [
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

        $this->forge->addPrimaryKey('asset_group_id');
        $this->forge->createTable('asset_group', true);
    }

    public function down()
    {
        $this->forge->dropTable('asset_group', true);
    }
}
