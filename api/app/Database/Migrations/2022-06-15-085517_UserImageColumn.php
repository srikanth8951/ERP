<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UserImageColumn extends Migration
{
    public function up()
    {
        $fields = [
            'image' => [
                'type' => 'TEXT',
                'after' => 'username'
            ]
        ];

        $this->forge->addColumn('user', $fields);
    }

    public function down()
    {
        //
    }
}
