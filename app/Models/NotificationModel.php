<?php

namespace App\Models;

use CodeIgniter\Model;

class NotificationModel extends Model
{
    protected $table = 'notifications'; // Nama tabel
    protected $primaryKey = 'id'; // Primary Key
    protected $allowedFields = ['title', 'message', 'time']; // Kolom yang bisa diinputkan
    protected $useTimestamps = false; // Tidak menggunakan timestamps
}
