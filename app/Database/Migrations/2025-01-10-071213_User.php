<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class User extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_pengguna' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ],

            'username' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => FALSE,
                'unique' => true,
            ],

            'password' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => FALSE,
            ],

            'status' => [
                'type' => "ENUM('aktif', 'nonaktif', 'diblokir')",
                'default' => 'aktif',
                'null' => FALSE,
            ],

            'created_at' => [
                'type' => 'DATETIME',
                'null' => TRUE
            ],

            'updated_at' => [
                'type' => 'DATETIME',
                'null' => TRUE
            ],

            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => TRUE,
            ],

            'id_pegawai' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'null' => TRUE,
            ],
        ]);

        $this->forge->addKey('id_pengguna', TRUE);
        $this->forge->addForeignKey('id_pegawai', 'pegawai', 'id_pegawai', 'CASCADE', 'CASCADE');
        $this->forge->createTable('user');
    }

    public function down()
    {
        $this->forge->dropTable('user', true);
    }
}
