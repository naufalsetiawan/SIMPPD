<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class SuratTugas extends Migration
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
                'null' => FALSE
            ],

            'no_surat' => [
                'type' => 'VARCHAR',
                'constraint' => 25,
                'default' => 'Belum Tersedia'
            ],

            'tempat_surat' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => TRUE
            ],

            'tanggal_surat' => [
                'type' => 'DATE',
                'null' => TRUE
            ],

            'pelaksana' => [
                'type' => 'VARCHAR',
                'constraint' => 10,
            ],

            'nama_pelaksana' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],

            'jabatan_pelaksana' => [
                'type' => 'VARCHAR',
                'constraint' => 100
            ],

            'status_st' => [
                'type' => 'VARCHAR',
                'constraint' => 30,
                'default' => 'belum_diajukan',
                'null' => FALSE,
            ]
        ]);

        $this->forge->addKey('id', TRUE);

        $this->forge->addForeignKey('id_penugasan', 'penugasan', 'id_penugasan', 'CASCADE', 'CASCADE');

        $this->forge->createTable('surat_tugas');
    }

    public function down()
    {
        $this->forge->dropTable('surat_tugas', true);
    }
}
