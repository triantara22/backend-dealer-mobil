<?php
namespace App\Models;

use CodeIgniter\Model;

class SpekModel extends Model
{
    protected $table            = 'spesifikasi';
    protected $primaryKey       = 'id_spek';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['id_mobil', 'tipe_mesin', 'tenaga', 'torsi', 'sistem_penggerak', 'fitur_keamanan',
        'sistem_hiburan', 'konektivitas', 'ac'];
}
