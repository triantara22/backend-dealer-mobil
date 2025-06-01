<?php
namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\GaransiModel;
use App\Models\KlaimGaransiModel;
use CodeIgniter\API\ResponseTrait;
use Exception;

class Garansi extends BaseController
{
    protected $garansimodel;
    protected $klaimgaransimodel;
    use ResponseTrait;
    public function __construct()
    {
        $this->garansimodel      = new GaransiModel();
        $this->klaimgaransimodel = new KlaimGaransiModel();
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
        $id          = $this->garansimodel->generateIdg();
        $datagaransi = [
            'idg'              => $id,
            'pelanggan_id'     => esc($this->request->getVar('pelanggan_id')),
            'mobil_id'         => esc($this->request->getVar('mobil_id')),
            'tanggal_mulai'    => esc($this->request->getVar('tanggal_mulai')),
            'tanggal_berakhir' => esc($this->request->getVar('tanggal_berakhir')),
            'detail_garansi'   => esc($this->request->getVar('detail_garansi')),
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
                'idg'              => $id,
                'pelanggan_id'     => esc($this->request->getVar('pelanggan_id')),
                'mobil_id'         => esc($this->request->getVar('mobil_id')),
                'tanggal_mulai'    => esc($this->request->getVar('tanggal_mulai')),
                'tanggal_berakhir' => esc($this->request->getVar('tanggal_berakhir')),
                'detail_garansi'   => esc($this->request->getVar('detail_garansi')),
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

    public function filterklaim($nama, $klaim_status)
    {
        try {
            $data = $this->klaimgaransimodel->filter($nama, $klaim_status);
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
            $data = $this->klaimgaransimodel->getklaimgaransi();
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

    public function updateklaim($idklaim)
    {
        $input = $this->request->getJSON(true);
        var_dump($input);
        if (! isset($input['klaim_status'])) {
            return $this->failValidationErrors('Status klaim wajib diisi');
        }

        // Cari data klaim berdasarkan ID klaim
        $klaim = $this->klaimgaransimodel->find($idklaim);

        if (! $klaim) {
            return $this->failNotFound("Klaim dengan ID $idklaim tidak ditemukan.");
        }

        // Ambil ID garansi dari klaim
        $idg = $klaim['garansi_id'];

        // Update klaim_status di tabel garansi
        $updated = $this->garansimodel->update($idg, [
            'klaim_status' => $input['klaim_status'],
        ]);

        if ($updated) {
            return $this->respond([
                'status'  => 200,
                'message' => 'Status klaim berhasil diperbarui.',
            ]);
        } else {
            return $this->failServerError('Gagal memperbarui status klaim');
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
