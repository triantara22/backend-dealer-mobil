<?php
namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PelangganModel;
use App\Models\PenjualanModel;
use CodeIgniter\API\ResponseTrait;
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
    public function create()
    {
        $id            = $this->pelangganmodel->generateTransactionId();
        $datapelanggan = [
            'id'      => $id,
            'nama'    => $this->request->getVar('nama'),
            'alamat'  => $this->request->getVar('alamat'),
            'telepon' => $this->request->getVar('telepon'),
            'email'   => $this->request->getVar('email'),
        ];

        $this->pelangganmodel->insert($datapelanggan, true);
        return $this->respondCreated([
            'status'  => true,
            'message' => 'Data Berhasil Ditambahkan',
            'data'    => [
                $datapelanggan,
            ],
        ]);
    }

}
