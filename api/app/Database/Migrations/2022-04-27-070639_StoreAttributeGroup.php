<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class StoreAttributeGroup extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'attribute_group_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true
            ],
            'name' => [
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
            'created_datetime' => [
                'type' => 'DATETIME'
            ],
            'updated_datetime' => [
                'type' => 'DATETIME',
                'null' => TRUE
            ]
        ]);
        
        $this->forge->addPrimaryKey('attribute_group_id');
        $this->forge->createTable('store_attribute_group', true);
    }

    public function down()
    {
        $this->forge->dropTable('store_attribute_group', true);
    }
}
