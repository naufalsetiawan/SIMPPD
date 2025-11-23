<?php

namespace App\Models;

use CodeIgniter\Model;

class LandasanStModel extends Model
{
    protected $table            = 'landasanSt';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;

    protected $allowedFields    = [
        'id_penugasan',
        'jenis',
        'urutan',
        'butir',
    ];

    public function getLandasanStById($id_penugasan, $jenis)
    {
        return $this
            ->where('id_penugasan', $id_penugasan)
            ->where('jenis', $jenis)
            ->orderBy('urutan', 'ASC')
            ->get()
            ->getResultArray();
    }
}
