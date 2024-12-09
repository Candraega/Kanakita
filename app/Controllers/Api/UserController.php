<?php
namespace App\Controllers\Api;

use App\Models\UserModel;
use CodeIgniter\RESTful\ResourceController;

class UserController extends ResourceController
{
    protected $modelName = 'App\Models\UserModel';
    protected $format    = 'json';

    // Rute untuk membuat akun baru
    public function create()
    {
        // Mengambil data yang dikirim dari form
        $data = $this->request->getJSON(true);

        // Melakukan validasi input (pastikan username, password, dan data lainnya ada)
        if (!$data['username'] || !$data['password'] || !$data['name'] || !$data['email'] || !$data['tanggal_lahir']) {
            return $this->failValidationError('Username, Password, Name, Email, and Tanggal Lahir are required');
        }

        // Hash password
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

        // Simpan ke database
        if ($this->model->save($data)) {
            return $this->respondCreated($data); // Mengirim respons sukses
        }

        return $this->failServerError('Failed to create account'); // Gagal membuat akun
    }
    public function getProfile()
    {
        // Contoh: Ambil data pengguna berdasarkan ID dari sesi atau token
        $userId = session()->get('user_id'); // Sesuaikan dengan metode autentikasi kamu

        if (!$userId) {
            return $this->failUnauthorized('User is not authenticated');
        }

        $user = $this->model->find($userId);

        if (!$user) {
            return $this->failNotFound('User not found');
        }

        // Kembalikan data pengguna (kecuali password)
        unset($user['password']); // Jangan kirim password
        return $this->respond($user);
    }


    public function updateProfile()
{
    // Ambil ID pengguna dari sesi atau token
    $userId = session()->get('user_id'); // Sesuaikan dengan metode autentikasi kamu

    if (!$userId) {
        return $this->failUnauthorized('User is not authenticated');
    }

    // Validasi data input
    $validation = \Config\Services::validation();
    $validation->setRules([
        'name' => 'required|string|max_length[255]',
        'username' => "required|string|max_length[255]|is_unique[users.username,id,{$userId}]",
        'email' => "required|valid_email|is_unique[users.email,id,{$userId}]",
        'password' => 'permit_empty|string|min_length[8]',
        'tanggal_lahir' => 'permit_empty|valid_date',
    ]);

    if (!$validation->withRequest($this->request)->run()) {
        return $this->failValidationErrors($validation->getErrors());
    }

    // Ambil data dari request
    $data = [
        'name' => $this->request->getVar('name'),
        'username' => $this->request->getVar('username'),
        'email' => $this->request->getVar('email'),
        'tanggal_lahir' => $this->request->getVar('tanggal_lahir'),
    ];

    // Jika password diubah, tambahkan ke data
    $password = $this->request->getVar('password');
    if (!empty($password)) {
        $data['password'] = password_hash($password, PASSWORD_DEFAULT);
    }

    // Update data di database
    if ($this->model->update($userId, $data)) {
        return $this->respond(['message' => 'Profile updated successfully']);
    }

    return $this->failServerError('Failed to update profile');
}


}
