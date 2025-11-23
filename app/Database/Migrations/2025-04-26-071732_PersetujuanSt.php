<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class PersetujuanSt extends Migration
{
    public function up()
    {
        $this->forge->addField([

            'id' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'auto_increment' => true,
            ],

            'id_penugasan' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'null' => FALSE,
            ],

            'file' => [
                'type' => "VARCHAR",
                'constraint' => 250,
                'null' => FALSE,
            ],

            'disetujui_pada' => [
                'type' => 'DATETIME',
                'null' => FALSE,
            ]
        ]);

        $this->forge->addKey('id', TRUE);

        $this->forge->addForeignKey('id_penugasan', 'penugasan', 'id_penugasan', 'CASCADE', 'CASCADE');

        $this->forge->createTable('persetujuan_st');
    }

    public function down()
    {
        $this->forge->dropTable('persetujuan_st', true);
    }
}
