<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Role extends Migration
{
    public function up()
    {
        $this->forge->addField([

            'id_role' => [
                'type' => 'INT',
                'null' => FALSE
            ],

            'nama_role' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => FALSE
            ],

        ]);

        $this->forge->addKey('id_role', TRUE);

        $this->forge->createTable('role');
    }

    public function down()
    {
        $this->forge->dropTable('role', true);
    }
}
