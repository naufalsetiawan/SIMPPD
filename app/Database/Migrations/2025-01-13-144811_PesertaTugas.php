<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class PesertaTugas extends Migration
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

            'nama' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => FALSE
            ],

            'nip' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => FALSE,
                'default' => '-'
            ],

            'pangkat' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],

            'jabatan' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],

            'instansi' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],

            'lokasi_berangkat' => [
                'type' => "VARCHAR",
                'constraint' => 30,
                'null' => FALSE,
            ],

            'peran' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => TRUE,
            ],

            'urutan' => [
                'type' => 'INT',

            ],

        ]);

        $this->forge->addKey('id', true);

        $this->forge->addForeignKey('id_penugasan', 'penugasan', 'id_penugasan', 'CASCADE', 'CASCADE');

        $this->forge->createTable('peserta_tugas');
    }

    public function down()
    {
        $this->forge->dropTable('peserta_tugas', true);
    }
}
