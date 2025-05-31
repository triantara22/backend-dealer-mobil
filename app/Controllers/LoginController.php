<?php
namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UsersModel;
use CodeIgniter\API\ResponseTrait;
use Firebase\JWT\JWT;

class LoginController extends BaseController
{

    use ResponseTrait;
    public function login()
    {
        $validation = \Config\Services::validation();
        $rules      = [
            'username' => 'required',
            'password' => 'required',
        ];

        $erors = [
            'username' => [
                'required' => 'Username harus diisi',
            ],
            'password' => [
                'required' => 'Password harus diisi',
            ],
        ];

        // cek validasi
        if (! $this->validate($rules, $erors)) {
            return $this->failValidationErrors($validation->getErrors());
        }

        // Ambil input yang sudah divalidasi
        $username = $this->request->getVar('username');
        $password = $this->request->getVar('password');

        // Cek user
        $userModel = new UsersModel();
        $user      = $userModel->where('username', $username)->first();

        if (! $user || ! password_verify($password, $user['password'])) {
            return $this->respond(['message' => 'Username atau password salah'], 401);
        }

        // Payload JWT
        $payload = [
            'iat'  => time(),
            'exp'  => time() + 3600,
            'data' => [
                'id'       => $user['id'],
                'username' => $user['username'],
                'email'    => $user['email'],
                'role'     => $user['role'],
            ],
        ];

        $key   = getenv('token_secret');
        $token = JWT::encode($payload, $key, 'HS256');

        return $this->respond([
            'message' => 'Login berhasil',
            'token'   => $token,
        ]);
    }
}
