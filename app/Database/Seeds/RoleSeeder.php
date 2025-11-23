<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run()
    {

        $roles = [
            [
                'id_role' => '0',
                'nama_role' => 'Admin'
            ],
            // [
            //     'id_role' => '1',
            //     'nama_role' => 'Bendahara'
            // ],
            // [
            //     'id_role' => '2',
            //     'nama_role' => 'PPSPM'
            // ],
            // [
            //     'id_role' => '3',
            //     'nama_role' => 'PPK'
            // ],
            [
                'id_role' => '4',
                'nama_role' => 'Kepala Instansi'
            ],
            [
                'id_role' => '5',
                'nama_role' => 'Ketua Tim Kerja'
            ],
            // [
            //     'id_role' => '6',
            //     'nama_role' => 'PIC Tim Kerja'
            // ],
            // [
            //     'id_role' => '7',
            //     'nama_role' => 'Pelaksana'
            // ],
            [
                'id_role' => '8',
                'nama_role' => 'Kasubag Umum'
            ],

            [
                'id_role' => '9',
                'nama_role' => 'Petugas Rekap'
            ]
        ];

        $this->db->table('role')->insertBatch($roles);
    }
}
