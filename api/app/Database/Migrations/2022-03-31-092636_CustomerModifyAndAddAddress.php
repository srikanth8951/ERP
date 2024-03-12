<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CustomerModifyAndAddAddress extends Migration
{
    public function up()
    {
        $fields = [
            'address1' => [
                'name' => 'billing_address',
                'type' => 'VARCHAR',
                'constraint' => '1000'
            ],
            'address2' => [
                'name' => 'site_address',
                'type' => 'VARCHAR',
                'constraint' => '1000'
            ],
            'country' => [
                'name' => 'billing_address_coutry',
                'type' => 'INT',
				'constraint' => 11
            ],
            'state' => [
                'name' => 'billing_address_state',
                'type' => 'INT',
				'constraint' => 11
            ],
            'city' => [
                'name' => 'billing_address_city',
                'type' => 'VARCHAR',
				'constraint' => '255'
            ],
            'pincode' => [
                'name' => 'billing_address_pincode',
                'type' => 'VARCHAR',
				'constraint' => '6'
            ],
            'mobile' => [
                'name' => 'billing_address_mobile',
                'type' => 'VARCHAR',
				'constraint' => '20'
            ],
        ];

        $this->forge->modifyColumn('customer', $fields);
        // gives ALTER TABLE `table_name` CHANGE `old_name` `new_name` TEXT

        $addFields = [
            'site_address_country' => [
                'type' => 'INT',
				'constraint' => 11
            ],
            'site_address_state' => [
                'type' => 'INT',
				'constraint' => 11
            ],
            'site_address_city' => [
                'type' => 'VARCHAR',
				'constraint' => '255'
            ],
            'site_address_pincode' => [
                'type' => 'VARCHAR',
				'constraint' => '6'
            ],
            'site_address_mobile' => [
                'type' => 'VARCHAR',
				'constraint' => '20'
            ],
        ];

        $this->forge->addColumn('customer', $addFields);
    }

    public function down()
    {
        //
    }
}
