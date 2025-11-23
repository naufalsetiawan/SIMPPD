<?php

namespace App\Models;

use CodeIgniter\Model;

class RoleModel extends Model
{
    protected $table            = 'role';
    protected $primaryKey       = 'id_role';

    public function getRoleName($id_role)
    {
        $role = $this->where('id_role', $id_role)
            ->select('nama_role')
            ->first();

        return $role['nama_role'];
    }
}
