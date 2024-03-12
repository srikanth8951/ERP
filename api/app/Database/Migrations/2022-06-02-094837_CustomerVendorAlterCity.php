<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CustomerVendorAlterCity extends Migration
{
    public function up()
    {
        $fields = [
            'site_address_city' => [
                'name' => 'site_address_city',
                'type' => 'INT',
				'constraint' => 11
            ],
            'billing_address_city' => [
                'name' => 'billing_address_city',
                'type' => 'INT',
				'constraint' => 11
            ],
        ];
        
        $this->forge->modifyColumn('customer', $fields);

        $fields = [
            'city' => [
                'name' => 'city',
                'type' => 'INT',
				'constraint' => 11
            ],
        ];
        
        $this->forge->modifyColumn('vendor', $fields);

        $fields = [
            'city' => [
                'name' => 'city',
                'type' => 'INT',
				'constraint' => 11
            ],
            'state' => [
                'name' => 'state',
                'type' => 'INT',
				'constraint' => 11
            ],
            'country' => [
                'name' => 'country',
                'type' => 'INT',
				'constraint' => 11
            ],

        ];
        
        $this->forge->modifyColumn('employee', $fields);
    }

    public function down()
    {
        //
    }
}
