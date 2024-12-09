<?php

namespace App\Models;

use CodeIgniter\Model;

class ItemModel extends Model
{
    protected $table      = 'items'; // Nama tabel
    protected $primaryKey = 'id'; // Primary key tabel

    protected $allowedFields = ['name', 'description', 'price']; // Kolom yang boleh diisi
}
