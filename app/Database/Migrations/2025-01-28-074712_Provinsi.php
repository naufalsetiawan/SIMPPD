<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Provinsi extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_provinsi' => [
                'type' => 'INT',
                'null' => FALSE
            ],

            'nama_provinsi' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => FALSE
            ],

        ]);

        $this->forge->addKey('id_provinsi', TRUE);

        $this->forge->createTable('provinsi');
    }

    public function down()
    {
        $this->forge->dropTable('provinsi', true);
    }
}
