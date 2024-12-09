<?php 
namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';  // Nama tabel yang sesuai
    protected $primaryKey = 'id';
    protected $allowedFields = ['name','username', 'password','email','tanggal_lahir'];
    protected $useTimestamps = true;

    // Fungsi untuk memverifikasi login
    public function verifyPassword($username, $password)
    {
        $user = $this->where('username', $username)->first();  // Cek berdasarkan username

        if ($user && password_verify($password, $user['password'])) {
            return $user;  // Jika password cocok
        }

        return false;  // Jika tidak cocok
    }
}
