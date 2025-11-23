<?php

namespace App\Models;

use CodeIgniter\Model;

class PetugasModel extends Model
{
    protected $table            = 'petugas';
    protected $primaryKey       = 'id';

    protected $allowedFields = [
        'nama',
        'nip',
        'jabatan',
        'jenis',
        'tanggal_mulai',
        'tanggal_selesai'
    ];

    public function getPetugas()
    {
        return $this->db->table('petugas p')
            ->select('p.*')
            ->where("(p.tanggal_selesai IS NULL OR p.tanggal_selesai >= CURDATE())")
            ->get()
            ->getResultArray();
    }

    public function getPPK()
    {
        return $this->db->table('petugas p')
            ->select('p.*')
            ->where('p.jenis', 'PPK')
            ->where("(p.tanggal_selesai IS NULL OR p.tanggal_selesai >= CURDATE())")
            ->get()
            ->getResultArray();
    }

    public function getRiwayatPetugas()
    {
        return $this->db->table('petugas p')
            ->select('p.*')
            ->where("p.tanggal_selesai IS NOT NULL")
            ->where("p.tanggal_selesai < CURDATE()")
            ->orderBy('p.tanggal_selesai', 'DESC')
            ->get()
            ->getResultArray();
    }

    public function getRiwayatPPK()
    {
        return $this->db->table('petugas p')
            ->select('p.*')
            ->where('p.jenis', 'PPK')
            ->where("p.tanggal_selesai IS NOT NULL")
            ->where("p.tanggal_selesai < CURDATE()")
            ->orderBy('p.tanggal_selesai', 'DESC')
            ->get()
            ->getResultArray();
    }
}
