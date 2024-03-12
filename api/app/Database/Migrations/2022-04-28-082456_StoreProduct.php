<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class StoreProduct extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'product_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => '255'
            ],
            'description' => [
                'type' => 'VARCHAR',
                'constraint' => '1000'
            ],
            'category_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'sub_category_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0
            ],
            'attribute_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0
            ],
            'make' => [
                'type' => 'VARCHAR',
                'constraint' => '255'
            ],
            'quantity' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0
            ],
            'unit' => [
                'type' => 'VARCHAR',
                'constraint' => '30'
            ],
            'unit' => [
                'type' => 'VARCHAR',
                'constraint' => '255'
            ],
            'sku' => [
                'type' => 'VARCHAR',
                'constraint' => '255'
            ],
            'image' => [
                'type' => 'VARCHAR',
                'constraint' => '2000'
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
        
        $this->forge->addPrimaryKey('product_id');
        $this->forge->createTable('store_product', true);
    }

    public function down()
    {
        $this->forge->dropTable('store_product', true);
    }
}
