<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TimKerja extends Migration
{
    public function up()
    {
        $this->forge->addField([

            'id_tim_kerja' => [
                'type' => 'INT',
                'auto_increment' => TRUE,
                'null' => FALSE
            ],

            'nama_tim_kerja' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => FALSE
            ],
        ]);

        $this->forge->addKey('id_tim_kerja', TRUE);

        $this->forge->createTable('tim_kerja');
    }

    public function down()
    {
        $this->forge->dropTable('tim_kerja', true);
    }
}
