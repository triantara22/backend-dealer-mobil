<?php
namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\LayananModel;
use CodeIgniter\API\ResponseTrait;
use Exception;

class LayananController extends BaseController
{
    protected $layananmodel;
    use ResponseTrait;
    public function __construct()
    {
        $this->layananmodel = new LayananModel();
    }

    public function index()
    {
        try {
            $data = $this->layananmodel->getlayanan();
            if (empty($data)) {
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

    public function create()
    {
        $id          = $this->layananmodel->generateId();
        $datalayanan = [
            'id'              => $id,
            'pelanggan_id'    => esc($this->request->getVar('pelanggan_id')),
            'mobil_id'        => esc($this->request->getVar('mobil_id')),
            'tanggal_layanan' => esc($this->request->getVar('tanggal_layanan')),
            'deskripsi'       => esc($this->request->getVar('deskripsi')),
            'biaya'           => esc($this->request->getVar('biaya')),
        ];

        $this->layananmodel->insert($datalayanan, true);
        return $this->respondCreated([
            'status'  => true,
            'message' => 'Data Berhasil Ditambahkan',
            'data'    => [
                $datalayanan,
            ],
        ]);
    }

    public function filter($tanggal, $nama, $model)
    {
        try {
            $data = $this->layananmodel->filter($tanggal, $nama, $model);
            if (empty($data)) {
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
}
