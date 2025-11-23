<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UserRole extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'auto_increment' => true,
                'unsigned' => TRUE,
            ],

            'id_pengguna' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'null' => FALSE,
            ],

            'id_role' => [
                'type' => 'INT',
                'null' => FALSE,
            ],

            'created_at' => [
                'type' => 'DATETIME',
                'null' => TRUE,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => TRUE,
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => TRUE,
            ],

        ]);

        $this->forge->addKey('id', true);

        // Add foreign key constraints
        $this->forge->addForeignKey('id_pengguna', 'user', 'id_pengguna', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('id_role', 'role', 'id_role', 'CASCADE', 'CASCADE');

        // Add unique key for email and role combination
        $this->forge->addUniqueKey(['id_pengguna', 'id_role']);

        $this->forge->createTable('user_role');
    }

    public function down()
    {
        $this->forge->dropTable('user_role', true);
    }
}
