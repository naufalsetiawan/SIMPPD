<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class RoleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        // Cek apakah user sudah login
        if (!$session->has('current_role')) {
            return redirect()->to('/login');
        }

        // Jika ada role yang harus dipenuhi
        if ($arguments) {
            $userRole = $session->get('current_role');
            if (!in_array($userRole, $arguments)) {
                return redirect()->to('/penugasan/dashboard');
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Tidak perlu diisi
    }
}
