<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\API\ResponseTrait;
use App\Models\PelangganModel;
use App\Models\PenjualanModel;
use Exception;
class PelangganController extends BaseController
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
                    'message' => 'Data Mobil Tidak Tersedia',
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

    public function detail($id)
    {
        try {
            $data = $this->pelangganmodel->find($id);
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
        $id = $this->pelangganmodel->generateTransactionId();
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

    public function update($id)
    {
        $datapelanggan = [
            'nama'    => $this->request->getVar('nama'),
            'alamat'  => $this->request->getVar('alamat'),
            'telepon' => $this->request->getVar('telepon'),
            'email'   => $this->request->getVar('email'),
        ];
        $this->pelangganmodel->update($id, $datapelanggan);
        return $this->respondUpdated([
            'status'  => true,
            'message' => 'Data Berhasil Diupdate',
            'data'    => [
                $datapelanggan,
            ],
        ]);
    }

    public function delete($id)
    {
        $this->pelangganmodel->delete($id);
        return $this->respondDeleted([
            'status'  => true,
            'message' => 'Data Berhasil Dihapus',
            'data'    => [],
        ]);
    }

    public function filter($nama)
    {
        try {
        $data = $this->pelangganmodel->filternama($nama);
        if(empty($data)){
            return $this->response->setStatusCode(404)->setJSON([
                'status'  => false,
                'message' => 'Data Mobil Tidak Tersedia',
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

    public function histori($id)
    {
        try {
        $data = $this->penjualanmodel->histori($id);
        if(empty($data)){
            return $this->response->setStatusCode(404)->setJSON([
                'status'  => false,
                'message' => 'Data Histori Tidak Tersedia',
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