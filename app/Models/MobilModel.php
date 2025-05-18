<?php
namespace App\Models;

use CodeIgniter\Model;

class MobilModel extends Model
{
    protected $table            = 'mobil';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['merek', 'model', 'tahun', 'harga', 'stok', 'gambar'];

}
