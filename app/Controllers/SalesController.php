<?php
namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\MobilModel;
use App\Models\PembayaranModel;
use App\Models\PenjualanModel;
use CodeIgniter\API\ResponseTrait;
use Exception;

class SalesController extends BaseController
{
    protected $penjualan;
    protected $pembayaran;
    protected $daftarmobil;
    use ResponseTrait;
    public function __construct()
    {
        $this->penjualan   = new PenjualanModel();
        $this->pembayaran  = new PembayaranModel();
        $this->daftarmobil = new MobilModel();
    }
    public function daftarmobil()
    {
        try {
            $data = $this->daftarmobil->getmobil();
            if (empty($data)) {
                return $this->response->setStatusCode(200)->setJSON([
                    'status'  => true,
                    'message' => 'Data transaksi Tidak Tersedia',
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
    public function daftarmobilid($id)
    {
        try {
            $data = $this->daftarmobil->getmobilwithid($id);
            if (empty($data)) {
                return $this->response->setStatusCode(200)->setJSON([
                    'status'  => true,
                    'message' => 'Data transaksi Tidak Tersedia',
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
    public function index()
    {
        try {
            $data = $this->penjualan->getpenjualan();
            if (empty($data)) {
                return $this->response->setStatusCode(200)->setJSON([
                    'status'  => true,
                    'message' => 'Data transaksi Tidak Tersedia',
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

    public function Create()
    {
        $rules = [
            'pelanggan_id'      => 'required',
            'mobil_id'          => 'required',
            'user_id'           => 'required',
            'total_harga'       => 'required',
            'jumlah_bayar'      => 'required',
            'metode_pembayaran' => 'required|in_list[Cash,Transfer]',
        ];

        $errors = [
            'jumlah_bayar'      => [
                'required' => 'Jumlah Bayar Harus Diisi',
            ],
            'metode_pembayaran' => [
                'required' => 'Metode Pembayaran Harus Diisi',
                'in_list'  => 'Metode Pembayaran harus cash atau transfer',
            ],
        ];

        if (! $this->validate($rules, $errors)) {
            $response = [
                'message' => $this->validator->getErrors(),
            ];

            return $this->failValidationErrors($response);
        }
        $id = $this->penjualan->generateTransactionId();

        $total_harga  = esc($this->request->getVar('total_harga'));
        $jumlah_bayar = esc($this->request->getVar('jumlah_bayar'));

        $status_pembayaran = ($jumlah_bayar >= $total_harga) ? 'selesai' : 'proses';

        $datapenjualan = [
            'id'                => $id,
            'pelanggan_id'      => esc($this->request->getVar('pelanggan_id')),
            'mobil_id'          => esc($this->request->getVar('mobil_id')),
            'user_id'           => esc($this->request->getVar('user_id')),
            'tanggal_transaksi' => esc($this->request->getVar('tanggal_transaksi')),
            'total_harga'       => $total_harga,
            'status_pembayaran' => $status_pembayaran,
        ];

        try {
            $penjualan_id = $this->penjualan->insert($datapenjualan, true);

            // Ambil ID mobil dari request
            $mobil_id = esc($this->request->getVar('mobil_id'));

            // Ambil data mobil untuk cek stok
            $mobil = $this->daftarmobil->find($mobil_id);

            if (! $mobil || $mobil['stok'] <= 0) {
                throw new Exception("Stok mobil tidak mencukupi");
            }

            // Kurangi stok mobil
            $this->daftarmobil->update($mobil_id, [
                'stok' => $mobil['stok'] - 1,
            ]);

            if (! $penjualan_id) {
                throw new Exception(" Gagal Menambahkan Data Penjualan ");
            }

            $datapembayaran = [
                'id'                => $id,
                'penjualan_id'      => $penjualan_id,
                'jumlah_bayar'      => $this->request->getVar('jumlah_bayar'),
                'metode_pembayaran' => $this->request->getVar('metode_pembayaran'),
            ];
            $this->pembayaran->insert($datapembayaran);
            return $this->respondCreated([
                'status'  => true,
                'message' => 'Data Berhasil Ditambahkan',
                'data'    => [
                    $datapenjualan,
                    $datapembayaran,
                ],
            ]);

        } catch (Exception $e) {
            return $this->response->setStatusCode(500)->setJSON([
                'Status'  => false,
                'message' => "Internal server eror" . $e->getMessage(),
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
                ->setJSON([
                    "status"  => false,
                    "message" => "Data Tidak Ditemukan",
                ]);
        }
    }

}
