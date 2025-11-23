<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->db->query('SET FOREIGN_KEY_CHECKS = 0;'); // Disable FK checks

        $this->call('RoleSeeder');
        $this->call('UserSeeder');
        $this->call('UserRoleSeeder');

        $this->db->query('SET FOREIGN_KEY_CHECKS = 1;'); // Enable FK checks
    }
}
