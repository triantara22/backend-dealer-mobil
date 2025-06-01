<?php
namespace App\Models;

use CodeIgniter\Model;

class Dashboardmodel extends Model
{
    protected $DBGroup = 'default';

    public function getTotalPenjualan()
    {
        return $this->db->table('penjualan')
            ->where('MONTH(tanggal_transaksi)', date('m'))
            ->where('YEAR(tanggal_transaksi)', date('Y'))
            ->where('deleted', 0)
            ->countAllResults();
    }

    public function getTotalPendapatan()
    {
        return $this->db->table('pembayaran')
            ->selectSum('jumlah_bayar', 'total')
            ->join('penjualan', 'penjualan.id = pembayaran.penjualan_id')
            ->where('MONTH(penjualan.tanggal_transaksi)', date('m'))
            ->where('YEAR(penjualan.tanggal_transaksi)', date('Y'))
            ->where('penjualan.deleted', 0)
            ->get()
            ->getRow();
    }

    public function getTotalStok()
    {
        return $this->db->table('mobil')
            ->selectSum('stok', 'total_stok')
            ->get()
            ->getRow();
    }

    public function getGrafikPenjualan()
    {
        return $this->db->query("
            SELECT
                MONTH(tanggal_transaksi) AS bulan,
                YEAR(tanggal_transaksi) AS tahun,
                COUNT(*) AS total
            FROM penjualan
            WHERE deleted = 0
            GROUP BY tahun, bulan
            ORDER BY tahun, bulan
        ")->getResult();
    }

    public function getPenjualanPerMerek()
    {
        return $this->db->query("
            SELECT m.merek, COUNT(*) AS total
            FROM penjualan p
            JOIN mobil m ON m.id = p.mobil_id
            WHERE p.deleted = 0
            GROUP BY m.merek
        ")->getResult();
    }
}
