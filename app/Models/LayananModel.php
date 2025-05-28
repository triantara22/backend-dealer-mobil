<?php
namespace App\Models;

use CodeIgniter\Model;

class LayananModel extends Model
{
    protected $table            = 'services';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['id', 'pelanggan_id', 'mobil_id', 'tanggal_layanan', 'deskripsi', 'biaya'];

    public function getlayanan()
    {
        return $this->select('services.*, pelanggan.nama, mobil.model')
            ->join('pelanggan', 'services.pelanggan_id = pelanggan.id')
            ->join('mobil', 'services.mobil_id = mobil.id')
            ->orderBy('services.tanggal_layanan', 'DESC')
            ->findAll();
    }

    public function filter($tanggal, $nama, $model)
    {
        return $this->select('services.*, pelanggan.nama, mobil.model')
            ->join('pelanggan', 'services.pelanggan_id = pelanggan.id')
            ->join('mobil', 'services.mobil_id = mobil.id')
            ->like('services.tanggal_layanan', $tanggal)
            ->like('pelanggan.nama', $nama)
            ->like('mobil.model', $model)
            ->findall();
    }

    public function generateId()
    {
        $prefix = 'LYN-A'; // Awalan yang diinginkan

        // Cari transaksi terakhir dengan awalan PJN-
        $lastTransaction = $this->like('id', $prefix, 'after')
            ->orderBy('id', 'DESC')
            ->first();

        if ($lastTransaction) {
            // Ambil angka setelah prefix
            $lastNumber = (int) str_replace($prefix, '', $lastTransaction['id']);
            $nextNumber = $lastNumber + 1;
        } else {
            // Jika tidak ada transaksi, mulai dari 1
            $nextNumber = 1;
        }

        // Format dengan leading zeros (3 digit)
        return $prefix . str_pad($nextNumber, 2, '0', STR_PAD_LEFT);
    }
}
