<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class DatabaseTestController extends Controller
{
    public function index()
    {
        // Cek koneksi database
        $db = \Config\Database::connect();

        if ($db->connect()) {
            return "Koneksi ke database berhasil!";
        } else {
            return "Koneksi ke database gagal!";
        }
    }
}
