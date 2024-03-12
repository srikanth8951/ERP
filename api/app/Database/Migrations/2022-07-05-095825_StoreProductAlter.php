<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class StoreProductAlter extends Migration
{
    public function up()
    {
        $this->forge->dropColumn('store_product', ['description' ,'attribute_id', 'make', 'sku', 'image'] ); // to drop one single column

        $fields = [
            'created_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'after' => 'product_id'
            ],
            'specification' => [
                'type' => 'TEXT',
                'after' => 'unit'
            ]
        ];

        $this->forge->addColumn('store_product', $fields);
    }

    public function down()
    {
        //
    }
}
