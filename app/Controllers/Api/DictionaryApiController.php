<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use App\Models\WordModel;

class DictionaryApiController extends ResourceController
{
    // Instantiate the WordModel
    protected $wordModel;

    public function __construct()
    {
        $this->wordModel = new WordModel();
    }

    // Get all words
    public function index()
    {
        $words = $this->wordModel->findAll();
        return $this->respond($words, 200, 'application/json');
    }

    // Get a single word by id
    public function show($id = null)
    {
        $word = $this->wordModel->find($id);
        if ($word) {
            return $this->respond($word, 200, 'application/json');
        }
        return $this->failNotFound("Word with id $id not found");
    }

    // Add a new word
    public function create()
    {
        // Validasi input
        $validation = \Config\Services::validation();
        $validation->setRules([
            'japanese' => 'required|string|max_length[100]',
            'romaji'   => 'required|string|max_length[100]',
            'meaning'  => 'required|string|max_length[255]',
        ]);

        // Mengambil data dari request (input JSON)
        $data = $this->request->getJSON(true); // Mengambil input dalam format JSON

        // Validasi data
        if (!$validation->run($data)) {
            return $this->failValidationErrors($validation->getErrors());
        }

        // Insert data ke database
        $insertId = $this->wordModel->insert($data);

        // Jika insert berhasil
        if ($insertId) {
            return $this->respondCreated([
                'message' => 'Word successfully created',
                'data' => array_merge($data, ['id' => $insertId]) // Menggabungkan ID yang dihasilkan dengan data
            ], 201, 'application/json');
        }

        // Jika insert gagal
        return $this->failServerError('Failed to create word');
    }

    // Update an existing word
    public function update($id = null)
    {
        // Validasi input untuk update
        $validation = \Config\Services::validation();
        $validation->setRules([
            'japanese' => 'required|string|max_length[100]',
            'romaji'   => 'required|string|max_length[100]',
            'meaning'  => 'required|string|max_length[255]',
        ]);

        // Mengambil data input untuk update
        $data = $this->request->getJSON(true); // Mengambil input dalam format JSON

        // Validasi input
        if (!$validation->run($data)) {
            return $this->failValidationErrors($validation->getErrors());
        }

        // Jika update berhasil
        if ($this->wordModel->update($id, $data)) {
            return $this->respondUpdated([
                'message' => 'Word successfully updated',
                'data' => $data
            ], 200, 'application/json');
        }

        // Jika update gagal
        return $this->failNotFound("Word with id $id not found or update failed");
    }

    // Delete a word
    public function delete($id = null)
    {
        // Periksa apakah kata ada di database
        $word = $this->wordModel->find($id);
        if (!$word) {
            return $this->failNotFound("Word with id $id not found");
        }

        // Hapus kata
        if ($this->wordModel->delete($id)) {
            return $this->respondDeleted([
                'message' => 'Word successfully deleted',
                'id' => $id
            ], 200, 'application/json');
        }

        return $this->failServerError("Failed to delete word with id $id");
    }
}
