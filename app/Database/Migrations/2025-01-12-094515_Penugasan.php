<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Penugasan extends Migration
{
    public function up()
    {
        $this->forge->addField([

            'id_penugasan' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ],

            'tahun' => [
                'type' => 'INT',
                'null' => FALSE
            ],

            'tim_kerja' => [
                'type' => 'varchar(30)',
                'null' => FALSE
            ],

            'jenis_penugasan' => [
                'type' => 'varchar(30)',
                'null' => FALSE
            ],

            'sub_jenis_penugasan' => [
                'type' => 'varchar(30)',
                'null' => FALSE
            ],

            'judul_kegiatan' => [
                'type' => 'varchar(100)',
                'null' => FALSE
            ],

            'tanggal_penugasan_string' => [
                'type' => 'varchar(250)',
                'null' => FALSE,
                'default' => '-'
            ],

            'transportasi' => [
                'type' => "VARCHAR",
                'constraint' => 30,
                'null' => FALSE,
            ],

            'anggaran' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => FALSE
            ],

            'usulan_mak_1' => [
                'type' => 'VARCHAR',
                'constraint' => 25,
                'null' => TRUE
            ],

            'usulan_mak_2' => [
                'type' => 'VARCHAR',
                'constraint' => 25,
                'null' => TRUE
            ],

            'nota_dinas' => [
                'type' => "VARCHAR",
                'constraint' => 250,
                'null' => FALSE,
            ],

            'untuk' => [
                'type' => 'TEXT'
            ],

            'ppk_nama' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => FALSE
            ],

            'ppk_nip' => [
                'type' => 'CHAR',
                'constraint' => 14,
                'null' => FALSE,
                'default' => '-'
            ],

            'created_at' => [
                'type' => 'DATETIME',
                'null' => TRUE,
            ],

            'created_by' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => FALSE
            ],

            'created_by_name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => FALSE
            ],

            'updated_at' => [
                'type' => 'DATETIME',
                'null' => TRUE,
            ],

            'updated_by' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => FALSE
            ],

            'updated_by_name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => FALSE
            ],

            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => TRUE,
            ],

        ]);


        $this->forge->addKey('id_penugasan', TRUE);

        $this->forge->createTable('penugasan');
    }
    public function down()
    {
        $this->forge->dropTable('penugasan', true);
    }
}
