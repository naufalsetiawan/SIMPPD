<?php

namespace App\Controllers;

use App\Database\Migrations\Penugasan;
use App\Models\UserModel;
use App\Models\RoleModel;

class AuthController extends BaseController
{
    protected $userModel;
    protected $roleModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->roleModel = new RoleModel();
    }

    public function login()
    {
        $session = session();

        if ($this->request->getMethod() === 'POST') {
            $username = $this->request->getPost('username');
            $password = $this->request->getPost('password');

            if (empty($username) || empty($username)) {
                $session->setFlashdata('error', 'Username and password are required.');
                return redirect()->to('/login');
            }

            $user = $this->userModel->getUserDetails($username);

            if (!$user || $user['status'] !== 'aktif') {
                $session->setFlashdata('error', 'Invalid username or account inactive.');
                return redirect()->to('/login');
            }

            if ($password !== $user['password']) {
                $session->setFlashdata('error', 'Invalid username or password.');
                return redirect()->to('/login');
            }

            $roles = explode(',', $user['roles']);
            $currentRole = !empty($roles) ? min($roles) : null;

            // Fetch role names for each role
            $roleNames = [];
            foreach ($roles as $role) {
                $roleNames[$role] = $this->roleModel->getRoleName($role); // Store the role name in an array
            }

            if ($currentRole == 0) {
                $id_pegawai = 0;
                $nama = 'Admin';
            } else {
                $id_pegawai = $user['id_pegawai'];
                $nama = $user['nama'];
            }

            $session->set([
                'id_pengguna' => $user['id_pengguna'],
                'username' => $user['username'],
                'id_pegawai' => $id_pegawai,
                'nama' => $nama,
                'roles' => $roles,
                'current_role' => $currentRole,
                'isLoggedIn' => true,
                'role_names' => $roleNames,
            ]);

            log_message('debug', 'Session setelah login: ' . print_r(session()->get(), true));


            // Redirect to appropriate dashboard
            if (in_array(0, $session->get('roles'))) {
                return redirect()->to('/admin/dashboard');
            }

            $roleName = $this->roleModel->getRoleName($currentRole);

            session()->setFlashdata('message', 'Anda Masuk Sebagai ' . $roleName);

            return redirect()->to('penugasan/dashboard');
        }

        return view('login/login_page');
    }


    public function logout()
    {
        $session = session();
        $session->destroy();
        return redirect()->to('/login');
    }
}
