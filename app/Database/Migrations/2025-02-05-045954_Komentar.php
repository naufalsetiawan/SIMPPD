<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Komentar extends Migration
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

            'isi' => [
                'type' => 'TEXT'
            ],

            'pembuat' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => FALSE
            ],

            'pembuat_nama' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => FALSE
            ],

            'waktu_ditambahkan' => [
                'type' => 'DATETIME',
                'null' => FALSE
            ],

        ]);


        $this->forge->addKey('id', TRUE);

        $this->forge->addForeignKey('id_penugasan', 'penugasan', 'id_penugasan', 'CASCADE', 'CASCADE');

        $this->forge->createTable('komentar');
    }

    public function down()
    {
        $this->forge->dropTable('komentar', true);
    }
}
