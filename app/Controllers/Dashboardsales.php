<?php
namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PembayaranModel;
use App\Models\PenjualanModel;
use CodeIgniter\API\ResponseTrait;

class Dashboardsales extends BaseController
{
    use ResponseTrait;

    public function index()
    {
        $penjualanModel  = new PenjualanModel();
        $pembayaranModel = new PembayaranModel();

        // Ambil data total penjualan bulan ini
        $totalPenjualan = $penjualanModel
            ->where('MONTH(tanggal_transaksi)', date('m'))
            ->where('YEAR(tanggal_transaksi)', date('Y'))
            ->where('deleted', 0)
            ->countAllResults();

        // Total pelanggan unik bulan ini
        $totalPelanggan = $penjualanModel
            ->where('MONTH(tanggal_transaksi)', date('m'))
            ->where('YEAR(tanggal_transaksi)', date('Y'))
            ->where('deleted', 0)
            ->distinct()
            ->countAll();

        // Total pendapatan (bulan ini)
        $totalPendapatan = $penjualanModel
            ->selectSum('total_harga')
            ->where('MONTH(tanggal_transaksi)', date('m'))
            ->where('YEAR(tanggal_transaksi)', date('Y'))
            ->where('deleted', 0)
            ->get()->getRow()->total_harga ?? 0;

        // Komisi (misalnya 20% dari total pendapatan)
        $komisi = $totalPendapatan * 0.2;

        // Data grafik per bulan tahun ini dan tahun lalu
        $grafikTahunIni  = [];
        $grafikTahunLalu = [];

        for ($i = 1; $i <= 12; $i++) {
            $grafikTahunIni[] = $penjualanModel
                ->where('MONTH(tanggal_transaksi)', $i)
                ->where('YEAR(tanggal_transaksi)', date('Y'))
                ->where('deleted', 0)
                ->countAllResults();

            $grafikTahunLalu[] = $penjualanModel
                ->where('MONTH(tanggal_transaksi)', $i)
                ->where('YEAR(tanggal_transaksi)', date('Y') - 1)
                ->where('deleted', 0)
                ->countAllResults();
        }

        $data = [
            'total_penjualan'  => $totalPenjualan,
            'total_pelanggan'  => $totalPelanggan,
            'total_pendapatan' => (float) $totalPendapatan,
            'komisi'           => (float) $komisi,
            'grafik_penjualan' => [
                'tahun_ini'  => $grafikTahunIni,
                'tahun_lalu' => $grafikTahunLalu,
            ],
        ];

        return $this->respond($data);
    }
}
