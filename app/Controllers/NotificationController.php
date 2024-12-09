<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\NotificationModel;

class NotificationController extends ResourceController
{
    // Mendapatkan semua notifikasi
    public function index()
    {
        $model = new NotificationModel();
        $notifications = $model->findAll(); // Mengambil semua data notifikasi
        return $this->respond($notifications); // Mengembalikan data dalam format JSON
    }

    // Mendapatkan notifikasi berdasarkan ID
    public function show($id = null)
    {
        $model = new NotificationModel();
        $notification = $model->find($id); // Mengambil data notifikasi berdasarkan ID
        if ($notification) {
            return $this->respond($notification); // Mengembalikan data notifikasi
        } else {
            return $this->failNotFound('Notification not found'); // Jika data tidak ditemukan
        }
    }

    // Menambahkan notifikasi baru
    public function create()
    {
        $model = new NotificationModel();
        $data = $this->request->getPost(); // Mengambil data dari request POST

        // Validasi input
        if (!$this->validate([
            'title' => 'required|string|max_length[255]',
            'message' => 'required|string',
            'time' => 'required|string|max_length[255]'
        ])) {
            return $this->failValidationErrors('Validation failed');
        }

        $model->insert($data); // Menyimpan data notifikasi ke database
        return $this->respondCreated($data); // Mengembalikan response data yang telah ditambahkan
    }

    // Mengupdate notifikasi berdasarkan ID
    public function update($id = null)
    {
        $model = new NotificationModel();
        $data = $this->request->getRawInput(); // Mengambil data dari request PUT

        // Validasi input
        if (!$this->validate([
            'title' => 'required|string|max_length[255]',
            'message' => 'required|string',
            'time' => 'required|string|max_length[255]'
        ])) {
            return $this->failValidationErrors('Validation failed');
        }

        $existingNotification = $model->find($id);
        if ($existingNotification) {
            $model->update($id, $data); // Mengupdate data berdasarkan ID
            return $this->respond($data); // Mengembalikan data yang sudah diupdate
        } else {
            return $this->failNotFound('Notification not found');
        }
    }

    // Menghapus notifikasi berdasarkan ID
    public function delete($id = null)
    {
        $model = new NotificationModel();
        $existingNotification = $model->find($id);
        if ($existingNotification) {
            $model->delete($id); // Menghapus data berdasarkan ID
            return $this->respondDeleted(['id' => $id]); // Mengembalikan response dengan ID yang dihapus
        } else {
            return $this->failNotFound('Notification not found');
        }
    }
}
