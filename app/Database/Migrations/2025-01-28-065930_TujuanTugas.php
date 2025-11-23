<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TujuanTugas extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_tujuan' => [
                'type' => 'INT',
                'auto_increment' => true,
                'unsigned' => TRUE,
            ],

            'id_penugasan' => [
                'type' => 'INT',
                'unsigned' => TRUE,
            ],

            'provinsi' => [
                'type' => "VARCHAR",
                'constraint' => 100,
                'null' => FALSE,
            ],

            'nama_kota' => [
                'type' => "VARCHAR",
                'constraint' => 100,
                'null' => TRUE,
            ],

            'lokasi' => [
                'type' => "VARCHAR",
                'constraint' => 300,
                'null' => TRUE,
            ],
        ]);

        $this->forge->addKey('id_tujuan', TRUE);

        $this->forge->addForeignKey('id_penugasan', 'penugasan', 'id_penugasan', 'CASCADE', 'CASCADE');

        $this->forge->createTable('tujuan_tugas');
    }

    public function down()
    {

        $this->forge->dropTable('tujuan_tugas', true);
    }
}
