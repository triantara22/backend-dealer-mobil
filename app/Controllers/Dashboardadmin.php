<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\Dashboardmodel;
use CodeIgniter\API\ResponseTrait;
class Dashboardadmin extends BaseController
{
    protected $model;
    use ResponseTrait;
    public function __construct()
    {
        $this->model = new Dashboardmodel();
    }

    public function index()
    {
        $penjualan     = $this->model->getTotalPenjualan();
        $pendapatan    = $this->model->getTotalPendapatan();
        $stok          = $this->model->getTotalStok();
        $grafik        = $this->model->getGrafikPenjualan();
        $perMerek      = $this->model->getPenjualanPerMerek();

        $response = [
            'total_penjualan'       => $penjualan,
            'total_pendapatan'      => $pendapatan->total ?? 0,
            'total_stok'            => $stok->total_stok ?? 0,
            'grafik_penjualan'      => $grafik,
            'penjualan_per_merek'   => $perMerek
        ];

        return $this->respond($response);
    }
}
