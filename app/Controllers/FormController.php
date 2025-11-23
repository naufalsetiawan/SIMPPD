<?php

namespace App\Controllers;

use App\Models\PenugasanModel;
use App\Models\SuratTugasModel;
use App\Models\PegawaiModel;
use App\Models\PesertaModel;
use App\Models\TujuanTugasModel;
use App\Models\TanggalTugasModel;
use App\Models\KotaModel;
use App\Models\LandasanModel;
use App\Models\LandasanStModel;
use App\Models\TimKerjaModel;
use App\Models\PetugasModel;



class FormController extends BaseController
{
    protected $penugasanModel;
    protected $stModel;
    protected $pegawaiModel;
    protected $pesertaModel;
    protected $tujuanModel;
    protected $tanggalModel;
    protected $kotaModel;
    protected $landasanModel;
    protected $landasanStModel;
    protected $tanggalTugasModel;
    protected $petugasModel;
    protected $timKerjaModel;
    protected $db;



    public function __construct()
    {
        $this->penugasanModel = new PenugasanModel();
        $this->stModel = new SuratTugasModel();
        $this->pegawaiModel = new PegawaiModel();
        $this->pesertaModel = new PesertaModel();
        $this->tujuanModel = new TujuanTugasModel();
        $this->kotaModel = new KotaModel();
        $this->tanggalModel = new TanggalTugasModel();
        $this->landasanModel = new LandasanModel();
        $this->landasanStModel = new LandasanStModel();
        $this->tanggalTugasModel = new TanggalTugasModel();
        $this->timKerjaModel = new TimKerjaModel();
        $this->db = \Config\Database::connect();
        $this->petugasModel = new PetugasModel();

        helper(["format_tanggal"]);
        helper(['format_lokasi']);
    }

    public function form_tugas($id = null)
    {
        $session = session();
        $data['kota'] = $this->kotaModel->getAllKota();
        // Panggil method baru khusus PPK
        $ppkList = $this->petugasModel->getPPK();

        // Hanya ambil nip dan nama
        $ppkList = array_map(function ($ppk) {
            return [
                'nip' => $ppk['nip'],
                'nama' => $ppk['nama'],
            ];
        }, $ppkList);

        $timKerjaList = $this->timKerjaModel->getAllTimKerja();

        // Ambil data penugasan jika sudah ada
        if ($id) {
            $data['penugasan'] = $this->penugasanModel->find($id);
            $data['tujuan'] = $this->tujuanModel->getTujuanTugasForm($id);
            $data['tanggal_penugasan'] = $this->tanggalTugasModel->getTanggalByPenugasanArray($id);

            $nipPpk = $data['penugasan']['ppk_nip'];
            $namaPpk = $data['penugasan']['ppk_nama'];

            $timKerja = $data['penugasan']['tim_kerja'];

            $existingNIPs = array_column($ppkList, 'nip');
            if (!in_array($nipPpk, $existingNIPs)) {
                $ppkList[] = ['nip' => $nipPpk, 'nama' => $namaPpk];
            }

            if (!in_array($timKerja, array_column($timKerjaList, 'nama_tim_kerja'))) {
                $timKerjaList[] = ['nama_tim_kerja' => $timKerja];
            }
        }
        $data['ppk'] = $ppkList;
        $data['tim_kerja'] = $timKerjaList;
        return view('penugasan/form_tugas', $data);
    }

    public function submit_form_tugas()
    {
        $session = session();
        $id = $this->request->getPost('id_penugasan');
        $action = $this->request->getPost('action');

        $data = [
            'tahun' => $this->request->getPost('tahun'),
            'tim_kerja' => $this->request->getPost('tim_kerja'),
            'jenis_penugasan' => $this->request->getPost('jenis_penugasan'),
            'sub_jenis_penugasan' => $this->request->getPost('sub_jenis_penugasan'),
            'judul_kegiatan' => $this->request->getPost('judul_kegiatan'),
            'tanggal_penugasan_string' => $this->request->getPost('tanggal_string'),
            'tujuan' => $this->request->getPost('tujuan'),
            'transportasi' => $this->request->getPost('transportasi'),
            'anggaran' => $this->request->getPost('anggaran'),
            'usulan_mak_1' => $this->request->getPost('usulan_mak_1'),
            'usulan_mak_2' => $this->request->getPost('usulan_mak_2'),
            'ppk_nama' => $this->request->getPost('ppk_nama'),
            'ppk_nip' => $this->request->getPost('ppk_nip')
        ];

        $file = $this->request->getFile('nota_dinas');

        $tanggal_mulai = $this->request->getPost('tanggal_mulai');
        $tanggal_selesai = $this->request->getPost('tanggal_selesai');

        $tujuan = $this->request->getPost('tujuan');


        $rules = [
            'tahun' => 'required',
            'tim_kerja' => 'required',
            'jenis_penugasan' => 'required',
            'sub_jenis_penugasan' => 'required',
            'judul_kegiatan' => 'required',
            'tujuan' => 'required',
            'transportasi' => 'required',
            'anggaran' => 'required',
        ];

        if ($data['anggaran'] !== 'Tidak dibiayai') {
            $rules['usulan_mak_1'] = 'required';
        }

        // Validasi input
        // $validation = $this->penugasanModel->validateTugas($data);
        if (!$this->validate($rules)) {
            session()->setFlashdata('errors', 'Harap Mengisi Semua Kolom');
            session()->setFlashdata('old_input', $data);
            return redirect()->back()->withInput();
        }

        // Handle file upload
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newFileName = $file->getRandomName(); // Generate random file name
            $file->move('uploads/', $newFileName); // Move file to 'uploads/' folder
            $data['nota_dinas'] = 'uploads/' . $newFileName; // Save path in database
        }

        // Mengupdate tugas yang sudah ada
        if ($action === "update") {
            $data['updated_by'] = $session->get('id_pengguna');
            $data['updated_by_name'] = $session->get('nama');
            $data['updated_at'] = date('Y-m-d H:i:s');

            // Melakukan update kolom
            $this->penugasanModel->update($id, $data);

            $this->tujuanModel
                ->where('id_penugasan', $id)
                ->delete();

            if (!empty($tujuan) && is_array($tujuan)) {

                $data = [];

                foreach ($tujuan as $tujuanRow) {

                    // Validasi: provinsi 
                    if (!empty(trim($tujuanRow['provinsi']))) {

                        // Sanitasi
                        $provinsi = ucwords(strtolower(trim($tujuanRow['provinsi'])));
                        $kota     = ucwords(strtolower(trim($tujuanRow['kota'])));
                        $lokasi   = trim($tujuanRow['lokasi'] ?? '');

                        // Masukkan data
                        $data[] = [
                            'id_penugasan' => $id,
                            'provinsi'     => $provinsi,
                            'nama_kota'    => $kota,
                            'lokasi'       => $lokasi
                        ];
                    }
                }

                // Insert batch ke DB
                if (!empty($data)) {
                    $this->tujuanModel->insertBatch($data);
                }
            }

            // Hapus data lama sebelum menambahkan yang baru
            $this->tanggalModel->where('id_penugasan', $id)->delete();
            if (!empty($tanggal_mulai) && !empty($tanggal_selesai)) {
                foreach ($tanggal_mulai as $index => $tglMulai) {


                    if (!empty($tglMulai) && !empty($tanggal_selesai[$index])) {
                        $this->tanggalModel->insert([
                            'id_penugasan' => $id,
                            'tanggal_mulai' => $tglMulai,
                            'tanggal_selesai' => $tanggal_selesai[$index]
                        ]);
                    }
                }
            }

            $untuk = $this->penugasanModel->find($id);

            // Reset status ST menjadi 'belum diajukan'
            $this->stModel
                ->where('id_penugasan', $id)
                ->set('status_st', 'belum diajukan')
                ->update();

            // Jika ST sudah ada, masuk ke halaman edit
            if ($untuk['untuk']) {
                return redirect()->to("/penugasan/edit_tugas/form_st/$id");
            } else {
                return redirect()->to("/penugasan/add_tugas/form_st/$id");
            }
        }

        // Menambah tugas baru
        else {
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['created_by'] = $session->get('id_pengguna');
            $data['updated_by'] = $session->get('id_pengguna');
            $data['created_by_name'] = $session->get('nama');
            $data['updated_by_name'] = $session->get('nama');
            $data['updated_at'] = date('Y-m-d H:i:s');
            $this->penugasanModel->insert($data);
            $id = $this->penugasanModel->insertID();
        }

        // Simpan tanggal baru
        if (!empty($tanggal_mulai) && !empty($tanggal_selesai)) {
            foreach ($tanggal_mulai as $index => $tglMulai) {


                if (!empty($tglMulai) && !empty($tanggal_selesai[$index])) {
                    $this->tanggalModel->insert([
                        'id_penugasan' => $id,
                        'tanggal_mulai' => $tglMulai,
                        'tanggal_selesai' => $tanggal_selesai[$index]
                    ]);
                }
            }
        }

        if (!empty($tujuan) && is_array($tujuan)) {

            $data = [];

            foreach ($tujuan as $tujuanRow) {

                if (!empty(trim($tujuanRow['provinsi']))) {

                    // Sanitasi
                    $provinsi = ucwords(strtolower(trim($tujuanRow['provinsi'])));
                    $kota     = ucwords(strtolower(trim($tujuanRow['kota'])));
                    $lokasi   = trim($tujuanRow['lokasi'] ?? '');

                    // Masukkan data
                    $data[] = [
                        'id_penugasan' => $id,
                        'provinsi'     => $provinsi,
                        'nama_kota'    => $kota,
                        'lokasi'       => $lokasi
                    ];
                }
            }

            // Insert batch ke DB
            if (!empty($data)) {
                $this->tujuanModel->insertBatch($data);
            }
        }

        $this->stModel->insert([
            'id_penugasan' => $id
        ]);

        $id = $this->penugasanModel->insertID();

        return redirect()->to("/penugasan/add_tugas/form_st/$id");
    }

    public function form_st($id)
    {
        $session = session();
        $data = $this->penugasanModel->find($id);

        $tujuan = $this->tujuanModel->getTujuanTugasForm($id);
        $data['tujuan'] = formatLokasiTugas($tujuan);

        // Menimbang
        $data['menimbang'] = $this->landasanModel
            ->getLandasanBySubJenis($data['sub_jenis_penugasan'], 'menimbang');

        $data['menimbang_st'] = $this->landasanStModel
            ->getLandasanStById($id, 'menimbang');

        // Dasar
        $data['dasar'] = $this->landasanModel
            ->getLandasanBySubJenis($data['sub_jenis_penugasan'], 'dasar');

        $data['dasar_st'] = $this->landasanStModel
            ->getLandasanStById($id, 'dasar');

        $tanggalArray = $this->tanggalTugasModel->getTanggalByPenugasanArray($id);
        $data['tanggal_penugasan'] = formatTanggalTugas($tanggalArray);

        return view('penugasan/form_st', ['penugasan' => $data]);
    }

    public function submit_form_st()
    {
        $session = session();
        $id = $this->request->getPost('id_penugasan');
        $action = $this->request->getPost('action');
        $data = [
            'untuk'  => $this->request->getPost('untuk')
        ];

        $menimbang = $this->request->getPost('menimbang');
        $dasar = $this->request->getPost('dasar');

        $validation = \Config\Services::validation();
        $rules = [
            'untuk' => 'required',
        ];

        if ($action === "update") {
            $penugasanData = [
                'updated_by' => $session->get('id_pengguna'),
                'updated_by_name' => $session->get('nama'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
            $this->penugasanModel->update($id, $penugasanData);

            // Hapus data lama sebelum menambahkan yang baru
            $this->penugasanModel->set($data)->where('id_penugasan', $id)->update();
            // Hapus semua landasan ST lama terlebih dahulu
            $this->landasanStModel->where('id_penugasan', $id)->delete();

            if (!empty($menimbang)) {
                $urutanM = 1;
                foreach ($menimbang as $butir) {
                    $this->landasanStModel->insert([
                        'id_penugasan' => $id,
                        'jenis'        => 'menimbang',   // ← tambahkan JENIS
                        'urutan'       => $urutanM++,
                        'butir'        => $butir
                    ]);
                }
            }

            if (!empty($dasar)) {
                $urutanD = 1;
                foreach ($dasar as $butir) {
                    $this->landasanStModel->insert([
                        'id_penugasan' => $id,
                        'jenis'        => 'dasar',       // ← tambahkan JENIS
                        'urutan'       => $urutanD++,
                        'butir'        => $butir
                    ]);
                }
            }
            // Reset status ST menjadi 'belum diajukan'
            $this->stModel
                ->where('id_penugasan', $id)
                ->set('status_st', 'belum diajukan')
                ->update();


            return redirect()->to("/penugasan/edit_tugas/form_peserta/$id");
        }

        $data['id_penugasan'] = $id;

        $this->penugasanModel->set($data)->where('id_penugasan', $id)->update();

        if (!empty($menimbang)) {
            $urutanM = 1;
            foreach ($menimbang as $butir) {
                $this->landasanStModel->insert([
                    'id_penugasan' => $id,
                    'jenis'        => 'menimbang',   // ← tambahkan JENIS
                    'urutan'       => $urutanM++,
                    'butir'        => $butir
                ]);
            }
        }
        if (!empty($dasar)) {
            $urutanD = 1;
            foreach ($dasar as $butir) {
                $this->landasanStModel->insert([
                    'id_penugasan' => $id,
                    'jenis'        => 'dasar',       // ← tambahkan JENIS
                    'urutan'       => $urutanD++,
                    'butir'        => $butir
                ]);
            }
        }

        return redirect()->to("/penugasan/add_tugas/form_peserta/$id");
    }

    public function form_peserta($id)
    {
        $penugasan = $this->penugasanModel->find($id);

        $pegawai = $this->pegawaiModel->getAllPegawai();

        $tugas = $this->tanggalTugasModel->getFirstAndLast($id);

        $list_tim_kerja = $this->timKerjaModel->getAllTimKerja();

        foreach ($pegawai as &$p) {
            $availablePeriods = $this->tanggalTugasModel->getAvailablePegawai($p['nip']);

            if (empty($availablePeriods)) {
                $p['status'] = 'Tersedia'; // Tidak ada tugas sama sekali
                continue;
            }

            $isAssigned = false;

            foreach ($availablePeriods as $period) {
                // Mengecek apakah peserta sedang dalam periode tugas
                if (
                    $tugas['first_date'] <= $period['last_date'] &&
                    $tugas['last_date'] >= $period['first_date']
                ) {
                    $isAssigned = true;
                    break;
                }
            }

            $p['status'] = $isAssigned ? 'Dalam Penugasan' : 'Tersedia';
        }

        // Prepare data for the view
        $data = [
            'penugasan' => $penugasan,
            'pegawai' => $pegawai,
            'list_tim_kerja' => $list_tim_kerja
        ];

        // for edit
        $peserta = $this->pesertaModel->getPesertaByPenugasan($id);

        if ($peserta) {
            $data['peserta'] = $peserta;
        }

        $tujuan = $this->tujuanModel->getTujuanTugasForm($id);
        $data['penugasan']['tujuan'] = formatLokasiTugas($tujuan);

        $tanggalArray = $this->tanggalTugasModel->getTanggalByPenugasanArray($id);
        $data['penugasan']['tanggal_penugasan'] = formatTanggalTugas($tanggalArray);
        $data['kota'] = $this->kotaModel->getAllKota();


        return view('penugasan/form_peserta_tugas', $data);
    }

    public function submit_form_peserta()
    {
        $session = session();

        $selectedPegawai = $this->request->getPost('selectedPegawai');
        $selectedPegawaiArray = json_decode($selectedPegawai, true); // Decode jika JSON, atau buat array kosong
        $id = $this->request->getPost('id_penugasan');
        $action = $this->request->getPost('action');

        $nama = $this->request->getPost('nama');
        $nip = $this->request->getPost('nip');
        $pangkat = $this->request->getPost('pangkat');
        $jabatan = $this->request->getPost('jabatan');
        $instansi = $this->request->getPost('instansi');
        $lokasi_berangkat = $this->request->getPost('lokasi_berangkat');
        $peran = $this->request->getPost('peran');

        // Determine the number of participants based on the 'nama' array
        $numParticipants = is_array($nama) ? count($nama) : 0;

        $logData = [];
        $errors = [];
        $pesertaData = [];

        // --- Start processing only if participants exist ---
        if ($numParticipants > 0) {
            // Ensure lokasi_berangkat values are not null but empty strings if no input
            foreach ($lokasi_berangkat as $key => $value) {
                if (empty($value)) {
                    $lokasi_berangkat[$key] = ''; // Set empty string if no input
                }
            }

            // Prepare data for logging and initial validation structure
            $logData = [
                'nama' => $nama,
                'nip' => $nip,
                'pangkat' => $pangkat,
                'jabatan' => $jabatan,
                'instansi' => $instansi,
                'lokasi_berangkat' => $lokasi_berangkat,
                'peran' => $peran
            ];

            $ketuaTimCount = 0;
            $nipUsed = [];

            foreach ($nama as $key => $value) {
                // Mengatur nilai default jika ada baris kosong
                $pesertaData[$key] = [
                    'nama' => isset($nama[$key]) ? $nama[$key] : null,
                    'nip' => isset($nip[$key]) ? $nip[$key] : null,
                    'pangkat' => isset($pangkat[$key]) ? $pangkat[$key] : null,
                    'jabatan' => isset($jabatan[$key]) ? $jabatan[$key] : null,
                    'instansi' => isset($instansi[$key]) ? $instansi[$key] : null,
                    'lokasi_berangkat' => isset($lokasi_berangkat[$key]) ? $lokasi_berangkat[$key] : '',
                    'peran' => isset($peran[$key]) ? $peran[$key] : '',
                ];

                // Validasi nip duplikat, kecuali jika nip = "-"
                $nipValue = trim($pesertaData[$key]['nip']);
                if ($nipValue !== '-' && $nipValue !== '') {
                    if (in_array($nipValue, $nipUsed)) {
                        $errors[] = "Terdapat pegawai dengan NIP " . $nipValue . " yang diinput lebih dari sekali (peserta " . ($key + 1) . ").";
                    } else {
                        $nipUsed[] = $nipValue;
                    }
                }

                // Hitung jumlah Ketua Tim
                if (strtolower(trim($pesertaData[$key]['peran'])) === 'ketua tim') {
                    $ketuaTimCount++;
                } else {
                    // Jika bukan Ketua Tim, peran harus 'Anggota'
                    if (!empty($pesertaData[$key]['peran']) && strtolower(trim($pesertaData[$key]['peran'])) !== 'anggota') {
                        $errors[] = 'Peran peserta ' . ($key + 1) . ' harus "Anggota" jika bukan "Ketua Tim".';
                    }
                }
                // Validasi setiap field wajib tidak boleh kosong
                if (empty($pesertaData[$key]['nama'])) {
                    $errors[] = 'Nama harus diisi untuk peserta ' . ($key + 1) . '.';
                }
                if (empty($pesertaData[$key]['nip'])) {
                    $errors[] = 'NIP harus diisi untuk peserta ' . ($key + 1) . '.';
                }
                if (empty($pesertaData[$key]['pangkat'])) {
                    $errors[] = 'Pangkat harus diisi untuk peserta ' . ($key + 1) . '.';
                }
                if (empty($pesertaData[$key]['jabatan'])) {
                    $errors[] = 'Jabatan harus diisi untuk peserta ' . ($key + 1) . '.';
                }
                if (empty($pesertaData[$key]['instansi'])) {
                    $errors[] = 'Instansi harus diisi untuk peserta ' . ($key + 1) . '.';
                }

                // Validasi lokasi berangkat
                if (empty($pesertaData[$key]['lokasi_berangkat'])) {
                    $errors[] = 'Lokasi berangkat harus diisi untuk peserta ' . ($key + 1) . '.';
                }

                // Validasi Peran jika jumlah peserta lebih dari 3
                if (count($nama) > 3 && empty($pesertaData[$key]['peran'])) {
                    $errors[] = 'Peran wajib diisi untuk peserta ' . ($key + 1) . '.';
                }
            }

            // Validasi jumlah Ketua Tim
            if (count($nama) > 3) {
                if ($ketuaTimCount === 0) {
                    $errors[] = 'Harus ada satu peserta dengan peran "Ketua Tim".';
                } elseif ($ketuaTimCount > 1) {
                    $errors[] = 'Hanya boleh ada satu peserta dengan peran "Ketua Tim".';
                }
            }
        }
        // Jika ada error validasi, kembalikan dengan pesan error
        if (!empty($errors)) {
            session()->setFlashdata('errors', $errors);
            session()->setFlashdata('old_input', [
                'nama' => $nama,
                'nip' => $nip,
                'pangkat' => $pangkat,
                'jabatan' => $jabatan,
                'instansi' => $instansi,
                'lokasi_berangkat' => $lokasi_berangkat,
                'peran' => $peran
            ]);
            return redirect()->back()->withInput();
        }

        $data = [];
        $this->db->transStart();

        // Menghapus data peserta sebelumnya
        $this->pesertaModel->where('id_penugasan', $id)->delete();
        // Only insert new participants if there are any
        if ($numParticipants > 0) {
            $insertData = [];
            // This is the problematic loop: ensure $nama is an array before iterating
            foreach ($nama as $key => $value) {
                $insertData[] = [
                    'id_penugasan' => $id,
                    'nama' => $nama[$key] ?? null, // Use null coalescing for safety
                    'nip' => $nip[$key] ?? null,
                    'pangkat' => $pangkat[$key] ?? null,
                    'jabatan' => $jabatan[$key] ?? null,
                    'instansi' => $instansi[$key] ?? null,
                    'lokasi_berangkat' => $lokasi_berangkat[$key] ?? '',
                    'peran' => $peran[$key] ?? null,
                    'urutan' => $key + 1,
                ];
            }
            $this->pesertaModel->insertBatch($insertData);
        }
        $penugasanData = [
            'updated_by' => $session->get('id_pengguna'),
            'updated_by_name' => $session->get('nama'),
            'updated_at' => date('Y-m-d H:i:s')
        ];
        $this->penugasanModel->update($id, $penugasanData);
        // Reset status ST menjadi 'belum diajukan'
        $this->stModel
            ->where('id_penugasan', $id)
            ->set('status_st', 'belum diajukan')
            ->update();
        $this->db->transComplete();
        return redirect()->to("/penugasan/draft_st/$id");
    }
}
