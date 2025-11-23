<?php

namespace App\Models;

use CodeIgniter\Model;

class PenugasanModel extends Model
{
    protected $table            = 'penugasan';
    protected $primaryKey       = 'id_penugasan';

    protected $allowedFields = [
        'tahun',
        'tim_kerja',
        'jenis_penugasan',
        'sub_jenis_penugasan',
        'judul_kegiatan',
        'tanggal_penugasan_string',
        'transportasi',
        'anggaran',
        'pengajuan_spd',
        'usulan_mak_1',
        'usulan_mak_2',
        'nota_dinas',
        'untuk',
        'ppk_nama',
        'ppk_nip',
        'created_at',
        'created_by',
        'created_by_name',
        'updated_at',
        'updated_by',
        'updated_by_name'
    ];

    public function validateTugas($data)
    {
        $validation = \Config\Services::validation();

        $validation->setRules([
            'tahun' => 'required',
            'tim_kerja' => 'required',
            'jenis_penugasan' => 'required',
            'sub_jenis_penugasan' => 'required',
            'judul_kegiatan' => 'required',
            'tujuan' => 'required',
            'transportasi' => 'required',
            'anggaran' => 'required',
            'usulan_mak_1' => 'required',
        ]);

        if ($validation->run($data)) {
            return true;
        }

        return $validation->getErrors();
    }

    public function getPenugasanByTimKerja(
        $id_pengguna,
        $all = false,
        $limit = 10,
        $offset = 0,
        $filterTimKerja = null,
        $filterSubJenis = null,
        $filterStatus = null,
        $filterUrutan = null
    ) {
        $db = \Config\Database::connect();
        $builder = $db->table('penugasan p')
            ->select('p.*, s.no_surat, s.status_st, COUNT(DISTINCT pt.id) AS jumlah_peserta')
            ->join('surat_tugas s', 'p.id_penugasan = s.id_penugasan', 'left')
            ->join('peserta_tugas pt', 'p.id_penugasan = pt.id_penugasan', 'left');

        if (!$all) {
            $subQuery = $db->table('pegawai i')
                ->select('i.tim_kerja')
                ->join('user u', 'i.id_pegawai = u.id_pegawai', 'left')
                ->where('u.id_pengguna', $id_pengguna)
                ->getCompiledSelect();

            $builder->where("p.tim_kerja = ($subQuery)", null, false);
        }

        if (!empty($filterTimKerja)) {
            $builder->where('p.tim_kerja', $filterTimKerja);
        }

        if (!empty($filterSubJenis)) {
            $builder->where('p.sub_jenis_penugasan', $filterSubJenis);
        }
        if (!empty($filterStatus)) {
            if (is_array($filterStatus)) {
                $builder->whereIn('s.status_st', $filterStatus);
            } else {
                $builder->where('s.status_st', $filterStatus);
            }
        }

        if ($filterUrutan === 'terlama') {
            $builder->orderBy('p.created_at', 'ASC');
        } elseif ($filterUrutan === 'A-Z') {
            $builder->orderBy('p.nama_kegiatan', 'ASC');
        } elseif ($filterUrutan === 'Z-A') {
            $builder->orderBy('p.nama_kegiatan', 'DESC');
        } else {
            $builder->orderBy('p.created_at', 'DESC');
        }

        $builder->groupBy('p.id_penugasan')
            ->limit($limit, $offset);

        return $builder->get()->getResultArray();
    }


    public function getPenugasanDashboardInfo($id_pengguna, $role)
    {
        $db = \Config\Database::connect();

        // KEPALA
        if ($role == 4) {
            return $db->table('penugasan p')
                ->select('p.*, s.no_surat, s.status_st, COUNT(DISTINCT pt.id) AS jumlah_peserta')
                ->join('surat_tugas s', 'p.id_penugasan = s.id_penugasan', 'left')
                ->join('peserta_tugas pt', 'p.id_penugasan = pt.id_penugasan', 'left')
                ->where('s.status_st =', 'menunggu persetujuan')
                ->groupBy('p.id_penugasan')
                ->get()
                ->getResultArray();
        }

        // KASUBAG
        if ($role == 8) {
            return $db->table('penugasan p')
                ->select('p.*, s.no_surat, s.status_st, COUNT(DISTINCT pt.id) AS jumlah_peserta')
                ->join('surat_tugas s', 'p.id_penugasan = s.id_penugasan', 'left')
                ->join('peserta_tugas pt', 'p.id_penugasan = pt.id_penugasan', 'left')
                ->where('s.status_st =', 'sedang diajukan')
                ->groupBy('p.id_penugasan')
                ->get()
                ->getResultArray();
        }

        // KETUA TIM KERJA
        if ($role == 5) {
            $subQuery = $db->table('pegawai i')
                ->select('i.tim_kerja')
                ->join('user u', 'i.id_pegawai = u.id_pegawai')
                ->where('u.id_pengguna', $id_pengguna)
                ->getCompiledSelect();

            $maxCreatedAt = $db->table('penugasan')
                ->selectMax('created_at')
                ->where("tim_kerja = ($subQuery)", null, false)
                ->get()
                ->getRow();

            if ($maxCreatedAt && $maxCreatedAt->created_at !== null) {
                return $db->table('penugasan p')
                    ->select('p.*, s.no_surat, s.status_st, COUNT(DISTINCT pt.id) AS jumlah_peserta')
                    ->join('surat_tugas s', 'p.id_penugasan = s.id_penugasan', 'left')
                    ->join('peserta_tugas pt', 'p.id_penugasan = pt.id_penugasan', 'left')
                    ->where("p.tim_kerja = ($subQuery)", null, false)
                    ->where('p.created_at', $maxCreatedAt->created_at)
                    ->groupBy('p.id_penugasan')
                    ->get()
                    ->getResultArray();
            }
        }
        return [];
    }


    public function getPenugasanCount($id_pengguna, $roles)
    {
        $builder = $this->db->table('penugasan p')
            ->join('surat_tugas s', 'p.id_penugasan = s.id_penugasan', 'left')
            ->join('peserta_tugas pt', 'p.id_penugasan = pt.id_penugasan', 'left');

        $allowed_roles_for_count = [4, 0, 8, 9];

        if (array_intersect($allowed_roles_for_count, $roles)) {
            return $builder->select('p.id_penugasan')
                ->groupBy('p.id_penugasan')
                ->countAllResults();
        }

        $subQuery = $this->db->table('pegawai i')
            ->select('i.tim_kerja')
            ->join('user u', 'i.username = u.username')
            ->where('u.id_pengguna', $id_pengguna)
            ->getCompiledSelect();

        return $builder->select('p.id_penugasan')
            ->where("p.tim_kerja = ($subQuery)", null, false)
            ->groupBy('p.id_penugasan')
            ->countAllResults();
    }

    public function getPenugasan()
    {
        $db = \Config\Database::connect();
        return $db->table('penugasan p')
            ->select('p.*, s.no_surat, s.status_st, COUNT(DISTINCT pt.id) AS jumlah_peserta')
            ->join('surat_tugas s', 'p.id_penugasan = s.id_penugasan', 'left')
            ->join('peserta_tugas pt', 'p.id_penugasan = pt.id_penugasan', 'left')
            ->groupBy('p.id_penugasan');
    }

    public function getRekap()
    {
        $db = \Config\Database::connect();
        return $db->table('penugasan p')
            ->select('p.*, s.no_surat, s.tanggal_surat, s.status_st')
            ->join('surat_tugas s', 'p.id_penugasan = s.id_penugasan', 'left')
            ->join('peserta_tugas pt', 'p.id_penugasan = pt.id_penugasan', 'left')
            ->groupBy('p.id_penugasan')
            ->get()
            ->getResultArray();
    }

    public function getRekapFiltered($filterType, $bulan, $tahun)
    {
        $builder = $this->db->table('penugasan p')
            ->select('p.*, s.no_surat, s.status_st, s.tanggal_surat')
            ->join('surat_tugas s', 'p.id_penugasan = s.id_penugasan', 'left')
            ->where('s.status_st', 'disetujui');

        if ($filterType === 'bulan') {
            $builder->where('MONTH(s.tanggal_surat)', $bulan)
                ->where('YEAR(s.tanggal_surat)', $tahun);
        } elseif ($filterType === 'tahun') {
            $builder->where('YEAR(s.tanggal_surat)', $tahun);
        }

        return $builder->get()->getResultArray();
    }

    public function getLastPenugasan($limit = 5)
    {
        $db = \Config\Database::connect();

        return $db->table('penugasan p')
            ->select('p.*, s.no_surat, s.status_st, COUNT(DISTINCT pt.id) AS jumlah_peserta')
            ->join('surat_tugas s', 'p.id_penugasan = s.id_penugasan', 'left')
            ->join('peserta_tugas pt', 'p.id_penugasan = pt.id_penugasan', 'left')
            ->where('s.status_st', 'Disetujui')
            ->groupBy('p.id_penugasan')
            ->orderBy('p.created_at', 'DESC')
            ->limit($limit)
            ->get()
            ->getResultArray();
    }

    public function getCountByStatus()
    {
        return $this->select('status_st, COUNT(*) as total')
            ->join('surat_tugas', 'penugasan.id_penugasan = surat_tugas.id_penugasan')
            ->groupBy('status_st')
            ->get()
            ->getResultArray();
    }

    public function getCountByJenisDanStatus($jenisPenugasan = null, $statusST = null, $id_pengguna = null, $role = null)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('penugasan')
            ->selectCount('penugasan.id_penugasan', 'total')
            ->join('surat_tugas', 'penugasan.id_penugasan = surat_tugas.id_penugasan');

        $currentYear = date('Y');
        $builder->where('YEAR(penugasan.created_at)', $currentYear);

        if ($statusST !== null) {
            $builder->where('LOWER(surat_tugas.status_st)', strtolower($statusST));
        }

        if ($jenisPenugasan !== null) {
            $builder->where('LOWER(penugasan.jenis_penugasan)', strtolower($jenisPenugasan));
        }

        if ($role == 5 && $id_pengguna !== null) {
            // Ambil tim kerja user
            $subQuery = $db->table('pegawai i')
                ->select('i.tim_kerja')
                ->join('user u', 'i.id_pegawai = u.id_pegawai')
                ->where('u.id_pengguna', $id_pengguna)
                ->getCompiledSelect();

            $builder->where("penugasan.tim_kerja = ($subQuery)", null, false);
        }

        return $builder->get()->getRowArray();
    }

    public function deleteTugas($id_penugasan)
    {
        return $this->db->table('penugasan')->delete(['id_penugasan' => $id_penugasan]);
    }

    public function getPenugasanAndPeserta()
    {
        $db = \Config\Database::connect();

        return $db->table('penugasan p')
            ->select('p.id_penugasan, p.tanggal_mulai, p.tanggal_selesai, pt.* ')
            ->join('peserta_tugas pt', 'p.id_penugasan = pt.id_penugasan', 'right')
            ->get()
            ->getResultArray();
    }

    public function getCountBySubJenis()
    {
        return $this->select('sub_jenis_penugasan, COUNT(*) as total')
            ->join('surat_tugas s', 'penugasan.id_penugasan = s.id_penugasan')
            ->where('s.status_st', 'disetujui')
            ->where('YEAR(s.tanggal_surat)', date('Y'))
            ->groupBy('sub_jenis_penugasan')
            ->get()
            ->getResultArray();
    }


    public function getApprovedCountByMonth()
    {
        return $this->select('MONTH(s.tanggal_surat) as bulan, COUNT(*) as total')
            ->join('surat_tugas s', 'penugasan.id_penugasan = s.id_penugasan')
            ->where('s.status_st', 'disetujui')
            ->where('YEAR(s.tanggal_surat)', date('Y'))
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get()
            ->getResultArray();
    }
}
