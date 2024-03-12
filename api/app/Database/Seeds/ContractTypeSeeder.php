<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ContractTypeSeeder extends Seeder
{
    public function run()
    {
        $typeDatas = [
            [
                'name' => 'CAMC',
                'job_prefix' => 'A-',
                'status' => 1,
                'created_datetime' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'LAMC',
                'job_prefix' => 'A-',
                'status' => 1,
                'created_datetime' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Mix',
                'job_prefix' => 'MX-',
                'status' => 1,
                'created_datetime' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'O&M',
                'job_prefix' => 'OM-',
                'status' => 1,
                'created_datetime' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Warranty',
                'job_prefix' => 'W-',
                'status' => 1,
                'created_datetime' => date('Y-m-d H:i:s')
            ],
        ];

        $this->db->table('contract_type')->insertBatch($typeDatas);
    }
}
