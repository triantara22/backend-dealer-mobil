<?php
namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\KlaimGaransiModel;
use App\Models\GaransiModel;
use CodeIgniter\API\ResponseTrait;
use Exception;

class CsKlaimgaransi extends BaseController
{
    protected $klaimgaransimodel;
    protected $garansimodel;
    use ResponseTrait;
    public function __construct()
    {
        $this->klaimgaransimodel = new KlaimGaransiModel();
        $this->garansimodel      = new GaransiModel();
    }
    public function index()
    {
        try {
            $data = $this->klaimgaransimodel->getklaimgaransi();
            if (empty($data)) {
                return $this->response->setStatusCode(200)->setJSON([
                    'status'  => true,
                    'message' => 'Data Klaim Tidak Tersedia',
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

    public function ajukanklaim()
    {
        $id        = $this->klaimgaransimodel->generateId();
        $dataklaim = [
            'idklaim'         => $id,
            'garansi_id'      => esc($this->request->getVar('garansi_id')),
            'pelanggan_id'    => esc($this->request->getVar('pelanggan_id')),
            'mobil_id'        => esc($this->request->getVar('mobil_id')),
            'klaim_tanggal'   => esc($this->request->getVar('klaim_tanggal')),
            'klaim_deskripsi' => esc($this->request->getVar('klaim_deskripsi')),
        ];

        $this->klaimgaransimodel->insert($dataklaim, true);
        $this->garansimodel->update($this->request->getVar('garansi_id'), [
            'klaim_status' => 'Pending',
        ]);

        return $this->respondCreated([
            'status'  => true,
            'message' => 'Data Berhasil Ditambahkan',
            'data'    => [
                $dataklaim,
            ],
        ]);
    }
}
