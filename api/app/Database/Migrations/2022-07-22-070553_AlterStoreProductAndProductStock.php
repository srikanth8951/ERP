<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterStoreProductAndProductStock extends Migration
{
    public function up()
    {
        $fields = [
            'amount' => [
                'type' => 'DECIMAL',
                'constraint' => "15, 2",
                'after' => 'quantity'
            ],
        ];

        $this->forge->addColumn('store_product', $fields);

        $fields = [
            'updated_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'after' => 'quantity'
            ],
        ];

        $this->forge->addColumn('store_product_stock', $fields);
    }

    public function down()
    {
        //
    }
}
