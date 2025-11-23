<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Pegawai extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_pegawai' => [
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

            'pangkat' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => 'false',
            ],

            'jabatan' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => 'false',
            ],

            'tim_kerja' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => 'false',
            ],

            'created_at' => [
                'type' => 'DATETIME',
                'null' => TRUE
            ],

        ]);

        $this->forge->addKey('id_pegawai', TRUE);

        $this->forge->createTable('pegawai');
    }

    public function down()
    {
        $this->forge->dropTable('pegawai', true);
    }
}
