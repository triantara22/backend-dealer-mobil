<?php
namespace App\Models;

use CodeIgniter\Model;

class PembayaranModel extends Model
{
    protected $table            = 'pembayaran';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['penjualan_id', 'metode_pembayaran', 'jumlah_bayar'];

    public function filterpembayaran($id)
    {
        return $this->select('pembayaran.*, penjualan.pelanggan_id, penjualan.mobil_id,penjualan.tanggal_transaksi')
            ->join('penjualan', 'pembayaran.penjualan_id = penjualan.id')
            ->like('penjualan_id', $id)
            ->findAll();
    }
}
