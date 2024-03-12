<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class StoreRequestApproval extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'request_approval_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true
            ],
            'request_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'is_approved' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0
            ],
            'approved_by' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'is_rejected' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0
            ],
            'rejected_by' => [
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

        $this->forge->addPrimaryKey('request_approval_id');
        $this->forge->createTable('store_request_approval', true);
    }

    public function down()
    {
        $this->forge->dropTable('store_request_approval', true);
    }
}
