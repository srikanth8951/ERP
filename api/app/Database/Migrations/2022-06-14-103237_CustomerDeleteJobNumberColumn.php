<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CustomerDeleteJobNumberColumn extends Migration
{
    public function up()
    {
        $this->forge->dropColumn('customer', 'job_number'); // to drop one single column
    }

    public function down()
    {
        //
    }
}
