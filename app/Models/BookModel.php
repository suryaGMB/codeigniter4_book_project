<?php

namespace App\Models;

use CodeIgniter\Model;

class BookModel extends Model
{
    protected $table            = 'books';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;

    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields = ['title', 'author', 'price', 'pdf_file'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'title'  => 'required|min_length[2]|max_length[255]',
        'author' => 'required|min_length[2]|max_length[255]',
        'price'  => 'required|decimal'
    ];
}
