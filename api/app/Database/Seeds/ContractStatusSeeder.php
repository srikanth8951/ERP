<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ContractStatusSeeder extends Seeder
{
    public function run()
    {
        $statusDatas = [
            [
                'name' => 'Warranty',
                'status' => 1,
                'created_datetime' => date('y-m-d H:i:s')
            ],
            [
                'name' => 'In Contract',
                'status' => 1,
                'created_datetime' => date('y-m-d H:i:s')
            ],
            [
                'name' => 'Not in Contract',
                'status' => 1,
                'created_datetime' => date('y-m-d H:i:s')
            ], 
            [
                'name' => 'Expired',
                'status' => 1,
                'created_datetime' => date('y-m-d H:i:s')
            ]
        ];

        $this->db->table('contract_status')->insertBatch($statusDatas);
    }
}
