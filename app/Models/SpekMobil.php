<?php

namespace App\Models;

use CodeIgniter\Model;

class SpekMobil extends Model
{
    protected $table            = 'spesifikasi';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['id_mobil', 'tipe_mesin', 'tenaga', 'torsi', 'sistem_penggerak', 'fitur_keamanan', 'sistem_hiburan', 'konektivitas', 'ac'];

    // public function getmobilwithmerek(){
    //     return $this->select('spesifikasi.*, mobil.model')
    //     ->join('mobil', 'mobil.id = spesifikasi.id_mobil')
    //     ->findAll();
    // }
}
