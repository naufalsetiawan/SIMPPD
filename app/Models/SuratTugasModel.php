<?php

namespace App\Models;

use CodeIgniter\Model;

class SuratTugasModel extends Model
{
    protected $table            = 'surat_tugas';
    protected $primaryKey       = 'id';

    protected $allowedFields = [
        'id_penugasan',
        'no_surat',
        'tempat_surat',
        'tanggal_surat',
        'untuk',
        'pelaksana',
        'nama_pelaksana',
        'jabatan_pelaksana',
        'status_st'
    ];

    public function getSTByPenugasan($id_penugasan, $asArray = false)
    {
        if ($asArray) {
            return $this->where('id_penugasan', $id_penugasan)
                ->select('*')
                ->get()
                ->getRowArray();
        }
        return $this->where('id_penugasan', $id_penugasan)
            ->select('*')
            ->get()
            ->getRow();
    }
}
