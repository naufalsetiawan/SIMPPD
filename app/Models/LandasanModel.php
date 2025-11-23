<?php

namespace App\Models;

use CodeIgniter\Model;

class LandasanModel extends Model
{
    protected $table            = 'landasan';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;

    protected $allowedFields    = [
        'jenis',
        'sub_jenis_penugasan',
        'butir',
    ];

    public function getLandasanBySubJenis($sub_jenis_penugasan, $jenis)
    {
        return $this
            ->where('sub_jenis_penugasan', $sub_jenis_penugasan)
            ->where('jenis', $jenis)
            ->get()
            ->getResultArray();
    }
}
