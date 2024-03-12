<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class StoreRequestAndStoreRequestProduct extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'request_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true
            ],
            'requested_by' => [
                'type' => 'INT',
                'constraint' => 11
            ],
            'title' => [
                'type' => 'VARCHAR',
                'constraint' => '255'
            ],
            'description' => [
                'type' => 'VARCHAR',
                'constraint' => '255'
            ],
            'status' => [
                'type' => 'INT',
                'constraint' => 11
            ],
            'removed' => [
                'type' => 'INT',
                'constraint' => 11
            ],
            'created_datetime' => [
                'type' => 'DATETIME'
            ],
            'updated_datetime' => [
                'type' => 'DATETIME',
                'null' => true
            ]
        ]);

        $this->forge->addPrimaryKey('request_id');
        $this->forge->createTable('store_request', true);

        $this->forge->addField([
            'request_product_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true
            ],
            'request_id' => [
                'type' => 'INT',
                'constraint' => 11
            ],
            'product_id' => [
                'type' => 'INT',
                'constraint' => 11
            ],
            'quantity' => [
                'type' => 'INT',
                'constraint' => 11
            ],
        ]);

        $this->forge->addPrimaryKey('request_product_id');
        $this->forge->createTable('store_request_product', true);
    }

    public function down()
    {
        $this->forge->dropTable('store_request', true);
        $this->forge->dropTable('store_request_product', true);
    }
}
