<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Kota extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_kota' => [
                'type' => 'INT',
                'null' => FALSE
            ],

            'id_provinsi' => [
                'type' => 'INT',
                'null' => FALSE
            ],

            'nama_kota' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => FALSE
            ],

        ]);

        $this->forge->addKey('id_kota', TRUE);

        $this->forge->addForeignKey('id_provinsi', 'provinsi', 'id_provinsi', 'CASCADE', 'CASCADE');

        $this->forge->createTable('kota');
    }

    public function down()
    {
        $this->forge->dropTable('kota', true);
    }
}
