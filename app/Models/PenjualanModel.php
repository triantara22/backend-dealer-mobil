<?php
namespace App\Models;

use CodeIgniter\Model;

class PenjualanModel extends Model
{
    protected $table            = 'penjualan';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['id', 'pelanggan_id', 'mobil_id', 'user_id', 'tanggal_transaksi', 'total_harga', 'status_pembayaran'];

    public function getpenjualan()
    {
        return $this->select('penjualan.*, pelanggan.nama, mobil.model')
            ->join('pelanggan', 'penjualan.pelanggan_id = pelanggan.id')
            ->join('mobil', 'penjualan.mobil_id = mobil.id')
            ->findAll();
    }
    public function getpenjualanwithid($id)
    {
        return $this->select('penjualan.*, pelanggan.nama, mobil.model')
            ->join('pelanggan', 'penjualan.pelanggan_id = pelanggan.id')
            ->join('mobil', 'penjualan.mobil_id = mobil.id')
            ->findAll();
    }

    public function filter($tanggaltr, $status, $namamobil)
    {
        return $this->select('penjualan.*, pelanggan.nama, mobil.model')
            ->join('pelanggan', 'penjualan.pelanggan_id = pelanggan.id')
            ->join('mobil', 'penjualan.mobil_id = mobil.id')
            ->like('penjualan.tanggal_transaksi', $tanggaltr)
            ->like('mobil.model', $namamobil)
            ->like('penjualan.status_pembayaran', $status)
            ->findAll();
    }

    public function generateTransactionId()
    {
        $prefix = 'PJN-S'; // Awalan yang diinginkan

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
