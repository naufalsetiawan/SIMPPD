<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class LandasanSt extends Migration
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
            ],

            'jenis' => [
                'type' => "ENUM('menimbang', 'dasar')",
                'default' => 'dasar',
                'null' => FALSE,
            ],

            'urutan' => [
                'type' => 'char(1)',
                'null' => FALSE
            ],

            'butir' => [
                'type' => 'TEXT',
            ],

        ]);

        $this->forge->addKey('id', TRUE);
        // jenis + urutan = unique
        $this->forge->addUniqueKey(['jenis', 'urutan']);


        $this->forge->addForeignKey('id_penugasan', 'penugasan', 'id_penugasan', 'CASCADE', 'CASCADE');

        $this->forge->createTable('landasanSt');
    }

    public function down()
    {
        $this->forge->dropTable('landsanSt', true);
    }
}
