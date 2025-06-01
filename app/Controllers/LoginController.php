<?php
namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UsersModel;
use CodeIgniter\API\ResponseTrait;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

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
            // 'exp'  => time() + 3600,
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
            'data'    => $user,
        ]);
    }

    public function changePassword()
    {
        $authHeader = $this->request->getHeaderLine('Authorization');
        if (! $authHeader || ! str_starts_with($authHeader, 'Bearer ')) {
            return $this->failUnauthorized('Token tidak ditemukan');
        }

        $token = str_replace('Bearer ', '', $authHeader);
        $key   = getenv('token_secret');

        try {
            $decoded  = JWT::decode($token, new Key($key, 'HS256'));
            $userData = (array) $decoded->data;
        } catch (\Exception $e) {
            return $this->failUnauthorized('Token tidak valid: ' . $e->getMessage());
        }

        // Ambil dan validasi input
        $rules = [
            'current_password' => 'required',
            'new_password'     => 'required|min_length[6]',
        ];

        if (! $this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $currentPassword = $this->request->getVar('current_password');
        $newPassword     = $this->request->getVar('new_password');

        $userModel = new UsersModel();
        $user      = $userModel->find($userData['id']);

        if (! $user || ! password_verify($currentPassword, $user['password'])) {
            return $this->fail('Password lama salah.', 400);
        }

        // Update password
        $userModel->update($user['id'], [
            'password' => password_hash($newPassword, PASSWORD_DEFAULT),
        ]);

        return $this->respond(['message' => 'Password berhasil diubah']);
    }
}
