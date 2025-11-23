<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserRoleSeeder extends Seeder
{
    public function run()
    {
        $currentTimestamp = date('Y-m-d H:i:s');

        $user_roles = [
            [
                'id_pengguna' => '1',
                'id_role' => '0',
                'created_at' => $currentTimestamp,

            ]
        ];

        $this->db->table('user_role')->insertBatch($user_roles);
    }
}
