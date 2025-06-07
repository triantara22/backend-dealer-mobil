<?php
namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\MobilModel;
use App\Models\SpekModel;
use CodeIgniter\API\ResponseTrait;
use Exception;

class MobilController extends BaseController
{

    protected $modelmobil;
    protected $spekModel;
    use ResponseTrait;
    public function __construct()
    {
        $this->modelmobil = new MobilModel();
        $this->spekModel  = new SpekModel();
    }
    public function index()
    {

        try {
            $data = $this->modelmobil->getmobil();
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
    public function detail($id)
    {
        try {
            $data = $this->modelmobil->getmobilwithid($id);
            if (! empty($data['gambar'])) {
                $data['gambar'] = base_url('uploads/' . $data['gambar']);
            }
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

    public function create()
    {
        // Validasi form
        $rules = [
            'merek'            => 'required',
            'model'            => 'required|string',
            'tahun'            => 'required|numeric|max_length[4]|min_length[4]',
            'harga'            => 'required|decimal',
            'stok'             => 'required|is_natural',
            'gambar'           => 'uploaded[gambar]|mime_in[gambar,image/jpg,image/jpeg,image/png]|max_size[gambar,2048]',
            'tipe_mesin'       => 'required|string',
            'tenaga'           => 'required|string',
            'torsi'            => 'required|string',
            'sistem_penggerak' => 'required|string',
            'fitur_keamanan'   => 'required|string',
            'sistem_hiburan'   => 'required|string',
            'konektivitas'     => 'required|string',
            'ac'               => 'required|string',
        ];

        $errors = [
            'merek'            => [
                'required' => 'Kolom ini harus diisi!',
            ],
            'model'            => [
                'required' => 'Kolom ini harus diisi!',
            ],
            'tahun'            => [
                'required' => 'Kolom ini harus diisi dengan 4 angka!',
            ],
            'harga'            => [
                'required' => 'Kolom ini harus diisi dengan angka decimal!',
            ],
            'stok'             => [
                'required'   => 'Kolom ini harus diisi!',
                'is_natural' => 'Stock harus berupa angka bulat positif atau 0!',
            ],
            'gambar'           => [
                'mime_in'  => 'File harus berupa gambar (JPG, JPEG, PNG)!',
                'max_size' => 'Ukuran file gambar maksimal 2MB!',
            ],
            'tipe_mesin'       => [
                'required' => 'Kolom ini harus diisi!',
            ],
            'tenaga'           => [
                'required' => 'Kolom ini harus diisi!',
            ],
            'torsi'            => [
                'required' => 'Kolom ini harus diisi!',
            ],
            'sistem_penggerak' => [
                'required' => 'Kolom ini harus diisi!',
            ],
            'fitur_keamanan'   => [
                'required' => 'Kolom ini harus diisi!',
            ],
            'sistem_hiburan'   => [
                'required' => 'Kolom ini harus diisi!',
            ],
            'konektivitas'     => [
                'required' => 'Kolom ini harus diisi!',
            ],
            'ac'               => [
                'required' => 'Kolom ini harus diisi!',
            ],
        ];

        if (! $this->validate($rules, $errors)) {
            $response = [
                'message' => $this->validator->getErrors(),
            ];

            return $this->failValidationErrors($response);
        }

        // Simpan data ke tabel mobil
        $mobilData = [
            'merek' => esc($this->request->getVar('merek')),
            'model' => esc($this->request->getVar('model')),
            'tahun' => esc($this->request->getVar('tahun')),
            'harga' => esc($this->request->getVar('harga')),
            'stok'  => esc((int) $this->request->getVar('stok')),
        ];

        // validasi gambar dan menyimpan ke folder uploads serta menghapusnya ketika data di hapus
        $filefoto = $this->request->getFile('gambar');
        if ($filefoto->isValid() && ! $filefoto->hasMoved()) {
            $namafile = $filefoto->getRandomname();
            $filefoto->move('uploads', $namafile);
            $mobilData['gambar'] = $namafile;
        } else {
            unset($mobilData['gambar']);
        }

        try {
            $id_mobil = $this->modelmobil->insert($mobilData, true);

            if (! $id_mobil) {
                throw new \Exception("Gagal insert mobil");
            }

            $spekData = [
                'id_mobil'         => $id_mobil,
                'tipe_mesin'       => esc($this->request->getVar('tipe_mesin')),
                'tenaga'           => esc($this->request->getVar('tenaga')),
                'torsi'            => esc($this->request->getVar('torsi')),
                'sistem_penggerak' => esc($this->request->getVar('sistem_penggerak')),
                'fitur_keamanan'   => esc($this->request->getVar('fitur_keamanan')),
                'sistem_hiburan'   => esc($this->request->getVar('sistem_hiburan')),
                'konektivitas'     => esc($this->request->getVar('konektivitas')),
                'ac'               => esc($this->request->getVar('ac')),
            ];

            $this->spekModel->insert($spekData);

            return $this->respondCreated([
                'status'  => true,
                'message' => 'Data Berhasil Ditambahkan',
                'data'    => [
                    $mobilData,
                    $spekData,
                ],
            ]);
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setJSON([
                'status'  => false,
                'message' => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage(),
            ]);
        }
    }

    public function update($id)
    {
        $datamobil = $this->modelmobil->find($id);
        if (! $datamobil) {
            return $this->failNotFound('Data mobil tidak ditemukan');
        }

        $gambarBaru       = $this->request->getFile('gambar');
        $isGambarUploaded = $gambarBaru && $gambarBaru->isValid() && ! $gambarBaru->hasMoved();

        $rules = [
            'merek'            => 'required',
            'model'            => 'required|string',
            'tahun'            => 'required|numeric|max_length[4]|min_length[4]',
            'harga'            => 'required|decimal',
            'stok'             => 'required|integer|max_length[11]',
            'tipe_mesin'       => 'required|string',
            'tenaga'           => 'required|string',
            'torsi'            => 'required|string',
            'sistem_penggerak' => 'required|string',
            'fitur_keamanan'   => 'required|string',
            'sistem_hiburan'   => 'required|string',
            'konektivitas'     => 'required|string',
            'ac'               => 'required|string',
        ];

        if ($isGambarUploaded) {
            $rules['gambar'] = 'uploaded[gambar]|mime_in[gambar,image/jpg,image/jpeg,image/png]|max_size[gambar,2048]';
        }

        if (! $this->validate($rules)) {
            return $this->failValidationErrors(['message' => $this->validator->getErrors()]);
        }

        if ($isGambarUploaded) {
            $namaGambar = $gambarBaru->getRandomName();
            $gambarBaru->move('uploads', $namaGambar);

            if (! empty($datamobil['gambar']) && file_exists('uploads/' . $datamobil['gambar'])) {
                unlink('uploads/' . $datamobil['gambar']);
            }
        } else {
            $namaGambar = $datamobil['gambar'];
        }

        $mobilData = [
            'merek'  => esc($this->request->getVar('merek')),
            'model'  => esc($this->request->getVar('model')),
            'tahun'  => esc($this->request->getVar('tahun')),
            'harga'  => esc($this->request->getVar('harga')),
            'stok'   => esc((int) $this->request->getVar('stok')),
            'gambar' => $namaGambar,
        ];

        try {
            $updateMobil = $this->modelmobil->update($id, $mobilData);
            if (! $updateMobil) {
                throw new \RuntimeException('Gagal mengupdate data mobil');
            }

            $spekData = [
                'tipe_mesin'       => esc($this->request->getVar('tipe_mesin')),
                'tenaga'           => esc($this->request->getVar('tenaga')),
                'torsi'            => esc($this->request->getVar('torsi')),
                'sistem_penggerak' => esc($this->request->getVar('sistem_penggerak')),
                'fitur_keamanan'   => esc($this->request->getVar('fitur_keamanan')),
                'sistem_hiburan'   => esc($this->request->getVar('sistem_hiburan')),
                'konektivitas'     => esc($this->request->getVar('konektivitas')),
                'ac'               => esc($this->request->getVar('ac')),
            ];

            $this->spekModel->where('id_mobil', $id)->set($spekData)->update();

            return $this->respond([
                'status'  => true,
                'message' => 'Data Berhasil Diupdate',
                'data'    => [$mobilData, $spekData],
            ]);
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setJSON([
                'status'  => false,
                'message' => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage(),
                'data'    => [],
            ]);
        }
    }

    public function delete($id)
    {
        $mobilmodel = new MobilModel();
        $spekmodel  = new SpekModel();

        $mobil = $mobilmodel->find($id);
        if (! $mobil) {
            return $this->response->setStatusCode(404)->setJSON([
                'status'  => false,
                'message' => 'Data Tidak Ditemukan',
                'data'    => [],
            ]);
        }

        // Hapus file gambar jika ada
        if (! empty($mobil['gambar'])) {
            $gambarPath = FCPATH . 'uploads/' . $mobil['gambar'];
            if (file_exists($gambarPath)) {
                unlink($gambarPath);
            } else {
                log_message('debug', 'File tidak ditemukan: ' . $gambarPath);
            }
        }

        // Hapus data spesifikasi terkait
        $spekmodel->where('id_mobil', $id)->delete();

        // Hapus data mobil
        $deleteResult = $mobilmodel->delete($id);

        if (! $deleteResult) {
            log_message('error', 'Gagal delete mobil ID: ' . $id);
            return $this->response->setStatusCode(500)->setJSON([
                'status'  => false,
                'message' => 'Gagal menghapus data mobil',
                'data'    => [],
            ]);
        }

        return $this->response->setStatusCode(200)->setJSON([
            'status'  => true,
            'message' => 'Data Berhasil Dihapus',
            'data'    => [],
        ]);
    }

    public function ambildatafilter()
    {
        $datafilter = $this->modelmobil->getmobil();
        return $this->respond([
            'status'  => true,
            'message' => 'Data Berhasil diambil',
            'data'    => [
                $datafilter,
            ],
        ]);
    }

    public function filter($nama = '', $tahun = '', $harga = '')
    {
        $nama  = urlencode($nama);
        $harga = urlencode($harga);
        $tahun = urlencode($tahun);
        $data  = $this->modelmobil->getmobilwithmereklike($nama, $harga, $tahun);
        if ($data) {
            echo json_encode($data);
        } else {
            $this->response->setStatusCode(404);
            echo json_encode(["message => Data Tidak Ditemukan "]);
        }
    }
}
