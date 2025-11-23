<?php

namespace App\Controllers;

use App\Models\PenugasanModel;
use App\Models\TanggalTugasModel;
use App\Models\TujuanTugasModel;
use App\Models\PegawaiModel;
use App\Models\UserModel;

class DashboardController extends BaseController
{
    protected $penugasanModel;
    protected $tujuanModel;
    protected $tanggalTugasModel;
    protected $pegawaiModel;
    protected $userModel;

    public function __construct()
    {
        helper(["format_tanggal", "url"]);
        helper(["format_lokasi"]);

        $this->penugasanModel = new PenugasanModel();
        $this->tujuanModel = new TujuanTugasModel();
        $this->tanggalTugasModel = new TanggalTugasModel();
        $this->pegawaiModel = new PegawaiModel();
        $this->userModel = new UserModel();
    }
    public function dashboard_st()
    {
        $session = session();

        $nama = $session->get('nama');
        $id_pengguna = $session->get('id_pengguna');
        $currentRole = $session->get('current_role');

        $user_detail = $this->userModel->getUserPegawai($id_pengguna);
        log_message('debug', 'ID Pengguna dari session: ' . $id_pengguna);
        log_message('debug', 'Isi user_detail: ' . print_r($user_detail, true));


        $dataPenugasan = $this->penugasanModel->getPenugasanDashboardInfo($id_pengguna, $currentRole);

        $data = [];
        foreach ($dataPenugasan as $penugasan) {
            $id_penugasan = $penugasan['id_penugasan'];

            $tujuanArray = $this->tujuanModel->getTujuanTugasForm($id_penugasan);
            $penugasan['tujuan'] = formatLokasiTugas($tujuanArray);

            // 🔹 Ambil tanggal-tanggal dari tujuan
            $tanggalArray = $this->tanggalTugasModel->getTanggalByPenugasanArray($id_penugasan);
            $penugasan['tanggal_penugasan'] = formatTanggalTugas($tanggalArray);

            $data[] = $penugasan;
        }

        $setujuCounts = $this->penugasanModel->getCountByJenisDanStatus(null, 'disetujui', $id_pengguna, $currentRole);
        $diajukanCounts = $this->penugasanModel->getCountByJenisDanStatus(null, 'sedang diajukan', $id_pengguna, $currentRole);
        $jenisCounts = $this->penugasanModel->getCountByJenisDanStatus('penugasan luar kantor', 'disetujui', $id_pengguna, $currentRole);

        $data = [
            'nama' => $nama,
            'currentRole' => $currentRole,
            'penugasan' => $data,
            'setujuCounts' => $setujuCounts['total'] ?? 0,
            'diajukanCounts' => $diajukanCounts['total'] ?? 0,
            'jenisCounts' => $jenisCounts['total'] ?? 0,
            'user_detail' => $user_detail

        ];

        $session->set('selected_module', 'penugasan');

        return view('dashboard/dashboard_penugasan', $data);
    }

    public function dashboard_espj()
    {
        $session = session();
        $session->set('selected_module', 'espj');

        return view('dashboard/dashboard_espj');
    }
}
