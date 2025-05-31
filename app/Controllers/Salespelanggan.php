<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\API\ResponseTrait;
use App\Models\PelangganModel;
use App\Models\PenjualanModel;
use Exception;
class Salespelanggan extends BaseController
{
    protected $pelangganmodel;
    protected $penjualanmodel;
    use ResponseTrait;

    public function __construct()
    {
        $this->pelangganmodel = new PelangganModel();
        $this->penjualanmodel = new PenjualanModel();
    }
    public function index()
    {
        try {
            $data = $this->pelangganmodel->getpelanggan();
            if (empty($data)) {
                return $this->response->setStatusCode(200)->setJSON([
                    'status'  => true,
                    'message' => 'Data pelanggan Tidak Tersedia',
                    'data'    => [],
                ]);
            }
            return $this->response->setStatusCode(200)->setJSON([
                'status'  => true,
                'message' => 'Data Ditemukan',
                'data'    => $data,
            ]);
        } catch (Exception $e) {
            return $this->response->setStatusCode(500)->setJSON([
                'Status'  => false,
                'message' => "Internal server eror" . $e->getMessage(),
                'data'    => [],
            ]);
        }
    }
}
