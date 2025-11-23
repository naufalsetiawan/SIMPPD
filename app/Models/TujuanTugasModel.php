<?php

namespace App\Models;

use CodeIgniter\Model;

class TujuanTugasModel extends Model
{
    protected $table            = 'tujuan_tugas';
    protected $primaryKey       = 'id_tujuan';

    protected $allowedFields = [
        'id_penugasan',
        'provinsi',
        'nama_kota',
        'lokasi',
    ];

    public function getTujuanTugas($id_penugasan, $format = 'array')
    {
        $result = $this->select('nama_kota')
            ->where('id_penugasan', $id_penugasan)
            ->findAll();

        if (!$result) {
            return ($format === 'array') ? [] : null;
        }

        if ($format === 'array1') {
            return array_column($result, 'nama_kota');
        }
        if ($format === 'array2') {
            return $result;
        }

        if (count($result) > 1) {
            $last = array_pop($result);
            $names = array_column($result, 'nama_kota');
            $output = implode(', ', $names) . ' dan ' . $last['nama_kota'];
        } else {
            $output = $result[0]['nama_kota'] ?? '';
        }

        return $output;
    }

    public function getTujuanTugasForm($id_penugasan)
    {
        $result = $this->select('provinsi, nama_kota, lokasi')
            ->where('id_penugasan', $id_penugasan)
            ->findAll();

        if (!$result) {
            return [];
        }

        return $result;
    }
}
