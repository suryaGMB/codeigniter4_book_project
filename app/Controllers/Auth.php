<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use CodeIgniter\HTTP\ResponseInterface;

class Auth extends BaseController
{
    protected $helpers = ['form', 'url'];

    public function register()
    {
        return view('auth/register');
    }

    public function storeRegister()
    {
        $rules = [
            'name'                  => 'required|min_length[2]|max_length[255]',
            'email'                 => 'required|valid_email|is_unique[users.email]',
            'password'              => 'required|min_length[6]',
            'password_confirmation' => 'required|matches[password]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $user = new \App\Models\UserModel();
        $user->insert([
            'name'     => $this->request->getPost('name'),
            'email'    => $this->request->getPost('email'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role'     => 'user',
        ]);

        return redirect()->to('/login')->with('message', 'Registration successful. Please login.');
    }

    public function login()
    {
        return view('auth/login');
    }

    public function doLogin()
    {
        $rules = [
            'email'    => 'required|valid_email',
            'password' => 'required',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $userModel = new \App\Models\UserModel();
        $user = $userModel->where('email', $this->request->getPost('email'))->first();

        if (! $user || ! password_verify($this->request->getPost('password'), $user['password'])) {
            return redirect()->back()->withInput()->with('errors', ['Invalid email or password.']);
        }

        session()->set([
            'user_id'    => $user['id'],
            'name'       => $user['name'],
            'email'      => $user['email'],
            'role'       => $user['role'], 
            'isLoggedIn' => true,
        ]);

        return ($user['role'] === 'admin')
            ? redirect()->to('/books')->with('message', 'Welcome admin!')
            : redirect()->to('/dashboard')->with('message', 'Welcome back, '.$user['name'].'!');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login')->with('message', 'Logged out successfully.');
    }
}
