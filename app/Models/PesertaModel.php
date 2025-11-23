<?php

namespace App\Models;

use CodeIgniter\Model;

class PesertaModel extends Model
{
    protected $table            = 'peserta_tugas';
    protected $primaryKey       = 'id';

    protected $allowedFields = [
        'id_penugasan',
        'nama',
        'nip',
        'pangkat',
        'jabatan',
        'instansi',
        'lokasi_berangkat',
        'peran',
        'urutan'
    ];

    public function getJumlahPeserta($id_penugasan)
    {
        return $this->where('id_penugasan', $id_penugasan)
            ->select('COUNT(DISTINCT id) AS jumlah_peserta')
            ->get()
            ->getRowArray();
    }

    public function getPesertaByPenugasan($id_penugasan)
    {
        return $this->where('id_penugasan', $id_penugasan)
            ->select('*')
            ->get()
            ->getResultArray();
    }
}
