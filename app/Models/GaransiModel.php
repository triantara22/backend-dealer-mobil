<?php
namespace App\Models;

use CodeIgniter\Model;

class GaransiModel extends Model
{
    protected $table            = 'garansi';
    protected $primaryKey       = 'idg';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['idg', 'mobil_id', 'pelanggan_id', 'tanggal_mulai', 'tanggal_berakhir', 'detail_garansi', 'klaim_status'];

    public function getdatagaransi()
    {
        return $this->select('garansi.idg,mobil_id,garansi.pelanggan_id,garansi.tanggal_mulai,garansi.tanggal_berakhir,
        garansi.detail_garansi,garansi.klaim_status, mobil.model, pelanggan.nama')
            ->join('mobil', 'garansi.mobil_id = mobil.id')
            ->join('pelanggan', 'garansi.pelanggan_id = pelanggan.id')
            ->orderBy('garansi.tanggal_mulai', 'DESC')
            ->findall();
    }

    public function getdatagaransiid($id)
    {
        return $this->select('garansi.*, mobil.model, pelanggan.nama')
            ->join('mobil', 'garansi.mobil_id = mobil.id')
            ->join('pelanggan', 'garansi.pelanggan_id = pelanggan.id')
            ->orderBy('garansi.tanggal_mulai', 'DESC')
            ->find($id);
    }
    public function filter($nama, $klaim_status)
    {
        return $this->select('garansi.id,mobil_id,garansi.pelanggan_id,garansi.tanggal_mulai,garansi.tanggal_berakhir,
        garansi.detail_garansi,garansi.klaim_status,, mobil.model, pelanggan.nama')
            ->join('mobil', 'garansi.mobil_id = mobil.id')
            ->join('pelanggan', 'garansi.pelanggan_id = pelanggan.id')
            ->like('pelanggan.nama', $nama)
            ->like('garansi.klaim_status', $klaim_status)
            ->findall();
    }

    public function generateIdg()
    {
        $prefix = 'DGI-D'; // Awalan yang diinginkan

        // Cari transaksi terakhir dengan awalan PJN-
        $lastTransaction = $this->like('idg', $prefix, 'after')
            ->orderBy('idg', 'DESC')
            ->first();

        if ($lastTransaction) {
            // Ambil angka setelah prefix
            $lastNumber = (int) str_replace($prefix, '', $lastTransaction['idg']);
            $nextNumber = $lastNumber + 1;
        } else {
            // Jika tidak ada transaksi, mulai dari 1
            $nextNumber = 1;
        }

        // Format dengan leading zeros (3 digit)
        return $prefix . str_pad($nextNumber, 2, '0', STR_PAD_LEFT);
    }
}
