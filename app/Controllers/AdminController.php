<?php

namespace App\Controllers;

use App\Database\Migrations\Provinsi;
use App\Database\Migrations\RiwayatPetugas;
use App\Models\SuratTugasModel;
use App\Models\UserModel;
use App\Models\UserRoleModel;
use App\Models\PegawaiModel;
use App\Models\RoleModel;
use App\Models\LandasanModel;
use App\Models\ProvinsiModel;
use App\Models\KotaModel;
use App\Models\PetugasModel;
use App\Models\TimKerjaModel;

use App\Models\PenugasanModel;
use App\Models\TanggalTugasModel;
use App\Models\TujuanTugasModel;

class AdminController extends BaseController
{
    protected $pegawaiModel;
    protected $stModel;
    protected $userModel;
    protected $userRoleModel;
    protected $roleModel;
    protected $landasanModel;
    protected $provinsiModel;
    protected $kotaModel;
    protected $timKerjaModel;
    protected $petugasModel;
    protected $db;

    protected $penugasanModel;
    protected $tujuanModel;
    protected $tanggalTugasModel;


    public function __construct()
    {
        $this->pegawaiModel = new PegawaiModel();
        $this->userModel = new UserModel();
        $this->stModel = new SuratTugasModel();
        $this->userRoleModel = new UserRoleModel();
        $this->roleModel = new RoleModel();
        $this->landasanModel = new LandasanModel();
        $this->provinsiModel = new ProvinsiModel();
        $this->kotaModel = new KotaModel();
        $this->timKerjaModel = new TimKerjaModel();
        $this->petugasModel = new PetugasModel();
        $this->db = \Config\Database::connect();

        helper(["format_tanggal", "url"]);
        helper(["format_lokasi"]);

        $this->penugasanModel = new PenugasanModel();
        $this->tujuanModel = new TujuanTugasModel();
        $this->tanggalTugasModel = new TanggalTugasModel();
    }

    public function dashboard()
    {
        $session = session();

        $nama = $session->get('nama');
        $currentRole = $session->get('current_role');

        $session = session();

        $dataPenugasan = $this->penugasanModel->getLastPenugasan();

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

        $jenisCounts = $this->penugasanModel->getCountByJenisDanStatus('penugasan luar kantor', 'disetujui');
        $setujuCounts = $this->penugasanModel->getCountByJenisDanStatus(null, 'disetujui');
        $diajukanCounts = $this->penugasanModel->getCountByJenisDanStatus(null, 'sedang diajukan');

        // Pie Chart: sub_jenis_penugasan
        $subJenisData = $this->penugasanModel->getCountBySubJenis();
        $subJenisLabels = [];
        $subJenisValues = [];

        foreach ($subJenisData as $item) {
            $subJenisLabels[] = $item['sub_jenis_penugasan'];
            $subJenisValues[] = $item['total'];
        }

        // Line Chart: Disetujui per bulan
        $approvedPerMonth = $this->penugasanModel->getApprovedCountByMonth();
        $bulanLabels = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Okt", "Nov", "Des"];
        $bulanData = array_fill(0, 12, 0); // Inisialisasi 12 bulan

        foreach ($approvedPerMonth as $row) {
            $bulanData[((int)$row['bulan']) - 1] = $row['total'];
        }

        $data = [
            'nama' => $nama,
            'currentRole' => $currentRole,
            'penugasan' => $data,
            'setujuCounts' => $setujuCounts['total'] ?? 0,
            'diajukanCounts' => $diajukanCounts['total'] ?? 0,
            'jenisCounts' => $jenisCounts['total'] ?? 0,
            'chart_subjenis_labels' => json_encode($subJenisLabels),
            'chart_subjenis_values' => json_encode($subJenisValues),
            'chart_bulan_labels' => json_encode($bulanLabels),  // pastikan ini array string "Jan", "Feb", dll
            'chart_bulan_values' => json_encode(array_values($bulanData)), // ini array angka 0-12
        ];
        return view('dashboard/dashboard_admin', $data);
    }

    public function kop_surat()
    {
        $session = session();
        return view('admin/kop_surat');
    }

    public function kop_surat_upload()
    {
        if ($this->request->getMethod() !== 'POST') {
            $this->logger->warning('Invalid request method.');
            return redirect()->back()->with('error', 'Invalid request method.');
        }

        $file = $this->request->getFile('kop_surat');
        if (!$file || !$file->isValid()) {
            $this->logger->error('No file selected or file upload error.');
            return redirect()->back()->with('error', 'No file selected or file upload error.');
        }

        if ($file->getClientMimeType() !== 'image/png') {
            $this->logger->error('File must be a PNG image.');
            return redirect()->back()->with('error', 'File must be a PNG image.');
        }

        $targetPath = FCPATH . 'img/kop_surat.png';

        try {
            $file->move(dirname($targetPath), basename($targetPath), true);
            $this->logger->info('File uploaded successfully: ' . $file->getClientName());
            return redirect()->back()->with('success', 'File uploaded successfully.');
        } catch (\Exception $e) {
            $this->logger->critical('Failed to upload file: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to upload file: ' . $e->getMessage());
        }
    }

    public function list_tim_kerja()
    {
        $tim_kerja = $this->timKerjaModel->findAll();

        $data = [
            'tim_kerja' => $tim_kerja
        ];

        return view('admin/list_tim_kerja', $data);
    }

    public function save_tim_kerja()
    {
        $session = session();
        $mode = $this->request->getPost('form-mode');
        $nama_tim_kerja = $this->request->getPost('nama_tim_kerja');
        $original_id = $this->request->getPost('original-id');  // ID tim kerja untuk edit

        // Validasi input
        $validation = \Config\Services::validation();
        $validation->setRules([
            'nama_tim_kerja' => 'required|min_length[4]',
        ]);

        if (!$validation->run(['nama_tim_kerja' => $nama_tim_kerja])) {
            return $this->response->setJSON([
                'status' => 'error',
                'errors' => $validation->getErrors(),
            ]);
        }

        $data = [
            'nama_tim_kerja' => $nama_tim_kerja,
        ];

        if ($mode === 'add') {
            // Insert new data
            if (!$this->timKerjaModel->insert($data)) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Failed to save data.',
                ]);
            }
        } elseif ($mode === 'edit') {
            if (!$this->timKerjaModel->update($original_id, $data)) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Failed to update data.',
                ]);
            }
        }
        return $this->response->setJSON(['status' => 'success']);
    }

    public function delete_tim_kerja()
    {
        $ids = $this->request->getPost('id_tim_kerja');

        // Decode if the input is a JSON string
        if (is_string($ids)) {
            $ids = json_decode($ids, true); // true = decode as array
        }

        // Basic validation
        if (empty($ids) || !is_array($ids)) {
            return redirect()->back()->with('error', 'Tidak ada data yang dipilih untuk dihapus.');
        }

        $timKerjaModel = new TimKerjaModel();

        // Perform deletion for each ID
        foreach ($ids as $id) {
            $timKerjaModel->delete($id);
        }

        return redirect()->to('/admin/tim_kerja')->with('success', 'Tim Kerja yang dipilih berhasil dihapus.');
    }


    public function list_users()
    {
        $perPage = 10;
        $search = $this->request->getGet('search'); // Ambil parameter search dari query string

        $users = $this->userModel->getUsersPaginated($perPage, $search);
        $pager = $this->userModel->pager;

        foreach ($users as &$user) {
            $user['roles'] = $this->userRoleModel->getUserRole($user['id_pengguna']);
        }

        $roles = $this->roleModel->where('id_role !=', 0)->findAll();
        $pegawai = $this->pegawaiModel->getAllPegawai();

        return view('admin/list_users', [
            'users' => $users,
            'pagerUsers' => $pager,
            'roles' => $roles,
            'pegawai' => $pegawai,
            'search' => $search,
        ]);
    }

    public function save_user()
    {
        log_message('debug', 'Received request: ' . json_encode($this->request->getPost()));
        $mode = $this->request->getPost('form-mode');
        $id = $this->request->getPost('original-id');
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');
        $status = $this->request->getPost('status');
        $id_pegawai = $this->request->getPost('pegawai');
        $roles = $this->request->getPost('roles');

        $errors = [];
        $data = [
            'username' => $username,
            'password' => $password,
            'status' => $status,
            'id_pegawai' => $id_pegawai,
        ];
        log_message('debug', 'Data to insert: ' . json_encode($data));

        // Validasi Username dan Password
        if (!$this->validateUsernameAndPassword($data)) {
            $errors['username'] = 'Username should be 4-30 characters, no spaces, and only alphanumeric.';
            $errors['password'] = 'Password should be between 8-30 characters.';
        }
        if ($mode === 'add') {
            // saat tambah, cek username apakah sudah ada
            $existingUser = $this->userModel
                ->where('username', $data['username'])
                ->first();
        } else if ($mode === 'edit') {
            // saat edit, cek username sama tapi bukan user yang sedang diedit
            $existingUser = $this->userModel
                ->where('username', $data['username'])
                ->where('id_pengguna !=', $id)
                ->first();
        }

        if ($existingUser) {
            $errors['username'] = 'Username sudah digunakan oleh pengguna lain.';
        }

        // Cek jika Pegawai sudah dikaitkan dengan user lain
        if ($this->isEmployeeLinked($id_pegawai, $id)) {
            $errors['pegawai'] = 'This employee is already linked to another user.';
        }

        // Pastikan setidaknya satu role dipilih
        if (empty($roles) || !is_array($roles)) {
            $errors['roles'] = 'At least one role must be selected.';
        }

        if (!empty($errors)) {
            return $this->response->setJSON([
                'status' => 'error',
                'errors' => $errors,
            ]);
        }

        // Start the transaction
        log_message('debug', 'Starting transaction...');
        $this->db->transStart(); // Begin transaction

        if ($mode === 'add') {
            log_message('debug', 'Adding new user...');
            // Insert user data
            if (!$this->userModel->insert($data)) {
                log_message('error', 'Failed to insert user: ' . json_encode($data));
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Failed to insert user.',
                ]);
            }

            // Insert user roles
            foreach ($roles as $role) {
                $this->userRoleModel->insert([
                    'id_pengguna' => $this->userModel->getInsertID(), // Ensure to link the user ID
                    'id_role' => $role,
                ]);
            }
        } elseif ($mode === 'edit') {
            // Prepare data for updating
            $data = [
                'username' => $username,
                'status' => $status,
                'id_pegawai' => $id_pegawai,
            ];

            // Check if a new password is provided
            if (!empty($password)) {
                // Only update the password if it's not empty
                $data['password'] = $password;
            }

            // Update user data
            if (!$this->userModel->update($id, $data)) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Failed to update user.',
                ]);
            }

            // Remove existing roles for the user
            $this->userRoleModel->where('id_pengguna', $id)->delete();

            // Insert new roles
            foreach ($roles as $role) {
                $this->userRoleModel->insert([
                    'id_pengguna' => $id,
                    'id_role' => $role,
                ]);
            }
        }

        // Complete the transaction (commit if all operations succeeded)
        $this->db->transComplete();

        // Check if transaction was successful
        if ($this->db->transStatus() === FALSE) {
            // Rollback in case of an error
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Transaction failed. Rolling back.',
            ]);
        }

        return $this->response->setJSON(['status' => 'success']);
    }

    public function delete_user()
    {
        $ids = $this->request->getPost('id_pengguna');

        // Decode if the input is a JSON string
        if (is_string($ids)) {
            $ids = json_decode($ids, true); // true = decode as array
        }

        // Basic validation
        if (empty($ids) || !is_array($ids)) {
            return redirect()->back()->with('error', 'Tidak ada data yang dipilih untuk dihapus.');
        }

        foreach ($ids as $id) {
            $this->userModel->delete($id);
        }

        return redirect()->to('/admin/users')->with('success', 'Tim Kerja yang dipilih berhasil dihapus.');
    }

    private function validateUsernameAndPassword($data)
    {
        $username = $data['username'];
        $password = $data['password'];

        // Validasi Username
        if (!preg_match('/^[a-zA-Z0-9]{4,30}$/', $username)) {
            return false; // Username is invalid
        }

        // Validasi Password (if provided)
        if (!empty($password) && (strlen($password) < 8 || strlen($password) > 30)) {
            return false; // Password is invalid
        }

        return true;
    }

    private function isEmployeeLinked($pegawai_id, $user_id)
    {
        // Cek apakah pegawai sudah terdaftar di user lain
        $user = $this->userModel->where('id_pegawai', $pegawai_id)->where('id_pengguna !=', $user_id)->first();

        return !empty($user); // Jika ada user lain yang terkait, return true
    }

    public function list_pegawai()
    {
        $session = session();

        $tim_kerja_list = $this->timKerjaModel->getAllTimKerja();

        $search = $this->request->getGet('search') ?? '';
        $timKerja = $this->request->getGet('timKerja') ?? 'semua';
        $perPage = 10;


        $builder = $this->pegawaiModel;

        if ($search) {
            $builder = $builder->groupStart()
                ->like('nama', $search)
                ->orLike('nip', $search)
                ->groupEnd();
        }

        if ($timKerja !== 'semua') {
            $builder = $builder->where('tim_kerja', $timKerja);
        }

        $pegawai = $builder->paginate($perPage, 'pegawai');
        $pagerPegawai = $builder->pager;
        $timKerjaList = $this->timKerjaModel->getAllTimKerja();
        $data = [
            'list_tim_kerja' => $tim_kerja_list,
            'pegawai' => $pegawai,
            'pagerPegawai' => $pagerPegawai,
            'search' => $search,
            'timKerja' => $timKerja,
            'tim_kerja_list' => $timKerjaList
        ];

        return view('admin/list_pegawai', $data);
    }

    public function save_pegawai()
    {
        $session = session();
        $mode = $this->request->getPost('form-mode');
        $nama = $this->request->getPost('nama');
        $nip = $this->request->getPost('nip');
        $pangkat = $this->request->getPost('pangkat');
        $jabatan = $this->request->getPost('jabatan');
        $tim_kerja = $this->request->getPost('tim_kerja');
        $original_id = $this->request->getPost('original-id');  // ID tim kerja untuk edit

        $errors = [];

        // Validasi: semua field tidak boleh kosong
        if (empty(trim($nama))) {
            $errors['nama'] = 'Nama harus diisi.';
        }
        if (empty(trim($nip))) {
            $errors['nip'] = 'NIP harus diisi.';
        }
        if (empty(trim($pangkat))) {
            $errors['pangkat'] = 'Pangkat harus diisi.';
        }
        if (empty(trim($jabatan))) {
            $errors['jabatan'] = 'Jabatan harus diisi.';
        }
        if (empty(trim($tim_kerja))) {
            $errors['tim_kerja'] = 'Tim kerja harus dipilih.';
        }

        // Validasi: cek unik NIP
        if (!empty($nip)) {
            if ($mode === 'add') {
                // Saat tambah, cek apakah NIP sudah ada
                $existingPegawai = $this->pegawaiModel->where('nip', $nip)->first();
            } elseif ($mode === 'edit') {
                // Saat edit, cek NIP yang sama tapi bukan data yang sedang diedit
                $existingPegawai = $this->pegawaiModel
                    ->where('nip', $nip)
                    ->where('id_pegawai !=', $original_id)
                    ->first();
            }

            if (!empty($existingPegawai)) {
                $errors['nip'] = 'NIP sudah digunakan oleh pegawai lain.';
            }
        }

        // Jika ada error, kembalikan response error
        if (!empty($errors)) {
            return $this->response->setJSON([
                'status' => 'error',
                'errors' => $errors,
            ]);
        }


        $data = [
            'nama' => $nama,
            'nip' => $nip,
            'pangkat' => $pangkat,
            'jabatan' => $jabatan,
            'tim_kerja' => $tim_kerja
        ];

        if ($mode === 'add') {
            if (!$this->pegawaiModel->insert($data)) {
                log_message('error', 'Gagal insert pegawai: ' . json_encode($this->pegawaiModel->errors()));
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Failed to save data.',
                ]);
            }
        } elseif ($mode === 'edit') {
            if (!$this->pegawaiModel->update($original_id, $data)) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Failed to update data.',
                ]);
            }
        }
        return $this->response->setJSON(['status' => 'success']);
    }

    public function delete_pegawai()
    {
        $ids = $this->request->getPost('id_pegawai');

        // Decode if the input is a JSON string
        if (is_string($ids)) {
            $ids = json_decode($ids, true); // true = decode as array
        }

        // Basic validation
        if (empty($ids) || !is_array($ids)) {
            return redirect()->back()->with('error', 'Tidak ada data yang dipilih untuk dihapus.');
        }

        $this->db->transStart();

        // 1. Update status ke nonaktif (gunakan updateBatch)
        $data = [];
        foreach ($ids as $id) {
            $data[] = [
                'id_pegawai' => $id,
                'status'     => 'nonaktif'
            ];
        }
        $this->userModel->updateBatch($data, 'id_pegawai');

        // 2. Kosongkan id_pegawai
        $this->userModel
            ->whereIn('id_pegawai', $ids)
            ->set('id_pegawai', null)
            ->update();

        // 3. Hapus pegawai
        foreach ($ids as $id) {
            if (!$this->pegawaiModel->delete($id)) {
                $this->db->transRollback();
                return redirect()->to('/admin/pegawai')->with('error', 'Gagal menghapus Tim Kerja.');
            }
        }

        $this->db->transComplete();

        if ($this->db->transStatus() === false) {
            return redirect()->to('/admin/pegawai')->with('error', 'Gagal menghapus Tim Kerja.');
        }

        return redirect()->to('/admin/pegawai')->with('success', 'Tim Kerja yang dipilih berhasil dihapus.');
    }


    public function add_pegawai_csv()
    {
        $file = $this->request->getFile('csv_pegawai');

        if (!$file || !$file->isValid() || $file->hasMoved()) {
            return redirect()->to('/admin/pegawai')->with('error', 'Upload file tidak valid.');
        }

        // Simpan file sementara
        $newFileName = $file->getRandomName();
        $file->move(WRITEPATH . 'uploads/admin/pegawai', $newFileName);
        $filePath = WRITEPATH . 'uploads/admin/pegawai/' . $newFileName;

        // Baca data CSV
        $csvData = array_map('str_getcsv', file($filePath));

        // Hapus header jika ada
        if (!empty($csvData)) {
            $header = array_map('strtolower', $csvData[0]);
            if (in_array('nama', $header) && in_array('nip', $header)) {
                array_shift($csvData);
            }
        }

        // Mulai transaksi database
        $this->db->transBegin();

        try {
            // Ambil semua pegawai lama
            $pegawaiList = $this->pegawaiModel->select('id_pegawai')->findAll();
            $ids = array_column($pegawaiList, 'id_pegawai');

            if (!empty($ids)) {
                // Update status user ke nonaktif
                $data = array_map(fn($id) => ['id_pegawai' => $id, 'status' => 'nonaktif'], $ids);
                $this->userModel->updateBatch($data, 'id_pegawai');

                // Kosongkan id_pegawai di tabel user
                $this->userModel
                    ->whereIn('id_pegawai', $ids)
                    ->set('id_pegawai', null)
                    ->update();

                // Hapus semua data pegawai
                $this->pegawaiModel->whereIn('id_pegawai', $ids)->delete();
            }

            // Masukkan data baru dari CSV
            foreach ($csvData as $index => $row) {
                if (count($row) < 5) {
                    throw new \Exception("Baris ke-" . ($index + 2) . " tidak valid.");
                }

                $this->pegawaiModel->insert([
                    'nama'      => trim($row[0]),
                    'nip'       => trim($row[1]),
                    'pangkat'   => trim($row[2]),
                    'jabatan'   => trim($row[3]),
                    'tim_kerja' => trim($row[4]),
                ]);
            }

            // Commit transaksi
            $this->db->transCommit();

            return redirect()->to('/admin/pegawai')->with('message', 'CSV berhasil diimpor!');
        } catch (\Throwable $e) {
            // Rollback jika terjadi error
            $this->db->transRollback();
            log_message('error', 'Gagal impor pegawai dari CSV: ' . $e->getMessage());

            return redirect()->to('/admin/pegawai')->with('error', 'Gagal mengimpor data: ' . $e->getMessage());
        }
    }

    public function landasan_surat()
    {
        $session = session();

        // Pagination settings for Menimbang
        $menimbangModel = $this->landasanModel;
        $menimbang = $menimbangModel->where('jenis', 'menimbang')->orderBy('sub_jenis_penugasan', 'ASC')->paginate(10, 'menimbang');
        $menimbangGrouped = [];
        foreach ($menimbang as $item) {
            $menimbangGrouped[$item['sub_jenis_penugasan']][] = [
                'id' => $item['id'],
                'butir' => $item['butir']
            ];
        }
        // Pagination settings for Dasar
        $dasarModel = $this->landasanModel;
        $dasar = $dasarModel->where('jenis', 'dasar')->orderBy('sub_jenis_penugasan', 'ASC')->paginate(10, 'dasar');
        $dasarGrouped = [];
        foreach ($dasar as $item) {
            $dasarGrouped[$item['sub_jenis_penugasan']][] = [
                'id' => $item['id'],
                'butir' => $item['butir']
            ];
        }
        log_message('debug', 'Menimbang Grouped: ' . print_r($menimbangGrouped, true));
        log_message('debug', 'Dasar Grouped: ' . print_r($dasarGrouped, true));


        $data = [
            'menimbang' => $menimbangGrouped,
            'menimbangPager' => $menimbangModel->pager,
            'dasar' => $dasarGrouped,
            'dasarPager' => $dasarModel->pager,
        ];

        return view('admin/landasan_surat', $data);
    }

    public function daftar_kota()
    {
        $session = session();
        $provinsi = $this->provinsiModel->findAll();
        $provinsiModel = new ProvinsiModel();
        $kotaModel = new KotaModel();

        $currentPage = $this->request->getVar('page_kota') ?? 1;

        $provinsi = $provinsiModel->paginate(10, 'provinsi');
        $pagerProvinsi = $provinsiModel->pager;

        $kota = $kotaModel->getPaginatedKota(50, $currentPage);
        $pagerKota = $kotaModel->pager;

        $groupedKota = [];
        foreach ($kota as $k) {
            $groupedKota[$k['nama_provinsi']][] = [
                'id_kota' => $k['id_kota'],
                'nama_kota' => $k['nama_kota']
            ];
        }

        $data = [
            'provinsi' => $provinsi,
            'pagerProvinsi' => $pagerProvinsi, // Separate pager for provinces
            'kota' => $groupedKota,
            'pagerKota' => $pagerKota
        ];

        return view('admin/list_kota', $data);
    }

    public function list_petugas()
    {
        $session = session();

        $pegawai = $this->pegawaiModel->getAllPegawai();
        $petugas = $this->petugasModel->getPetugas();
        $riwayat_petugas = $this->petugasModel->getRiwayatPetugas();

        // Inisialisasi array untuk menyimpan data yang sudah dipisah
        $kepala = [];
        $plt          = [];
        $plh          = [];
        $ppk          = [];
        $riwayat_kepala = [];
        $riwayat_plt  = [];
        $riwayat_plh  = [];
        $riwayat_ppk  = [];

        $formatTanggal = function ($date) {
            if (empty($date) || $date === '0000-00-00') {
                return '-';
            }
            return date('d-m-Y', strtotime($date));
        };


        // Pisahkan petugas berdasarkan jenis dan format tanggal
        foreach ($petugas as $p) {
            $p['tanggal_mulai']   = $formatTanggal($p['tanggal_mulai']);
            $p['tanggal_selesai'] = $formatTanggal($p['tanggal_selesai']);

            switch (strtolower($p['jenis'])) {
                case 'kepala':
                    $kepala[] = $p;
                    break;
                case 'plt':
                    $plt[] = $p;
                    break;
                case 'plh':
                    $plh[] = $p;
                    break;
                case 'ppk':
                    $ppk[] = $p;
                    break;
            }
        }

        // Pisahkan riwayat petugas berdasarkan jenis dan format tanggal
        foreach ($riwayat_petugas as $p) {
            $p['tanggal_mulai']   = $formatTanggal($p['tanggal_mulai']);
            $p['tanggal_selesai'] = $formatTanggal($p['tanggal_selesai']);

            switch (strtolower($p['jenis'])) {
                case 'kepala':
                    $riwayat_kepala[] = $p;
                    break;
                case 'plt':
                    $riwayat_plt[] = $p;
                    break;
                case 'plh':
                    $riwayat_plh[] = $p;
                    break;
                case 'ppk':
                    $riwayat_ppk[] = $p;
                    break;
            }
        }

        $data = [
            'pegawai'       => $pegawai,
            'kepala'           => $kepala,
            'plt'           => $plt,
            'plh'           => $plh,
            'ppk'           => $ppk,
            'riwayat_kepala'   => $riwayat_kepala,
            'riwayat_plt'   => $riwayat_plt,
            'riwayat_plh'   => $riwayat_plh,
            'riwayat_ppk'   => $riwayat_ppk,
        ];

        return view('admin/list_petugas', $data);
    }

    public function save_petugas()
    {
        $id_pegawai = $this->request->getPost('id_pegawai');
        $jenis = $this->request->getPost('jenis');
        $tanggal_mulai = $this->request->getPost('tanggal_mulai');
        $tanggal_selesai = $this->request->getPost('tanggal_selesai');

        $errors = [];

        // Validate required fields
        if (!$id_pegawai) {
            $errors['petugas'] = 'Pegawai harus dipilih.';
        }

        if (!$tanggal_mulai) {
            $errors['tanggal'] = 'Tanggal mulai harus diisi';
        }

        if ($tanggal_selesai !== '' && $tanggal_selesai < $tanggal_mulai) {
            $errors['tanggal'] = 'Tanggal selesai tidak boleh lebih awal dari tanggal mulai.';
        }

        if (!empty($errors)) {
            return $this->response->setJSON([
                'status' => 'error',
                'errors' => $errors
            ]);
        }

        $pegawai = $this->pegawaiModel->find($id_pegawai);
        if (!$pegawai) {
            return $this->response->setJSON([
                'status' => 'error',
                'errors' => ['petugas' => 'Pegawai tidak ditemukan.']
            ]);
        }

        $nama = $pegawai['nama'];
        $nip = $pegawai['nip'];
        $jabatan = $pegawai['jabatan'];

        try {
            $existing = $this->petugasModel
                ->where('nip', $nip)
                ->where('jenis', $jenis)
                ->groupStart()
                ->where('tanggal_selesai IS NULL', null, false)
                ->orWhere('tanggal_selesai >=', date('Y-m-d'))
                ->groupEnd()
                ->first();
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }

        if ($existing) {
            return $this->response->setJSON([
                'status' => 'error',
                'errors' => [
                    'petugas' => 'Pegawai ini masih menjabat.'
                ]
            ]);
        }

        $data = [
            'nama' => $nama,
            'nip' => $nip,
            'jabatan' => $jabatan,
            'jenis' => $jenis,
            'tanggal_mulai' => $tanggal_mulai,
            'tanggal_selesai' => ($tanggal_selesai === '' ? null : $tanggal_selesai)
        ];

        $this->petugasModel->insert($data);

        return $this->response->setJSON(['status' => 'success']);
    }

    public function nonaktif_petugas()
    {
        $idInput = $this->request->getPost('id');
        $id = json_decode($idInput, true);

        // Jika hasil decode berupa array, ambil elemen pertama
        if (is_array($id)) {
            $id = reset($id);
        }

        if (empty($id)) {
            return redirect()->back()->with('error', 'ID tidak valid atau kosong.');
        }

        $petugas = $this->petugasModel->find($id);

        if (!$petugas) {
            return redirect()->back()->with('error', 'Data petugas tidak ditemukan.');
        }

        // Set tanggal_selesai ke hari ini
        $this->petugasModel->update($id, [
            'tanggal_selesai' => date('Y-m-d')
        ]);

        return redirect()->back()->with('message', 'Petugas berhasil dinonaktifkan dan otomatis masuk riwayat.');
    }

    public function delete_petugas()
    {
        $idInput = $this->request->getPost('id');
        $id = json_decode($idInput, true);

        // Jika hasil decode berupa array, ambil elemen pertama
        if (is_array($id)) {
            $id = reset($id);
        }

        if (empty($id)) {
            return redirect()->back()->with('error', 'ID tidak valid atau kosong.');
        }

        $petugas = $this->petugasModel->find($id);

        if (!$petugas) {
            return redirect()->back()->with('error', 'Data petugas tidak ditemukan.');
        }

        // Hapus 
        $this->petugasModel->delete($id);

        return redirect()->back()->with('message', 'Petugas berhasil dihapus dan disimpan ke riwayat.');
    }

    public function import_provinsi()
    {
        $file = $this->request->getFile('csv_provinsi');

        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newFileName = $file->getRandomName();  // Generate a unique name
            $file->move(WRITEPATH . 'uploads/admin/', $newFileName);

            $filePath = WRITEPATH . 'uploads/admin/' . $newFileName;


            $csvData = array_map('str_getcsv', file($filePath));

            // Remove the header row if needed
            array_shift($csvData);


            $provinceModel = new ProvinsiModel();

            // Delete all rows instead of truncating
            $provinceModel->where('id_provinsi IS NOT NULL')->delete();


            foreach ($csvData as $row) {
                $provinceModel->insert([
                    'id_provinsi'   => trim($row[0]),
                    'nama_provinsi' => trim($row[1])
                ]);
            }

            return redirect()->to('/admin/kota')->with('message', 'CSV successfully imported!');
        }

        return redirect()->to('/admin/kota')->with('error', 'Invalid file upload');
    }

    public function import_kota()
    {
        $file = $this->request->getFile('csv_kota');

        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newFileName = $file->getRandomName();  // Generate a unique name
            $file->move(WRITEPATH . 'uploads/admin/', $newFileName);

            $filePath = WRITEPATH . 'uploads/admin/' . $newFileName;


            $csvData = array_map('str_getcsv', file($filePath));

            // Remove the header row if needed
            array_shift($csvData);


            $provinceModel = new KotaModel();

            // Delete all rows instead of truncating
            $provinceModel->where('id_kota IS NOT NULL')->delete();


            foreach ($csvData as $row) {
                $provinceModel->insert([
                    'id_kota'   => trim($row[0]),
                    'id_provinsi'   => trim($row[1]),
                    'nama_kota' => trim($row[2])
                ]);
            }

            return redirect()->to('/admin/kota')->with('message', 'CSV successfully imported!');
        }

        return redirect()->to('/admin/kota')->with('error', 'Invalid file upload');
    }

    public function save_landasan()
    {
        $session = session();
        $jenis = $this->request->getPost('jenis');
        $sub_jenis_penugasan = $this->request->getPost('sub_jenis_penugasan');
        $butir = $this->request->getPost('butir');

        // Validasi: butir tidak boleh kosong
        if (empty(trim($butir))) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Butir tidak boleh kosong.',
            ]);
        }

        // Validasi jenis hanya boleh menimbang / dasar
        if (!in_array($jenis, ['menimbang', 'dasar'])) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Jenis tidak valid.',
            ]);
        }

        $data = [
            'jenis'                 => $jenis,
            'sub_jenis_penugasan'   => $sub_jenis_penugasan,
            'butir'                 => $butir,
        ];

        if (!$this->landasanModel->insert($data)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Failed to save data.',
            ]);
        }
        return $this->response->setJSON(['status' => 'success']);
    }

    public function delete_landasan()
    {
        $ids = $this->request->getPost('id');
        $jenis = $this->request->getPost('jenisdelete');

        log_message('debug', 'Delete request received. IDs: ' . print_r($ids, true));
        log_message('debug', 'Jenis: ' . $jenis);


        // Decode if the input is a JSON string
        if (is_string($ids)) {
            $ids = json_decode($ids, true); // true = decode as array
            log_message('debug', 'Decoded IDs: ' . print_r($ids, true));
        }

        // Basic validation
        if (empty($ids) || !is_array($ids)) {
            return redirect()->back()->with('error', 'Tidak ada data yang dipilih untuk dihapus.');
        }

        if (!in_array($jenis, ['menimbang', 'dasar'])) {
            log_message('error', 'Jenis tidak valid: ' . $jenis);
            return redirect()->back()->with('error', 'Jenis tidak valid.');
        }

        // Delete, but ensure jenis cocok (lebih aman)
        foreach ($ids as $id) {
            $this->landasanModel
                ->where('id', $id)
                ->where('jenis', $jenis)
                ->delete();
        }

        return redirect()->to('/admin/landasan_surat')->with('success', 'Item yang dipilih berhasil dihapus.');
    }

    public function update_status_st()
    {
        $session = session();
        $id = $this->request->getPost('id');
        $status = $this->request->getPost('status');

        // Log ID dan status untuk debug
        $this->logger->debug("ID yang diterima: {$id}, Status yang diterima: {$status}");

        // Validasi ID dan status
        if (!$id || !$status) {
            $session->setFlashdata('error', 'ID atau status tidak valid.');
            $this->logger->warning("Gagal memperbarui status: ID atau status tidak valid. ID: {$id}, Status: {$status}");
            return redirect()->back();
        }

        // Update status
        $data = ['status_st' => $status];
        if ($this->stModel->update($id, $data)) {
            $session->setFlashdata('success', 'Status berhasil diperbarui.');
            $this->logger->info("Status berhasil diperbarui. ID: {$id}, Status baru: {$status}");
        } else {
            $session->setFlashdata('error', 'Gagal memperbarui status.');
            $this->logger->error("Gagal memperbarui status di database. ID: {$id}, Status: {$status}");
        }

        return redirect()->back();
    }
}
