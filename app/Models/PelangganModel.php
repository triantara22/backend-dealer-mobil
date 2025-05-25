<?php
namespace App\Models;

use CodeIgniter\Model;

class PelangganModel extends Model
{
    protected $table            = 'pelanggan';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['id','nama', 'email', 'alamat', 'telepon'];

    public function getpelanggan()
    {
        return $this->select('pelanggan.*')
            ->orderBy('pelanggan.id', 'DESC')
            ->findall();
    }

    public function filternama($nama)
    {
        return $this->select('pelanggan.*')
            ->like('pelanggan.nama', $nama)
            ->findall();
    }
        public function generateTransactionId()
    {
        $prefix = 'CSR-P'; // Awalan yang diinginkan

        // Cari transaksi terakhir dengan awalan CSR-P
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
