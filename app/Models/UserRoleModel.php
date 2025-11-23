<?php

namespace App\Models;

use CodeIgniter\Model;

class UserRoleModel extends Model
{
    protected $table            = 'user_role';
    protected $primaryKey       = 'id';

    protected $allowedFields = [
        'id_pengguna',
        'id_role'
    ];

    public function getUserRole($id_pengguna)
    {
        return $this->select('*')
            ->where('id_pengguna', $id_pengguna)
            ->get()
            ->getResultArray();
    }
}
