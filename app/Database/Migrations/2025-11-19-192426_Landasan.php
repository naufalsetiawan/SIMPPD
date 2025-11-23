<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Landasan extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ],

            'jenis' => [
                'type' => "ENUM('menimbang', 'dasar')",
                'default' => 'dasar',
                'null' => FALSE,
            ],

            'sub_jenis_penugasan' => [
                'type' => 'varchar(30)',
                'null' => FALSE
            ],
            'butir' => [
                'type' => 'TEXT',
            ],

        ]);

        $this->forge->addKey('id', TRUE);

        $this->forge->createTable('landasan');
    }

    public function down()
    {
        $this->forge->dropTable('landasan', true);
    }
}
