<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class RoleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (! session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('errors', ['Please login first.']);
        }

        if ($arguments && count($arguments) > 0) {
            $allowed = $arguments;
            $role = session('role');
            if (! in_array($role, $allowed, true)) {
                return redirect()->back()->with('errors', ['You are not allowed to access this page.']);
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
