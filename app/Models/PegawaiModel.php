<?php

namespace App\Models;

use CodeIgniter\Model;

class PegawaiModel extends Model
{
    protected $table            = 'pegawai';
    protected $primaryKey       = 'id_pegawai';

    protected $allowedFields = [
        'nama',
        'nip',
        'pangkat',
        'jabatan',
        'tim_kerja'
    ];

    public function getAllPegawai()
    {
        return $this->findAll();
    }
    public function getFilteredData($search, $timKerja, $limit, $page)
    {
        $builder = $this->builder();
        if ($search) {
            $builder->groupStart()
                ->like('nama', $search)
                ->orLike('nip', $search)
                ->groupEnd();
        }

        if ($timKerja && $timKerja !== 'semua') {
            $builder->where('tim_kerja', $timKerja);
        }

        return $builder->limit($limit, ($page - 1) * $limit)->get()->getResultArray();
    }

    public function countFilteredData($search, $timKerja)
    {
        $builder = $this->builder();
        if ($search) {
            $builder->groupStart()
                ->like('nama', $search)
                ->orLike('nip', $search)
                ->groupEnd();
        }

        if ($timKerja && $timKerja !== 'semua') {
            $builder->where('tim_kerja', $timKerja);
        }

        return $builder->countAllResults();
    }

    public function getPegawaiById($id_pegawai)
    {
        return $this->select('*')->where('id_pegawai', $id_pegawai)->get()->getRowArray();
    }
}
