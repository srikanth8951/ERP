<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AssetChecklistAlterQrcode extends Migration
{
    public function up()
    {
        $fields = [
            'qrcode' => [
                'type' => 'VARCHAR',
                'constraint' => '10000',
                'null' => true,
                'after' => 'checklist_id'
            ] 
        ];
        $this->forge->addColumn('asset_checklist', $fields);
    }

    public function down()
    {
        //
    }
}
