<?php

namespace App\Models;

use CodeIgniter\Model;

class TanggalTugasModel extends Model
{
    protected $table            = 'tanggal_tugas';
    protected $primaryKey       = 'id';

    protected $allowedFields = [
        'id_penugasan',
        'tanggal_mulai',
        'tanggal_selesai',
    ];

    public function getTanggalByPenugasan($id_penugasan)
    {
        return $this->where('id_penugasan', $id_penugasan)
            ->orderBy('tanggal_mulai', 'ASC')
            ->findAll();
    }

    public function getTanggalByPenugasanArray($id_penugasan)
    {
        return $this->where('id_penugasan', $id_penugasan)
            ->orderBy('tanggal_mulai', 'ASC')
            ->get()
            ->getResultArray();;
    }

    public function getAvailablePegawai($nip)
    {
        return $this->select('tanggal_tugas.id_penugasan, MIN(tanggal_tugas.tanggal_mulai) AS first_date, MAX(tanggal_tugas.tanggal_selesai) AS last_date')
            ->join('peserta_tugas pt', 'pt.id_penugasan = tanggal_tugas.id_penugasan')
            ->where('pt.nip', $nip)
            ->groupBy('tanggal_tugas.id_penugasan') // Kelompokkan berdasarkan tugas yang sama
            ->findAll();
    }


    public function getFirstAndLast($id_penugasan)
    {
        return $this->select('tanggal_tugas.id_penugasan, MIN(tanggal_tugas.tanggal_mulai) AS first_date, MAX(tanggal_tugas.tanggal_selesai) AS last_date')
            ->where('tanggal_tugas.id_penugasan', $id_penugasan)
            ->first();
    }
}
