<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class VendorEvaluation extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'vendor_evaluation_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true
            ],
            'vendor_id' => [
                'type' => 'INT',
                'constraint' => 11
            ],
            'file' => [
                'type' => 'VARCHAR',
                'constraint' => 255
            ],
            'created_datetime' => [
                'type' => 'DATETIME'
            ]
        ]);

        $this->forge->addPrimaryKey('vendor_evaluation_id');
        $this->forge->createTable('vendor_evaluation', true);
    }

    public function down()
    {
        $this->forge->dropTable('vendor_evaluation', true);
    }
}
