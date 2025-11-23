<?php

namespace App\Controllers;

use App\Database\Migrations\Role;
use App\Models\RoleModel;

class UserController extends BaseController
{

    protected $roleModel;

    public function __construct()
    {

        $this->roleModel = new RoleModel();
    }

    public function switchRole()
    {

        $newRole = $this->request->getPost('switch_role');
        $session = session();
        $roles = session()->get('roles');

        if (in_array($newRole, $roles)) {
            session()->set('current_role', $newRole);
            $roleName = $this->roleModel->getRoleName($newRole);

            session()->setFlashdata('message', 'Anda Masuk Sebagai ' . $roleName);

            return redirect()->to('penugasan/dashboard');
        } else {
            return redirect()->back()->with('error', 'Invalid role.');
        }
    }
}
