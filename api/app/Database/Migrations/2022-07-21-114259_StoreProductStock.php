<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class StoreProductStock extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'product_stock_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true
            ],
            'product_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'operation' => [
                'type' => 'ENUM',
                'constraint' => ['plus', 'minus'],
            ],
            'quantity' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'created_datetime' => [
                'type' => 'DATETIME'
            ],
            'updated_datetime' => [
                'type' => 'DATETIME',
                'null' => true
            ]
        ]);

        $this->forge->addPrimaryKey('product_stock_id');
        $this->forge->createTable('store_product_stock', true);
    }

    public function down()
    {
        $this->forge->dropTable('store_product_stock', true);
    }
}
