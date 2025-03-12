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
    protected $allowedFields    = ['merek_id', 'model', 'tahun', 'harga', 'stok', 'gambar'];

    public function getmobilwithmerek(){
        return $this->select('mobil.*, merek.nama')
        ->join('merek', 'merek.id = mobil.merek_id')
        ->findAll();
    }
    public function getmobilwithmereklike($nama){
        return $this->select('mobil.*, merek.nama')
        ->join('merek', 'merek.id = mobil.merek_id')
        ->like('merek.nama',$nama)
        ->findAll();
    }
}
