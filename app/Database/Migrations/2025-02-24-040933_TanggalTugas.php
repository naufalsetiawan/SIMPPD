<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TanggalTugas extends Migration
{
    public function up()
    {
        $this->forge->addField([

            'id' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ],

            'id_penugasan' => [
                'type' => 'INT',
                'unsigned' => TRUE,
            ],

            'tanggal_mulai' => [
                'type' => 'DATE',
                'null' => FALSE
            ],

            'tanggal_selesai' => [
                'type' => 'DATE',
                'null' => FALSE
            ],
        ]);

        $this->forge->addKey('id', TRUE);

        $this->forge->addForeignKey('id_penugasan', 'penugasan', 'id_penugasan', 'CASCADE', 'CASCADE');

        $this->forge->createTable('tanggal_tugas');
    }

    public function down()
    {
        $this->forge->dropTable('tanggal_tugas', true);
    }
}
