<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $currentTimestamp = date('Y-m-d H:i:s');

        $users = [
            [
                'username' => 'admin',
                'password' => 'admin123',
                'id_pegawai' => NULL,
                'created_at' => $currentTimestamp,

            ]
        ];

        $this->db->table('user')->insertBatch($users);
    }
}
