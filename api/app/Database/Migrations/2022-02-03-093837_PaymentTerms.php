<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class PaymentTerms extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'payment_term_id' => [
                'type' => 'INT',
				'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true
            ],
            'title' => [
                'type' => 'VARCHAR',
				'constraint' => '500'
            ],
            'description' => [
                'type' => 'VARCHAR',
				'constraint' => '10000'
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
                'null' => true
            ]
        ]);

        $this->forge->addPrimaryKey('payment_term_id');
        $this->forge->createTable('payment_term', true);
    }

    public function down()
    {
        $this->forge->dropTable('payment_term', true);
    }
}
