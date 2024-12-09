<?php
namespace App\Controllers;
use CodeIgniter\RESTful\ResourceController;
use App\Models\UserModel;  // Pastikan UserModel sudah diimport
use App\Models\ItemModel;
use Firebase\JWT\JWT;

class ApiController extends ResourceController
{
    protected $modelName = 'App\Models\ItemModel'; // Menunjuk ke model ItemModel
    protected $format    = 'json';

    // Endpoint untuk mengambil semua item
    public function getItems()
    {
        $items = $this->model->findAll(); // Mengambil semua data
        return $this->respond($items);
    }

    // Endpoint untuk menambahkan item baru
    public function addItem()
    {
        $data = $this->request->getPost(); // Mengambil data dari body request (JSON)
        if ($this->model->insert($data)) {
            return $this->respondCreated($data); // Mengirimkan respons jika data berhasil ditambahkan
        }
        return $this->fail('Data gagal ditambahkan');
    }

    // Endpoint untuk mengupdate item berdasarkan ID
    public function updateItem($id)
    {
        $data = $this->request->getRawInput(); // Mengambil data mentah dari body request
        if ($this->model->update($id, $data)) {
            return $this->respond($data); // Mengirimkan respons data yang diupdate
        }
        return $this->failNotFound('Item tidak ditemukan');
    }

    // Endpoint untuk menghapus item berdasarkan ID
    public function deleteItem($id)
    {
        if ($this->model->delete($id)) {
            return $this->respondDeleted(); // Mengirimkan respons jika data berhasil dihapus
        }
        return $this->failNotFound('Item tidak ditemukan');
    }

    // Endpoint untuk login
    public function login()
    {
        $username = $this->request->getVar('username');
        $password = $this->request->getVar('password');

        if (empty($username) || empty($password)) {
            return $this->failUnauthorized('Username atau password tidak boleh kosong');
        }

        // Validasi user
        $userModel = new UserModel();
        $user = $userModel->where('username', $username)->first();

        if (!$user || !password_verify($password, $user['password'])) {
            return $this->failUnauthorized('Username atau password salah');
        }

        // Membuat JWT Token
        $key = getenv('JWT_SECRET_KEY'); // Pastikan sudah ditambahkan di .env
        $iat = time();
        $exp = $iat + 3600; // Token berlaku selama 1 jam
        $payload = [
            'iat' => $iat,
            'exp' => $exp,
            'sub' => $user['id'], // id pengguna
        ];

     $jwt = JWT::encode($payload, $key, 'HS256');  // Menambahkan algoritma sebagai argumen ketiga

        return $this->respond([
            'status' => 'success',
            'message' => 'Login berhasil',
            'token' => $jwt,
        ]);
    }

    // Endpoint untuk mengambil profil pengguna
    public function profile()
    {
        $authHeader = $this->request->getHeader('Authorization');
        if (!$authHeader) {
            return $this->failUnauthorized('Token tidak ditemukan');
        }
    
        $token = str_replace('Bearer ', '', $authHeader);
    
        try {
            $key = getenv('JWT_SECRET_KEY');
            // Jika parameter ketiga tidak diperlukan, hapus atau ubah sesuai kebutuhan
            $decoded = JWT::decode($token, $key, ['HS256']);
    
            $userId = $decoded->sub; // Ambil id pengguna dari token
    
            // Ambil data pengguna dari database
            $userModel = new UserModel();
            $user = $userModel->find($userId);
    
            if (!$user) {
                return $this->failNotFound('User tidak ditemukan');
            }
    
            return $this->respond([
                'status' => 'success',
                'message' => 'Profil berhasil dimuat',
                'data' => [
                    'name' => $user['name'],
                    'username' => $user['username'],
                    'email' => $user['email'],
                    'tanggal_lahir' => $user['tanggal_lahir'], // Pastikan ini sesuai dengan field yang ada di database
                ],
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error decoding JWT: ' . $e->getMessage());
            return $this->failUnauthorized('Token tidak valid: ' . $e->getMessage());
        }
    }
    
    
    // UserModel.php

            public function createUser($username, $password)
            {
                $data = [
                    'username' => $username,
                    'password' => password_hash($password, PASSWORD_DEFAULT) // Hash password
                ];
                return $this->insert($data);
            }




}
