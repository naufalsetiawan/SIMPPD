<?php

namespace App\Models;

use CodeIgniter\Model;

class PersetujuanStModel extends Model
{
    protected $table            = 'persetujuan_st';
    protected $primaryKey       = 'id';

    protected $allowedFields = [
        'id',
        'id_penugasan',
        'file',
        'disetujui_pada'
    ];

    public function getPersetujuanStById($id_penugasan)
    {
        return $this->where('id_penugasan', $id_penugasan)
            ->select('*')
            ->get()
            ->getResultArray();
    }
}
