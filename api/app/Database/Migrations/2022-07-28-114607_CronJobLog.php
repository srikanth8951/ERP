<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class CronJobLog extends Migration
{
    public function up()
    {
        $config = $this->_getConfig();

        $this->forge->addField([
            'id'    => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true
            ],
            'name'  => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true
            ],
            'type'  => [
                'type' => 'VARCHAR',
                'constraint' => 25,
                'null' => false
            ],
            'action'    => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => false
            ],
            'environment'   => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => false
            ],
            'output'    => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true
            ],
            'error'     => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true
            ],
            'start_at'  => [
                'type' => 'DATETIME',
                'null' => false
            ],
            'end_at'    => [
                'type' => 'DATETIME',
                'null' => false
            ],
            'duration'  => [
                'type' => 'time',
                'null' => false
            ],
            'test_time' => [
                'type' => 'DATETIME',
                'null' => true
            ],
            'status' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0
            ],
            'created_at' => [
                'type'    => 'DATETIME'
            ],
            'updated_at' => [
                'type'    => 'DATETIME',
                'null' => true,
            ],
            'deleted_at'    => [
                'type' => 'DATETIME',
                'null' => true
            ]
        ]);

        $this->forge->addPrimaryKey('id');

        $this->forge->createTable($config->tableName, true);
    }

    public function down()
    {
        $config = $this->_getConfig();

		$this->forge->dropTable($config->tableName, true);
    }

    private function _getConfig()
    {
        $config = config( 'CronJob' );

        if( !$config )
        {
            $config = new \Daycry\CronJob\Config\CronJob();
        }

        return $config;
    }
}
