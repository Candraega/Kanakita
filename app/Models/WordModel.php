<?php

namespace App\Models;

use CodeIgniter\Model;

class WordModel extends Model
{
    protected $table = 'words';
    protected $primaryKey = 'id';
    protected $allowedFields = ['japanese', 'romaji', 'meaning'];
    protected $useTimestamps = false; // If you don't have timestamps in the table
}
