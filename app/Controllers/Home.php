<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class Home extends Controller
{
    // Menampilkan halaman create account
    public function createAccount()
    {
        return view('auth/create_account');
    }
}
