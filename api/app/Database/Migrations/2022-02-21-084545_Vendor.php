<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Vendor extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'vendor_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true
            ],
            'organization_name' => [
                'type' => 'VARCHAR',
				'constraint' => '255'
            ],
            'code' => [
                'type' => 'VARCHAR',
				'constraint' => '255'
            ],
            'group' => [
                'type' => 'VARCHAR',
				'constraint' => '255'
            ],
            'contact_name' => [
                'type' => 'VARCHAR',
				'constraint' => '255'
            ],
            'address1' => [
                'type' => 'VARCHAR',
				'constraint' => '1000'
            ],
            'address2' => [
                'type' => 'VARCHAR',
				'constraint' => '1000'
            ],
            'country' => [
                'type' => 'INT',
				'constraint' => 11
            ],
            'state' => [
                'type' => 'INT',
				'constraint' => 11
            ],
            'city' => [
                'type' => 'VARCHAR',
				'constraint' => '255'
            ],
            'pincode' => [
                'type' => 'VARCHAR',
				'constraint' => '6'
            ],
            'email' => [
                'type' => 'VARCHAR',
				'constraint' => '255'
            ],
            'mobile' => [
                'type' => 'VARCHAR',
				'constraint' => '20'
            ],
            'website' => [
                'type' => 'VARCHAR',
				'constraint' => '2000'
            ],
            'gst_number' => [
                'type' => 'VARCHAR',
				'constraint' => '15'
            ],
            'pan_number' => [
                'type' => 'VARCHAR',
				'constraint' => '10'
            ],
            'payment_term' => [
                'type' => 'INT',
				'constraint' => 11
            ],
            'bank_name' => [
                'type' => 'VARCHAR',
				'constraint' => '255'
            ],
            'bank_account_person_name' => [
                'type' => 'VARCHAR',
				'constraint' => '255'
            ],
            'bank_account_number' => [
                'type' => 'VARCHAR',
				'constraint' => '18'
            ],
            'bank_branch_name' => [
                'type' => 'VARCHAR',
				'constraint' => '255'
            ],
            'bank_ifsc_code' => [
                'type' => 'VARCHAR',
				'constraint' => '11'
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

        $this->forge->addPrimaryKey('vendor_id');
        $this->forge->createTable('vendor', true);
    }

    public function down()
    {
        $this->forge->dropTable('vendor', true);
    }
}
