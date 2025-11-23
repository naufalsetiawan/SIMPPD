<?php

namespace App\Models;

use CodeIgniter\Model;

class KotaModel extends Model
{
    protected $table            = 'kota';
    protected $primaryKey       = 'id_kota';

    protected $allowedFields = [
        'id_kota',
        'id_provinsi',
        'nama_kota'
    ];

    public function getAllKota()
    {
        $db = \Config\Database::connect();
        return $db->table('kota k')
            ->select('k.*, p.nama_provinsi')
            ->join('provinsi p', 'k.id_provinsi = p.id_provinsi')
            ->orderBy('p.nama_provinsi', 'ASC')
            ->orderBy('k.nama_kota', 'ASC')
            ->get()
            ->getResultArray();
    }

    public function getPaginatedKota($perPage, $currentPage)
    {
        return $this->select('kota.id_kota, kota.nama_kota, provinsi.nama_provinsi')
            ->join('provinsi', 'provinsi.id_provinsi = kota.id_provinsi')
            ->paginate($perPage, 'kota', $currentPage);
    }
}
