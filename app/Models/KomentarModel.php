<?php

namespace App\Models;

use CodeIgniter\Model;

class KomentarModel extends Model
{
    protected $table            = 'komentar';
    protected $primaryKey       = 'id';

    protected $allowedFields = [
        'id_penugasan',
        'isi',
        'pembuat',
        'pembuat_nama',
        'waktu_ditambahkan'
    ];

    public function getAllKomentarbyPenugasan($id_penugasan)
    {
        return $this->where('id_penugasan', $id_penugasan)
            ->select('komentar.*')
            ->get()
            ->getResultArray();
    }
}
