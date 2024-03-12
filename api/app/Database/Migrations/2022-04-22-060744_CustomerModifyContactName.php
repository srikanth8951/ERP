<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CustomerModifyContactName extends Migration
{
    public function up()
    {
        $fields = [
            'name' => [
                'name' => 'billing_address_contact_name',
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'contact_name' => [
                'name' => 'site_address_contact_name',
                'type' => 'VARCHAR',
                'constraint' => '255',
                'after' => 'billing_address_mobile'
            ],
            'email' => [
                'name' => 'billing_address_email',
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
        ];
        
        $this->forge->modifyColumn('customer', $fields);
        // gives ALTER TABLE `table_name` CHANGE `old_name` `new_name` TEXT
        
        $addFields = [
            'site_address_email' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'after' => 'site_address_pincode',
            ],
        ];
        
        $this->forge->addColumn('customer', $addFields);
    }

    public function down()
    {
        //
    }
}
