<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Petugas extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ],

            'nama' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => FALSE
            ],

            'nip' => [
                'type' => 'CHAR',
                'constraint' => 14,
                'null' => FALSE,
                'default' => '-'
            ],

            'jabatan' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => FALSE,
            ],

            'jenis' => [
                'type' => "VARCHAR",
                'constraint' => 30,
                'null' => FALSE
            ],

            'tanggal_mulai' => [
                'type' => "VARCHAR",
                'constraint' => 30,
                'null' => FALSE
            ],

            'tanggal_selesai' => [
                'type' => "VARCHAR",
                'constraint' => 30,
                'null' => TRUE
            ]
        ]);

        $this->forge->addKey('id', TRUE);

        $this->forge->createTable('petugas');
    }

    public function down()
    {
        $this->forge->dropTable('petugas', true);
    }
}
