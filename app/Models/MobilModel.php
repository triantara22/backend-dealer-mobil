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

    public function getmobil()
    {
        return $this->select('mobil.*')
            ->findAll();
    }
    public function getmobilwithid($id)
    {
        return $this->select('mobil.*, spesifikasi.*')
            ->join('spesifikasi', 'spesifikasi.id_mobil = mobil.id')
            ->find($id);
    }
    public function getmobilwithmereklike($nama, $harga, $tahun)
    {
        return $this->select('mobil.*')
            ->like('mobil.merek', $nama)
            ->like('mobil.harga', $harga)
            ->like('mobil.tahun', $tahun)
            ->findAll();
    }
}
