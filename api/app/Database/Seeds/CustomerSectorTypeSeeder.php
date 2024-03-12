<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CustomerSectorTypeSeeder extends Seeder
{
    public function run()
    {
        $typeData = [
            [
                'name' => 'Govt',
                'status' => 1,
                'created_datetime' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Non Govt',
                'status' => 1,
                'created_datetime' => date('Y-m-d H:i:s')
            ],
        ];

        $this->db->table('customer_sector_type')->insertBatch($typeData);
    }
}
