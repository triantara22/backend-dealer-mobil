<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use Exception;
use CodeIgniter\API\ResponseTrait;
use App\Models\GaransiModel;

class Garansi extends BaseController
{
    protected $garansimodel;
    use ResponseTrait;
    public function __construct()
    {
        $this->garansimodel = new GaransiModel();
    }

    public function index()
    {
        try {
            $data = $this->garansimodel->getdatagaransi();
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
        $id          = $this->garansimodel->generateId();
        $datagaransi = [
            'id'              => $id,
            'pelanggan_id'    => esc($this->request->getVar('pelanggan_id')),
            'mobil_id'        => esc($this->request->getVar('mobil_id')),
            'tanggal_mulai' => esc($this->request->getVar('tanggal_mulai')),
            'tanggal_berakhir' => esc($this->request->getVar('tanggal_berakhir')),
            'detail_garansi'       => esc($this->request->getVar('detail_garansi')),
            'klaim_status'           => esc($this->request->getVar('klaim_status')),
        ];
        
        $this->garansimodel->insert($datagaransi, true);
        return $this->respondCreated([
            'status'  => true,
            'message' => 'Data Berhasil Ditambahkan',
            'data'    => [
                $datagaransi,
            ],
        ]);
    }

    public function update($id)
    {

        try
        {
            $datagaransi = [
            'id'              => $id,
            'pelanggan_id'    => esc($this->request->getVar('pelanggan_id')),
            'mobil_id'        => esc($this->request->getVar('mobil_id')),
            'tanggal_mulai' => esc($this->request->getVar('tanggal_mulai')),
            'tanggal_berakhir' => esc($this->request->getVar('tanggal_berakhir')),
            'detail_garansi'       => esc($this->request->getVar('detail_garansi')),
            'klaim_status'           => esc($this->request->getVar('klaim_status')),
            ];

            // Lakukan update data
            $this->garansimodel->update($id, $datagaransi);

            return $this->response->setStatusCode(200)->setJSON([
                'status'  => true,
                'message' => 'Data berhasil diperbarui',
                'data'    => $datagaransi,
            ]);
        } catch (Exception $e) {
            return $this->response->setStatusCode(500)->setJSON([
                'status'  => false,
                'message' => 'Terjadi kesalahan server: ' . $e->getMessage(),
                'data'    => [],
            ]);
        }

    }


    public function filter($nama, $klaim_status)
    {
        try {
            $data = $this->garansimodel->filter($nama, $klaim_status);
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

    public function klaimgaransi()
    {
        try {
            $data = $this->garansimodel->getklaimgaransi();
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



    public function delete($id)
    {
        $this->garansimodel->delete($id);
        return $this->respondDeleted([
            'status'  => true,
            'message' => 'Data Berhasil Dihapus',
            'data'    => [],
        ]);
    }


}
