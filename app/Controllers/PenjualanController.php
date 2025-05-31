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

    public function pembayaran()
    {
        try {
            $data = $this->pembayaran->getpembayaran();
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

    public function Create()
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

        $status_pembayaran = ($jumlah_bayar >= $total_harga) ? 'paid' : 'pending';

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

    public function update($id)
    {
        $rules = [
            'status_pembayaran' => 'permit_empty',
            'jumlah_bayar'      => 'permit_empty|numeric',
            'metode_pembayaran' => 'permit_empty|in_list[cash,transfer]',
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
            'pelanggan_id'      => esc($this->request->getVar('pelanggan_id')),
            'mobil_id'          => esc($this->request->getVar('mobil_id')),
            'user_id'           => esc($this->request->getVar('user_id')),
            'tanggal_transaksi' => esc($this->request->getVar('tanggal_transaksi')),
            'total_harga'       => esc($this->request->getVar('total_harga')),
            'status_pembayaran' => esc($this->request->getVar('status_pembayaran')),
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
                'status'  => true,
                'message' => 'Data Berhasil Diupdate',
                'data'    => [
                    $datapenjualan,
                    $datapembayaran,
                ],
            ]);

        } catch (Exception $e) {
            return $this->response->setStatusCode(500)->setJSON([
                'status'  => false,
                'message' => "Internal server error: " . $e->getMessage(),
                'data'    => [],
            ]);
        }
    }

    public function filter()
    {
        $tanggaltr = esc($this->request->getGet('tanggaltr'));
        $namamobil = esc($this->request->getGet('namamobil'));
        $status    = esc($this->request->getGet('status'));

        $data = $this->penjualan->filter($tanggaltr, $status, $namamobil);

        if ($data) {
            return $this->response->setJSON($data);
        } else {
            return $this->response->setStatusCode(404)
                ->setJSON(["message" => "Data Tidak Ditemukan"]);
        }
    }

    public function filterpembayaran($penjualan_id)
    {
        $data = $this->pembayaran->filterpembayaran($penjualan_id);

        if ($data) {
            return $this->respond([
                'status'  => true,
                'message' => 'Data Berhasil Ditemukan',
                'data'    => [
                    $data,
                ],
            ]);
        } else {
            return $this->response->setStatusCode(404)
                ->setJSON(["message" => "Data Tidak Ditemukan"]);
        }
    }


        public function ambildatafilter()
    {
        $datafilter = $this->penjualan->getpenjualan();
        return $this->respond([
            'status'  => true,
            'message' => 'Data Berhasil diambil',
            'data'    => [
                $datafilter,
            ],
        ]);
    }
}
