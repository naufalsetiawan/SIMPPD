<?php

namespace App\Controllers;

use App\Database\Migrations\Komentar;
use App\Database\Migrations\TanggalTugas;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use App\Models\KomentarModel;
use App\Models\PenugasanModel;
use App\Models\SuratTugasModel;
use App\Models\PesertaModel;
use App\Models\TujuanTugasModel;
use App\Models\KotaModel;
use App\Models\TanggalTugasModel;
use App\Models\LandasanStModel;
use App\Models\PegawaiModel;
use App\Models\PersetujuanStModel;
use App\Models\PetugasModel;
use App\Models\TimKerjaModel;
use Dompdf\Dompdf;
use Dompdf\Options;


class PenugasanController extends BaseController
{
    protected $penugasanModel;
    protected $stModel;
    protected $pesertaModel;
    protected $tujuanModel;
    protected $kotaModel;
    protected $komentarModel;
    protected $tanggalTugasModel;
    protected $landasanStModel;
    protected $pegawaiModel;
    protected $pelaksanaModel;
    protected $persetujuanStModel;
    protected $petugasModel;

    protected $timKerjaModel;

    public function __construct()
    {
        $this->penugasanModel = new PenugasanModel();
        $this->stModel = new SuratTugasModel();
        $this->pesertaModel = new PesertaModel();
        $this->tujuanModel = new TujuanTugasModel();
        $this->kotaModel = new KotaModel();
        $this->komentarModel = new KomentarModel();
        $this->tanggalTugasModel = new TanggalTugasModel();
        $this->landasanStModel = new LandasanStModel();
        $this->pegawaiModel = new PegawaiModel();
        $this->petugasModel = new PetugasModel();
        $this->persetujuanStModel = new PersetujuanStModel();
        $this->timKerjaModel = new TimKerjaModel();

        helper(["format_tanggal", "url"]);
        helper(['format_lokasi']);
    }

    public function daftar_penugasan()
    {
        $session = session();
        $id_pengguna = session()->get('id_pengguna');
        $currentRole = session()->get('current_role');
        $roles = $session->get('roles');

        $perPage = 10;
        $page = $this->request->getVar('page') ?: 1;
        $offset = ($page - 1) * $perPage;

        // Ambil filter dari request
        $filterTimKerja = $this->request->getVar('tim_kerja');
        $filterSubJenis = $this->request->getVar('sub_jenis');
        $filterStatus = $this->request->getVar('status_st');
        $filterUrutan = $this->request->getVar('urutkan');

        // Ambil data penugasan dengan filter
        $dataPenugasan = $this->penugasanModel->getPenugasanByTimKerja(
            $id_pengguna,
            $currentRole == 4 || $currentRole == 0,
            $perPage,
            $offset,
            $filterTimKerja,
            $filterSubJenis,
            $filterStatus,
            $filterUrutan
        );

        $totalRows = $this->penugasanModel->getPenugasanCount(
            $id_pengguna,
            $roles,
            $filterTimKerja,
            $filterSubJenis,
            $filterStatus
        );

        $pager = \Config\Services::pager();
        $pagerLinks = $pager->makeLinks($page, $perPage, $totalRows, 'tailwind_pagination');

        $data = [];
        foreach ($dataPenugasan as $penugasan) {
            $id_penugasan = $penugasan['id_penugasan'];
            $tujuan = $this->tujuanModel->getTujuanTugasForm($id_penugasan);
            $penugasan['tujuan'] = formatLokasiTugas($tujuan);

            $tanggalArray = $this->tanggalTugasModel->getTanggalByPenugasanArray($id_penugasan);
            $penugasan['tanggal_penugasan'] = formatTanggalTugas($tanggalArray);


            $data[] = $penugasan;
        }

        $list_tim_kerja = $this->timKerjaModel->getAllTimKerja();
        $list_status = ['belum diajukan', 'sedang diajukan', 'disetujui', 'ditolak'];

        return view('penugasan/daftar_penugasan', [
            'list_tim_kerja'     => $list_tim_kerja,
            'penugasan'          => $data,
            'pagerLinks'         => $pagerLinks,
            'selectedTimKerja'   => $filterTimKerja,
            'selectedSubJenis'   => $filterSubJenis,
            'selectedStatus'     => $filterStatus,
            'selectedUrutan'     => $filterUrutan,
            'filter' => [
                'tim_kerja' => $filterTimKerja,
                'sub_jenis' => $filterSubJenis,
                'status' => $filterStatus,
                'urutkan' => $filterUrutan,
            ]
        ]);
    }

    public function pengajuan_surat_tugas()
    {
        $session = session();
        $id_pengguna = $session->get('id_pengguna');
        $currentRole = $session->get('current_role');

        // Definisikan role ID yang diizinkan
        $allowedRoles = [4, 8];

        // Cek apakah role pengguna saat ini TIDAK ADA di dalam daftar role yang diizinkan
        if (! in_array($currentRole, $allowedRoles)) {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak.');
        }

        $perPage = 10;
        $page = $this->request->getVar('page') ?: 1;
        $offset = ($page - 1) * $perPage;

        // Ambil filter dari request
        $filterTimKerja = $this->request->getVar('tim_kerja');
        $filterSubJenis = $this->request->getVar('sub_jenis');
        $filterStatus = $this->request->getVar('status_st');
        $filterUrutan = $this->request->getVar('urutkan');

        if (empty($filterStatus)) {
            if ($currentRole == 4) {
                $filterStatus = ['menunggu persetujuan', 'disetujui', 'ditolak'];
            } elseif ($currentRole == 8) {
                $filterStatus = ['sedang diajukan', 'menunggu persetujuan', 'disetujui', 'ditolak'];
            }
        }

        $dataPenugasan = $this->penugasanModel->getPenugasanByTimKerja(
            $id_pengguna,
            false, // $all = false karena tidak admin
            $perPage,
            $offset,
            $filterTimKerja,
            $filterSubJenis,
            $filterStatus,
            $filterUrutan
        );

        $totalRows = $this->penugasanModel->getPenugasanCount(
            $id_pengguna,
            [$currentRole],
            $filterTimKerja,
            $filterSubJenis,
            $filterStatus
        );

        $pager = \Config\Services::pager();
        $pagerLinks = $pager->makeLinks($page, $perPage, $totalRows, 'tailwind_pagination');

        $data = [];
        foreach ($dataPenugasan as $penugasan) {
            $id_penugasan = $penugasan['id_penugasan'];
            // $penugasan['tujuan'] = $this->tujuanModel->getTujuanTugas($id_penugasan, 'string');
            $tujuan = $this->tujuanModel->getTujuanTugasForm($id_penugasan);
            $penugasan['tujuan'] = formatLokasiTugas($tujuan);

            $tanggalArray = $this->tanggalTugasModel->getTanggalByPenugasanArray($id_penugasan);
            $penugasan['tanggal_penugasan'] = formatTanggalTugas($tanggalArray);

            $data[] = $penugasan;
        }

        $list_status = ['sedang diajukan', 'disetujui', 'ditolak'];
        $list_sub_jenis = ['koordinasi', 'tugas pokok dan fungsi', 'undangan'];
        $list_tim_kerja = $this->timKerjaModel->getAllTimKerja();

        return view('penugasan/pengajuan_surat_tugas', [
            'list_tim_kerja'     => $list_tim_kerja,
            'penugasan'        => $data,
            'pagerLinks'       => $pagerLinks,
            'selectedSubJenis' => $filterSubJenis,
            'selectedTimKerja'   => $filterTimKerja,
            'selectedStatus'   => is_array($filterStatus) ? '' : $filterStatus,
            'selectedUrutan'   => $filterUrutan,
            'list_status'      => $list_status,
            'list_sub_jenis'   => $list_sub_jenis,
            'filter' => [
                'sub_jenis' => $filterSubJenis,
                'status'    => $filterStatus,
                'urutkan'   => $filterUrutan,
            ]
        ]);
    }

    public function view_draft_st($id)
    {
        $session = session();
        $st =  $this->stModel->getSTByPenugasan($id, true);
        // $peserta =  $this->pesertaModel->getDetailPesertaByPenugasan($id);
        $penugasan = $this->penugasanModel->find($id);
        $komentar = $this->komentarModel->getAllKomentarbyPenugasan($id);
        $menimbang = $this->landasanStModel->getLandasanStById($id, 'menimbang');
        $dasar     = $this->landasanStModel->getLandasanStById($id, 'dasar');
        $jumlah_peserta = $this->pesertaModel->getJumlahPeserta($id);
        $peserta = $this->pesertaModel->getPesertaByPenugasan($id);
        $riwayat_persetujuan = $this->persetujuanStModel->getPersetujuanStById($id);

        $untuk = $penugasan['untuk'];

        // $pegawai = $this->pegawaiModel->findAll();

        $id_st = $st['id'];
        $petugas = $this->petugasModel->getPetugas();
        $existingPetugas = $this->stModel->select('pelaksana, nama_pelaksana, jabatan_pelaksana')
            ->where('id', $id_st)
            ->first();


        // Inisialisasi array untuk menyimpan data
        $kepala = [];
        $plt = [];
        $plh = [];

        // Pisahkan petugas berdasarkan kolom 'jenis' (tanpa peduli huruf besar/kecil)
        foreach ($petugas as $p) {
            // Paksa jadi array jika datangnya object
            $p = (array) $p;

            $jenis = strtolower($p['jenis'] ?? '');

            if ($jenis === 'plt') {
                $plt[] = $p;
            } elseif ($jenis === 'plh') {
                $plh[] = $p;
            } elseif ($jenis === 'kepala') {
                $kepala[] = $p;
            }
        }

        // Menambahkan pelaksana dari stModel ke dalam plt/plh/kepala
        if ($existingPetugas) {
            $pelaksana = strtolower($existingPetugas['pelaksana']);
            $nama = $existingPetugas['nama_pelaksana'];
            $jabatan = $existingPetugas['jabatan_pelaksana'];

            if ($pelaksana === 'plt' && !in_array($nama, array_column($plt, 'nama'))) {
                $plt[] = [
                    'nip' => null,
                    'nama' => $nama,
                    'jabatan' => $jabatan,
                ];
            } elseif ($pelaksana === 'plh' && !in_array($nama, array_column($plh, 'nama'))) {
                $plh[] = [
                    'nip' => null,
                    'nama' => $nama,
                    'jabatan' => $jabatan,
                ];
            } elseif ($pelaksana === 'kepala' && !in_array($nama, array_column($kepala, 'nama'))) {
                $kepala[] = [
                    'nip' => null,
                    'nama' => $nama,
                    'jabatan' => $jabatan,
                ];
            }
        }

        // Konversi ke array asosiatif pasti (jika berasal dari object)
        $pltArray = array_map(fn($obj) => (array) $obj, $plt);
        $plhArray = array_map(fn($obj) => (array) $obj, $plh);
        $kepalaArray = array_map(fn($obj) => (array) $obj, $kepala);
        // Inisialisasi array untuk menyimpan data
        $kepala = [];
        $plt = [];
        $plh = [];

        // Pisahkan petugas berdasarkan kolom 'jenis' (tanpa peduli huruf besar/kecil)
        foreach ($petugas as $p) {
            // Paksa jadi array jika datangnya object
            $p = (array) $p;

            $jenis = strtolower($p['jenis'] ?? '');

            if ($jenis === 'plt') {
                $plt[] = $p;
            } elseif ($jenis === 'plh') {
                $plh[] = $p;
            } elseif ($jenis === 'kepala') {
                $kepala[] = $p;
            }
        }

        // Menambahkan pelaksana dari stModel ke dalam plt/plh/kepala
        if ($existingPetugas) {
            $pelaksana = strtolower($existingPetugas['pelaksana']);
            $nama = $existingPetugas['nama_pelaksana'];
            $jabatan = $existingPetugas['jabatan_pelaksana'];

            if ($pelaksana === 'plt' && !in_array($nama, array_column($plt, 'nama'))) {
                $plt[] = [
                    'nip' => null,
                    'nama' => $nama,
                    'jabatan' => $jabatan,
                ];
            } elseif ($pelaksana === 'plh' && !in_array($nama, array_column($plh, 'nama'))) {
                $plh[] = [
                    'nip' => null,
                    'nama' => $nama,
                    'jabatan' => $jabatan,
                ];
            } elseif ($pelaksana === 'kepala' && !in_array($nama, array_column($kepala, 'nama'))) {
                $kepala[] = [
                    'nip' => null,
                    'nama' => $nama,
                    'jabatan' => $jabatan,
                ];
            }
        }

        // Konversi ke array asosiatif pasti (jika berasal dari object)
        $pltArray = array_map(fn($obj) => (array) $obj, $plt);
        $plhArray = array_map(fn($obj) => (array) $obj, $plh);
        $kepalaArray = array_map(fn($obj) => (array) $obj, $kepala);

        $tanggalArray = $this->tanggalTugasModel->getTanggalByPenugasanArray($id);
        $formattedTanggal = formatTanggalTugas($tanggalArray);

        $tujuan = $this->tujuanModel->getTujuanTugasForm($id);
        $dataTujuanTugas = formatLokasiTugas($tujuan);


        $data = [
            'untuk' => $untuk,
            'penugasan' => $penugasan,
            'komentar' => $komentar,
            'id' => $id,
            'st' => $st,
            // 'peserta' => $peserta,
            'menimbang' => $menimbang,
            'dasar' => $dasar,
            'tujuan' => $dataTujuanTugas,
            'tanggal_penugasan' => $formattedTanggal,
            'jumlah_peserta' => $jumlah_peserta,
            'peserta' => $peserta,
            // 'pegawai' => $pegawai,
            'plt' => $pltArray,
            'plh' => $plhArray,
            'kepala' => $kepalaArray,
            'riwayat_persetujuan' => $riwayat_persetujuan
        ];

        return view('penugasan/view_draft_surat', $data);
    }

    public function update_st()
    {
        $session = session();
        $id = $this->request->getPost('id_penugasan');

        // Check if `no_surat` is unique (excluding the current record)
        $no_surat = $this->request->getPost('no_surat');
        $isUnique = $this->stModel
            ->where('no_surat', $no_surat)
            ->where('id_penugasan !=', $id) // Exclude the current record
            ->countAllResults() === 0;

        if (!$isUnique) {
            return redirect()->back()->withInput()->with('errors', 'No Surat sudah tersedia!');
            session()->setFlashdata('old_input', $this->request->getPost());
            return redirect()->back();
        }

        // If unique, proceed with updating
        $data = [
            'no_surat'      => $no_surat,
            'tanggal_surat' => $this->request->getPost('tanggal_surat'),
            'tempat_surat' => $this->request->getPost('tempat_surat'),
            'pelaksana'     => $this->request->getPost('pelaksana'),
            'nama_pelaksana' => $this->request->getPost('nama_pelaksana'),
            'jabatan_pelaksana' => $this->request->getPost('jabatan_pelaksana'),
            'status_st' => 'sedang diajukan'
        ];

        // Perbaikan Query Update
        $this->stModel
            ->where('id_penugasan', $id) // Tentukan ID yang akan di-update
            ->set($data) // Set data yang diperbarui
            ->update(); // Eksekusi update


        $penugasanData = [
            'updated_by' => $session->get('id_pengguna'),
            'updated_by_name' => $session->get('nama'),
            'updated_at' => date('Y-m-d H:i:s')
        ];
        $this->penugasanModel->update($id, $penugasanData);
        return redirect()->to("/penugasan/draft_st/$id")->with('success', 'Data berhasil diperbarui!');
    }

    public function update_status_st($id)
    {
        $session = session();
        $status = $this->request->getPost('status');
        $this->stModel
            ->set('status_st', $status)
            ->where('id_penugasan', $id)
            ->update();

        return redirect()->to("/penugasan/draft_st/$id");
    }

    public function deleteTugas()
    {
        $id_penugasan = $this->request->getPost('id_penugasan');
        if ($this->penugasanModel->delete($id_penugasan)) {
            return redirect()->to('/penugasan/tugas');
        }
    }

    public function tolakST()
    {
        $id = $this->request->getPost('id_penugasan');
        $status = $this->request->getPost('status');
        $session = session();
        $data = [
            'id_penugasan' => $id,
            'isi' => $this->request->getPost('isi'),
            'pembuat' => $session->get('id_pengguna'),
            'pembuat_nama' => $session->get('nama'),
            'waktu_ditambahkan' => date('Y-m-d H:i:s'),
        ];

        $this->komentarModel->insert($data);
        $this->stModel
            ->set('status_st', $status)
            ->where('id_penugasan', $id)
            ->update();
        return redirect()->to("/penugasan/draft_st/$id");
    }

    public function setujuiST()
    {
        $session = session();

        $id = $this->request->getPost('id_penugasan');
        $status = $this->request->getPost('status');
        $file = $this->request->getFile('file_st');

        $data = [
            'id_penugasan' => $id,
            'disetujui_pada' => date('Y-m-d H:i:s'),
        ];

        // Handle file upload
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newFileName = $file->getRandomName();
            $file->move('uploads/', $newFileName);
            $data['file'] = 'uploads/' . $newFileName;
        }

        $this->persetujuanStModel->insert($data);
        $this->stModel
            ->set('status_st', $status)
            ->where('id_penugasan', $id)
            ->update();
        return redirect()->to("/penugasan/draft_st/$id");
    }
    public function addKomentar()
    {
        $id = $this->request->getPost('id_penugasan');
        $session = session();
        $data = [
            'id_penugasan' => $id,
            'isi' => $this->request->getPost('isi'),
            'pembuat' => $session->get('id_pengguna'),
            'pembuat_nama' => $session->get('nama'),
            'waktu_ditambahkan' => date('Y-m-d H:i:s'),
        ];

        $this->komentarModel->insert($data);
        return redirect()->to("/penugasan/draft_st/$id");
    }

    //GENERATE SURAT
    public function generate($id)
    {
        $menimbang = $this->landasanStModel->getLandasanStById($id, 'menimbang');
        $dasar     = $this->landasanStModel->getLandasanStById($id, 'dasar');

        $penugasan = $this->penugasanModel
            ->select('*')
            ->where('id_penugasan', $id)
            ->first();

        $untuk = $penugasan['untuk'];
        $tanggal_string =  $penugasan['tanggal_penugasan_string'];
        $transportasi = $penugasan['transportasi'];
        $anggaran = $penugasan['anggaran'];
        $usulan_mak_1 = $penugasan['usulan_mak_1'];
        $usulan_mak_2 = $penugasan['usulan_mak_2'];
        $st = $this->stModel->getSTByPenugasan($id, true);

        // Jika pelaksana adalah 'kepala' → kosongkan
        if (strtolower($st['pelaksana']) === 'kepala') {
            $st['pelaksana'] = '';
        } else {
            // Buat kapital di awal kata
            $st['pelaksana'] = ucwords(strtolower($st['pelaksana']));
        }

        $jumlah_peserta = $this->pesertaModel->getJumlahPeserta($id);
        $peserta = $this->pesertaModel->getPesertaByPenugasan($id);

        $tanggalArray = $this->tanggalTugasModel->getTanggalByPenugasanArray($id);
        $formattedTanggal = formatTanggalTugas($tanggalArray);

        $tujuan = $this->tujuanModel->getTujuanTugasForm($id);
        $dataTujuanTugas = formatLokasiTugas($tujuan);

        $data = [
            'menimbang' => $menimbang,
            'dasar' => $dasar,
            'untuk' => $untuk,
            'tujuan' => $dataTujuanTugas,
            'tanggal_penugasan' => $formattedTanggal,
            'tanggal_string' => $tanggal_string,
            'transportasi' => $transportasi,
            'anggaran' => $anggaran,
            'usulan_mak_1' => $usulan_mak_1,
            'usulan_mak_2' => $usulan_mak_2,
            'peserta' => $peserta,
            'jumlah_peserta' => $jumlah_peserta,

            'st' => $st

        ];
        $tanggal_surat = formatTanggalIndonesia((array)$st['tanggal_surat']);
        $data['st']['tanggal_surat'] = $tanggal_surat;

        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $options->set('chroot', FCPATH);
        $dompdf = new Dompdf($options);

        // Pass data to the view
        $html = view(
            'template_dokumen/ST',
            $data

        );
        $dompdf->loadHtml($html);

        // Set paper size and orientation
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to browser
        $dompdf->stream("table.pdf", ["Attachment" => false]);
    }

    public function cetak_visum($id)
    { // Allow Dompdf to access files in your public directory

        $penugasan = $this->penugasanModel
            ->select('ppk_nama, ppk_nip')
            ->where('id_penugasan', $id)
            ->first();

        $data['penugasan'] = $penugasan;

        $dompdf = new Dompdf();
        // Pass data to the view
        $html = view(
            'template_dokumen/VISUM',
            $data
        );
        $dompdf->loadHtml($html);
        // Set paper size and orientation
        $dompdf->setPaper([0, 0, 595, 935], 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to browser
        $dompdf->stream("table.pdf", ["Attachment" => false]);
    }

    public function rekap()
    {
        $session = session();
        $id_pengguna = session()->get('id_pengguna');
        $currentRole = session()->get('current_role');
        $roles = $session->get('roles');

        // Ambil input filter dari GET
        $filterType = $this->request->getGet('filter_type') ?? 'bulan';
        $bulan = $this->request->getGet('bulan') ?? date('m'); // default bulan ini
        $tahun = $this->request->getGet('tahun') ?? date('Y'); // default tahun ini

        $rekapList = $this->penugasanModel->getRekapFiltered($filterType, $bulan, $tahun);

        foreach ($rekapList as &$rekap) {
            $tujuanArray = $this->tujuanModel->getTujuanTugasForm($rekap['id_penugasan']);
            $rekap['tempat_pelaksanaan'] = formatLokasiTugas($tujuanArray);

            // 🔹 Ambil tanggal-tanggal dari tujuan
            $tanggalArray = $this->tanggalTugasModel->getTanggalByPenugasanArray($rekap['id_penugasan']);
            $rekap['tanggal_pelaksanaan'] = formatTanggalTugas($tanggalArray);

            // Ambil peserta berdasarkan penugasan
            $pesertaList = $this->pesertaModel->getPesertaByPenugasan($rekap['id_penugasan']);

            // Ambil hanya nama (atau sesuaikan kolomnya)
            $pesertaNames = array_column($pesertaList, 'nama');

            // Gabungkan jadi satu string, pisahkan dengan "; "
            $rekap['peserta'] = implode('; ', $pesertaNames);
            // Persetujuan ST
            $persetujuan = $this->persetujuanStModel->getPersetujuanStById($rekap['id_penugasan']);
            $countPersetujuan = count($persetujuan);

            if ($countPersetujuan > 0) {
                $lastIndex = $countPersetujuan - 1;
                $rekap['file'] = $persetujuan[$lastIndex]['file']; // Ambil file terakhir
                $rekap['keterangan'] = $countPersetujuan > 1
                    ? 'Revisi terakhir (' . ($countPersetujuan - 1) . ' kali revisi)'
                    : '-';
            } else {
                $rekap['file'] = null;
                $rekap['keterangan'] = '-';
            }
        }

        $data['rekap'] = $rekapList;
        $data['filterType'] = $filterType;
        $data['selectedBulan'] = $bulan;
        $data['selectedTahun'] = $tahun;

        return view('penugasan/rekap', $data);
    }

    public function rekap_export()
    {
        // Ambil input filter dari GET
        $filterType = $this->request->getGet('filter_type') ?? 'bulan';
        $bulan = $this->request->getGet('bulan') ?? date('m'); // default bulan ini
        $tahun = $this->request->getGet('tahun') ?? date('Y');   // default tahun ini

        // Ambil data sesuai filter
        $rekapList = $this->penugasanModel->getRekapFiltered($filterType, $bulan, $tahun);

        foreach ($rekapList as &$rekap) {
            $tujuanArray = $this->tujuanModel->getTujuanTugasForm($rekap['id_penugasan']);
            $rekap['tempat_pelaksanaan'] = formatLokasiTugas($tujuanArray);

            $tanggalArray = $this->tanggalTugasModel->getTanggalByPenugasanArray($rekap['id_penugasan']);
            $rekap['tanggal_pelaksanaan'] = formatTanggalTugas($tanggalArray);

            $pesertaList = $this->pesertaModel->getPesertaByPenugasan($rekap['id_penugasan']);
            $pesertaNames = array_column($pesertaList, 'nama');
            $rekap['peserta'] = implode('; ', $pesertaNames);

            $persetujuan = $this->persetujuanStModel->getPersetujuanStById($rekap['id_penugasan']);
            $countPersetujuan = count($persetujuan);
            $rekap['file'] = $countPersetujuan > 0 ? $persetujuan[$countPersetujuan - 1]['file'] : null;

            if ($countPersetujuan > 1) {
                $rekap['keterangan'] = "Revisi " . ($countPersetujuan - 1);
            } else {
                $rekap['keterangan'] = '-';
            }
        }

        // Buat Excel
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header
        $sheet->setCellValue('A1', 'No Surat Tugas');
        $sheet->setCellValue('B1', 'Nama Kegiatan');
        $sheet->setCellValue('C1', 'Tanggal Pelaksanaan');
        $sheet->setCellValue('D1', 'Tempat Pelaksanaan');
        $sheet->setCellValue('E1', 'Peserta');
        $sheet->setCellValue('F1', 'File ST');

        $rowNum = 2;
        foreach ($rekapList as $r) {
            $noSurat = $r['no_surat'];
            if (!empty($r['keterangan']) && $r['keterangan'] !== '-') {
                $noSurat .= " ({$r['keterangan']})";
            }

            $sheet->setCellValueExplicit('A' . $rowNum, $noSurat, DataType::TYPE_STRING);
            $sheet->setCellValue('B' . $rowNum, $r['judul_kegiatan']);
            $sheet->setCellValue('C' . $rowNum, $r['tanggal_pelaksanaan']);
            $sheet->setCellValue('D' . $rowNum, $r['tempat_pelaksanaan']);
            $sheet->setCellValue('E' . $rowNum, $r['peserta']);
            $sheet->setCellValue('F' . $rowNum, $r['file'] ?? '-');
            $rowNum++;
        }

        $writer = new Xlsx($spreadsheet);
        $fileName = 'rekap_st_' . date('Ymd_His') . '.xlsx';

        // Output ke browser
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"$fileName\"");
        $writer->save('php://output');
        exit;
    }
}
