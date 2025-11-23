<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'user';
    protected $primaryKey       = 'id_pengguna';

    protected $allowedFields = [
        'username',
        'password',
        'status',
        'id_pegawai'
    ];

    public function getUserDetails($username)
    {
        $user = $this->select('user.*, p.nama, GROUP_CONCAT(user_role.id_role) as roles')
            ->join('user_role', 'user.id_pengguna = user_role.id_pengguna', 'left')
            ->join('pegawai p', 'p.id_pegawai = user.id_pegawai', 'left')
            ->where('user.username', $username)
            ->groupBy('user.id_pengguna')
            ->first();

        return $user;
    }

    public function getUsersPaginated($perPage = 10, $search = null)
    {
        $builder = $this->select('user.*, pegawai.nama, pegawai.nip')
            ->join('user_role', 'user_role.id_pengguna = user.id_pengguna')
            ->join('pegawai', 'pegawai.id_pegawai = user.id_pegawai', 'left')
            ->where('user_role.id_role !=', 0)
            ->groupBy('user.id_pengguna');

        if ($search) {
            $builder->groupStart()
                ->like('pegawai.nama', $search)
                ->orLike('pegawai.nip', $search)
                ->groupEnd();
        }

        return $builder->paginate($perPage);
    }

    public function getUserPegawai($id_pengguna)
    {
        return $this->select('user.*, p.*')
            ->join('pegawai p', 'p.id_pegawai = user.id_pegawai', 'left')
            ->where('user.id_pengguna', $id_pengguna)
            ->first();
    }
}
