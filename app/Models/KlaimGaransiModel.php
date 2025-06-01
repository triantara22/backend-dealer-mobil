<?php
namespace App\Models;

use CodeIgniter\Model;

class KlaimGaransiModel extends Model
{
    protected $table            = 'klaimgaransi';
    protected $primaryKey       = 'idklaim';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['idklaim','garansi_id', 'klaim_deskripsi', 'klaim_tanggal'];

    public function getklaimgaransi()
    {
        return $this->select('klaimgaransi.*, garansi.pelanggan_id, garansi.mobil_id, garansi.klaim_status, mobil.model, pelanggan.nama')
            ->join('garansi', 'klaimgaransi.garansi_id = garansi.idg')
            ->join('pelanggan', 'garansi.pelanggan_id = pelanggan.id')
            ->join('mobil', 'garansi.mobil_id = mobil.id')
            ->orderBy('klaimgaransi.idklaim', 'DESC')
            ->findall();
    }

    public function getklaimgaransiid($id)
    {
        return $this->select('klaimgaransi.*', 'garansi.*')
            ->join('garansi', 'klaimgaransi.garansi_id = garansi.id')
            ->orderBy('klaimgaransi.idklaim', 'DESC')
            ->findall($id);
    }

    public function generateId()
    {
        $prefix = 'KGI-C'; // Awalan yang diinginkan

        // Cari transaksi terakhir dengan awalan PJN-
        $lastTransaction = $this->like('idklaim', $prefix, 'after')
            ->orderBy('idklaim', 'DESC')
            ->first();

        if ($lastTransaction) {
            // Ambil angka setelah prefix
            $lastNumber = (int) str_replace($prefix, '', $lastTransaction['idklaim']);
            $nextNumber = $lastNumber + 1;
        } else {
            // Jika tidak ada transaksi, mulai dari 1
            $nextNumber = 1;
        }

        // Format dengan leading zeros (3 digit)
        return $prefix . str_pad($nextNumber, 2, '0', STR_PAD_LEFT);
    }
}
