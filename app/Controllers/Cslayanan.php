<?php
namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\LayananModel;
use CodeIgniter\API\ResponseTrait;
use App\Models\MobilModel;
use App\Models\SpekModel;
use App\Models\PelangganModel;
use Exception;


class Cslayanan extends BaseController
{
    protected $layananmodel;
    protected $modelmobil;
    protected $spekModel;
    protected $pelangganmodel;
    use ResponseTrait;
    public function __construct()
    {
        $this->layananmodel = new LayananModel();
        $this->modelmobil = new MobilModel();
        $this->spekModel  = new SpekModel();
        $this->pelangganmodel = new PelangganModel();
    }

    public function index()
    {
        try {
            $data = $this->layananmodel->getlayanan();
            if (empty($data)) {
                return $this->response->setStatusCode(200)->setJSON([
                    'status'  => true,
                    'message' => 'Data layanan Tidak Tersedia',
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

    public function update($id)
    {

        try
        {
            $dataUpdate = [
                'pelanggan_id'    => esc($this->request->getVar('pelanggan_id')),
                'mobil_id'        => esc($this->request->getVar('mobil_id')),
                'tanggal_layanan' => esc($this->request->getVar('tanggal_layanan')),
                'deskripsi'       => esc($this->request->getVar('deskripsi')),
                'biaya'           => esc($this->request->getVar('biaya')),
            ];

            // Lakukan update data
            $this->layananmodel->update($id, $dataUpdate);

            return $this->response->setStatusCode(200)->setJSON([
                'status'  => true,
                'message' => 'Data berhasil diperbarui',
                'data'    => $dataUpdate,
            ]);
        } catch (Exception $e) {
            return $this->response->setStatusCode(500)->setJSON([
                'status'  => false,
                'message' => 'Terjadi kesalahan server: ' . $e->getMessage(),
                'data'    => [],
            ]);
        }
    }

    public function filter($nama, $model)
    {
        try {
            $data = $this->layananmodel->filtercs($nama, $model);
            if (empty($data)) {
                return $this->response->setStatusCode(200)->setJSON([
                    'status'  => true,
                    'message' => 'Data layanan Tidak Tersedia',
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


    public function delete($id)
    {
        $this->layananmodel->delete($id);
        return $this->response->setStatusCode(200)->setJSON([
            'status'  => true,
            'message' => 'Data Berhasil Dihapus',
            'data'    => [],
        ]);
    }

    public function getmobil(){
        try {
            $data = $this->modelmobil->getmobil();
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
    public function getpelanggan(){
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
