<?php
namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\GaransiModel;
use App\Models\LayananModel;

class Dashboardcs extends BaseController
{
    public function index()
    {
        $layananModel = new LayananModel();
        $garansiModel = new GaransiModel();
        // Total semua layanan
        $totalLayanan = $layananModel->countAllResults();

        // Layanan dengan status pending
        $pending = $garansiModel
            ->where('klaim_status', 'Pending')
            ->countAllResults();

        // Layanan dengan status selesai
        $selesai = $garansiModel
            ->where('klaim_status', 'Selesai')
            ->countAllResults();

        // Pemasukan dari layanan selesai (asumsi kolom: biaya)
        $pemasukan = $layananModel
            ->selectSum('biaya')
            ->first()['biaya'];

        return $this->response->setJSON([
            'totalLayanan'   => $totalLayanan,
            'layananPending' => $pending,
            'layananSelesai' => $selesai,
            'pemasukan'      => 'Rp. ' . number_format($pemasukan, 0, ',', '.'),
        ]);
    }

    public function grafikLayanan()
    {
        $layananModel = new LayananModel();

        // Ambil tahun sekarang dan tahun sebelumnya
        $tahunIni  = date('Y');
        $tahunLalu = $tahunIni - 1;

        $data = [
            "labels"     => [
                "Jan", "Feb", "Mar", "Apr", "Mei", "Jun",
                "Jul", "Agu", "Sep", "Okt", "Nov", "Des",
            ],
            "tahun_ini"  => array_fill(0, 12, 0),
            "tahun_lalu" => array_fill(0, 12, 0),
        ];

        $results = $layananModel
            ->select("MONTH(tanggal_layanan) AS bulan, YEAR(tanggal_layanan) AS tahun, SUM(biaya) AS total")
            ->groupBy("tahun, bulan")
            ->orderBy("tahun, bulan")
            ->findAll();

        foreach ($results as $row) {
            $bulan = (int) $row['bulan'] - 1;
            if ($row['tahun'] == $tahunIni) {
                $data['tahun_ini'][$bulan] = (float) $row['total'];
            } elseif ($row['tahun'] == $tahunLalu) {
                $data['tahun_lalu'][$bulan] = (float) $row['total'];
            }
        }

        return $this->response->setJSON($data);
    }

}
