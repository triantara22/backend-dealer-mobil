<?php
namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PembayaranModel;
use App\Models\PenjualanModel;
use CodeIgniter\API\ResponseTrait;
use Exception;

class PenjualanController extends BaseController
{
    protected $penjualan;
    protected $pembayaran;
    use ResponseTrait;
    public function __construct()
    {
        $this->penjualan  = new PenjualanModel();
        $this->pembayaran = new PembayaranModel();
    }
    public function index()
    {
        try {
            $data = $this->penjualan->getpenjualan();
            if (empty($data)) {
                return $this->response->setStatusCode(200)->setJSON([
                    'status'  => 204,
                    'message' => 'Data Mobil Tidak Tersedia',
                    'data'    => [],
                ]);
            }
            return $this->response->setStatusCode(200)->setJSON([
                'status'  => 200,
                'message' => 'Data Ditemukan',
                'data'    => $data,
            ]);
        } catch (Exception $e) {
            return $this->response->setStatusCode(500)->setJSON([
                'Status'  => 500,
                'message' => "Internal server eror" . $e->getMessage(),
                'data'    => [],
            ]);
        }
    }

    public function update($id)
    {
        $rules = [
            'pelanggan_id'      => 'required',
            'mobil_id'          => 'required',
            'user_id'           => 'required',
            'total_harga'       => 'required',
            'jumlah_bayar'      => 'required',
            'metode_pembayaran' => 'required|in_list[cash,transfer]',
        ];

        $errors = [
            'jumlah_bayar'      => ['required' => 'Jumlah Bayar Harus Diisi'],
            'metode_pembayaran' => [
                'required' => 'Metode Pembayaran Harus Diisi',
                'in_list'  => 'Metode Pembayaran harus cash atau transfer',
            ],
        ];

        if (! $this->validate($rules, $errors)) {
            return $this->failValidationErrors([
                'message' => $this->validator->getErrors(),
            ]);
        }

        $datapenjualan = [
            'pelanggan_id'      => $this->request->getVar('pelanggan_id'),
            'mobil_id'          => $this->request->getVar('mobil_id'),
            'user_id'           => $this->request->getVar('user_id'),
            'tanggal_transaksi' => $this->request->getVar('tanggal_transaksi'),
            'total_harga'       => $this->request->getVar('total_harga'),
            'status_pembayaran' => $this->request->getVar('status_pembayaran'),
        ];

        try {
            if (! $this->penjualan->update($id, $datapenjualan)) {
                throw new Exception("Gagal mengupdate data penjualan.");
            }

            // Cari ID pembayaran berdasarkan penjualan_id
            $pembayaran     = $this->pembayaran->where('penjualan_id', $id)->first();
            $datapembayaran = [
                'jumlah_bayar'      => $this->request->getVar('jumlah_bayar'),
                'metode_pembayaran' => $this->request->getVar('metode_pembayaran'),
            ];

            if ($pembayaran) {
                $this->pembayaran->update($pembayaran['id'], $datapembayaran);
            }

            return $this->respond([
                'status'  => 200,
                'message' => 'Data Berhasil Diupdate',
                'data'    => [
                    $datapenjualan,
                    $datapembayaran,
                ],
            ]);

        } catch (Exception $e) {
            return $this->response->setStatusCode(500)->setJSON([
                'status'  => 500,
                'message' => "Internal server error: " . $e->getMessage(),
                'data'    => [],
            ]);
        }
    }

    public function filter()
    {
        $tanggaltr = $this->request->getGet('tanggaltr');
        $namamobil = $this->request->getGet('namamobil');
        $status    = $this->request->getGet('status');

        $data = $this->penjualan->filter($tanggaltr, $status, $namamobil);

        if ($data) {
            return $this->response->setJSON($data);
        } else {
            return $this->response->setStatusCode(404)
                ->setJSON(["message" => "Data Tidak Ditemukan"]);
        }
    }

    public function filterpembayaran()
    {
        $id = $this->request->getGet('id');

        $data = $this->pembayaran->filterpembayaran($id);

        if ($data) {
            return $this->response->setJSON($data);
        } else {
            return $this->response->setStatusCode(404)
                ->setJSON(["message" => "Data Tidak Ditemukan"]);
        }
    }
}
