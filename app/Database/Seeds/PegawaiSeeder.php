<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PegawaiSeeder extends Seeder
{
    public function run()
    {
        $currentTimestamp = date('Y-m-d H:i:s');

        $pegawai = [];

        $this->db->table('pegawai')->insertBatch($pegawai);
    }
}
