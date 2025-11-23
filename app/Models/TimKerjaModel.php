<?php

namespace App\Models;

use CodeIgniter\Model;

class TimKerjaModel extends Model
{
    protected $table            = 'tim_kerja';
    protected $primaryKey       = 'id_tim_kerja';

    protected $allowedFields = [
        'id_tim_kerja',
        'nama_tim_kerja',
    ];

    public function getAllTimKerja()
    {
        return $this->db->table('tim_kerja')
            ->select('nama_tim_kerja')
            ->get()
            ->getResultArray();
    }
}
