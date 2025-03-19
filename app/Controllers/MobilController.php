<?php
namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\MerekModel;
use App\Models\MobilModel;
use App\Models\SpekMobil;
use Exception;

class MobilController extends BaseController
{
    protected $modelmobil;
    protected $spekModel;

    public function __construct()
    {
        $this->modelmobil = new MobilModel();
        $this->spekModel = new SpekMobil();
        // $this->response->setContentType('application/json');
    }
    public function index()
    {
        try{
            $data = $this->modelmobil->getmobilwithmerek();
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
        }catch(Exception $e){
                return $this->response->setStatusCode(500)->setJSON([
                    'Status' => 500,
                    'message' => "Internal server eror". $e->getMessage(), 
                    'data'  => [],
                ]);
        }
    }
    public function detail($id)
    {
        try{
            $data = $this->modelmobil->getmobilwithid($id);
            if (empty($data)) {
                return $this->response->setStatusCode(200)->setJSON([
                    'status'  => 200,
                    'message' => 'Data Mobil Tidak Tersedia',
                    'data'    => [],
                ]);
            }
            return $this->response->setStatusCode(200)->setJSON([
                'status'  => 200,
                'message' => 'Data Ditemukan',
                'data'    => $data,
            ]);
        }catch(Exception $e){
                return $this->response->setStatusCode(500)->setJSON([
                    'Status' => 500,
                    'message' => "Internal server eror". $e->getMessage(), 
                    'data'  => [],
                ]);
        }
    }

    public function create()
    {
        $modelmobil = new MobilModel();
        $data       = $modelmobil->getmobilwithall();
        if (empty($data)) {
            return $this->response->setStatusCode(200)->setJSON([
                'status'  => '200',
                'message' => 'data tidak mobil ditemukan',
                'data'    => [],
            ]);
        }
            return $this->response->setStatusCode(200)->setJSON([
                'status'  => '200',
                'message' => 'data berhasil ditemukan',
                'data'    => $data,
            ]);
    }
    public function simpan()
    {
         // Validasi form
        $rules = [
            'merek_id' => 'required',
            'model'    => 'required|string',
            'tahun'    => 'required|numeric|max_length[4]|min_length[4]',
            'harga'    => 'required|decimal',
            'stock'    => 'required|integer|max_length[11]',
            'gambar'   => 'required|uploaded[gambar]|mime_in[gambar,image/jpg,image/jpeg,image/png]|max_size[gambar,2048]',
            'tipe_mesin' => 'required|string',
            'tenaga' => 'required|string',
            'torsi' => 'required|string',
            'sistem_penggerak' => 'required|string',
            'fitur_keamanan' => 'required|string',
            'sistem_hiburan'=> 'required|string',
            'konektivitas' => 'required|string',
            'ac'  => 'required|string',
        ];

        $errors = [
            'merek_id' => [
                'required' => 'Kolom ini harus diisi!',
            ],
            'model' => [
                'required' => 'Kolom ini harus diisi!',
            ],
            'tahun' => [
                'required' => 'Kolom ini harus diisi dengan 4 angka!',
            ],
            'harga' => [
                'required' => 'Kolom ini harus diisi dengan angka decimal!',
            ],
            'stock' => [
                'required' => 'Kolom ini harus diisi dengan angka!',
            ],
            'gambar' => [
                'required' => 'Kolom ini harus diisi!',
                'mime_in'  => 'File harus berupa gambar (JPG, JPEG, PNG)!',
                'max_size' => 'Ukuran file gambar maksimal 2MB!'
            ],
            'tipe_mesin' => [
                    'required' => 'Kolom ini harus diisi!',
            ],
            'tenaga' => [
                    'required' => 'Kolom ini harus diisi!',
            ],
            'torsi' => [
                    'required' => 'Kolom ini harus diisi!',
            ],
            'sistem_penggerak' => [
                    'required' => 'Kolom ini harus diisi!',
            ],
            'fitur_keamanan' => [
                    'required' => 'Kolom ini harus diisi!',
            ],
            'sistem_hiburan'=> [
                    'required' => 'Kolom ini harus diisi!',
            ],
            'konektivitas' => [
                    'required' => 'Kolom ini harus diisi!',
            ],
            'ac'  => [
                    'required' => 'Kolom ini harus diisi!',
            ],
        ];

        if (!$this->validate($rules, $errors)) {
            return $this->response->setStatusCode(400)->setJSON([
                'status'  => 400,
                'message' => 'Validasi Gagal',
                'errors'  => $this->validator->getErrors(),
            ]);
        }

        // Simpan data ke tabel mobil
        $mobilData = [
            'merek_id' => $this->request->getPost('merek_id'),
            'model'    => $this->request->getPost('model'),
            'tahun'    => $this->request->getPost('tahun'),
            'harga'    => $this->request->getPost('harga'),
            'stock'    => $this->request->getPost('stock'),
        ];

        $filefoto = $this->request->getFile('gambar');
        if ($filefoto->isValid() && ! $filefoto->hasMoved()) {
            $namafile = $filefoto->getRandomname();
            $filefoto->move('uploads', $namafile);
            $mobildata['gambar'] = $namafile;
        }else{
            unset($mobildata['gambar']);
        }


        try {
            $mobilId = $this->modelmobil->insert($mobilData);

            $spekData = [
                'mobil_id' => $mobilId,
                'tipe_mesin' => $this->request->getPost('tipe_mesin'),
                'tenaga' => $this->request->getPost('tenaga'),
                'torsi' => $this->request->getPost('torsi'),
                'sistem_penggerak' => $this->request->getPost('sistem_penggerak'),
                'fitur_keamanan' => $this->request->getPost('fitur_keamanan'),
                'sistem_hiburan' => $this->request->getPost('sistem_hiburan'),
                'konektivitas' => $this->request->getPost('konektivitas'),
                'ac' => $this->request->getPost('ac'),
            ];

            $this->spekModel->insert($spekData);

            return $this->response->setStatusCode(201)->setJSON([
                'status'  => 201,
                'message' => 'Data Berhasil Ditambahkan',
                'data'    => $mobilData,
            ]);
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setJSON([
                'status'  => 500,
                'message' => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage(),
                'data'    => [],
            ]);
        }
    }

    public function edit($id)
    {
        $merekmodel = new MerekModel();
        $mobilmodel = new MobilModel();
        $spekmodel = new SpekMobil();
    
        // Ambil data mobil berdasarkan ID
        $mobil = $mobilmodel->find($id);
        if (!$mobil) {
            return $this->response->setStatusCode(404)->setJSON([
                'status'  => 404,
                'message' => 'Data Mobil Tidak Ditemukan',
                'data'    => [],
            ]);
        }
    
        // Ambil data merek (untuk dropdown atau pilihan merek)
        $merek = $merekmodel->findAll();
        // Ambil data spesifikasi berdasarkan mobil_id
        $spesifikasi = $spekmodel->where('id_mobil', $id)->first();
        if (!$spesifikasi) {
            return $this->response->setStatusCode(404)->setJSON([
                'status'  => 404,
                'message' => 'Data Spesifikasi Tidak Ditemukan',
                'data'    => [],
            ]);
        }
    
        // Gabungkan semua data dan kirim sebagai respons JSON
        return $this->response->setStatusCode(200)->setJSON([
            'status'  => 200,
            'message' => 'Data Berhasil Ditemukan',
            'data'    => [
                'merek' => $merek,
                'mobil' => $mobil,
                'spesifikasi' => $spesifikasi,
            ],
        ]);
    }

    public function update($id)
    {
        $data = $this->modelmobil->find($id);
        // cek data
        if (!$data) {
            return $this->response->setJSON([
                'status'  => 400,
                'message' => 'Data Tidak Ditemukan',
                'data'    => [],
            ]);
        }

        // Validasi Form
        $rules = [
            'merek_id' => 'required',
            'model'    => 'required|string',
            'tahun'    => 'required|numeric|max_length[4]|min_length [4]',
            'harga'    => 'required|decimal',
            'stock'    => 'required|integer|max_length[11]',
            'gambar'   => 'permit_empty|uploaded[gambar]|mime_in[gambar,image/jpg,image/jpeg,image/png]|max_size[gambar,2048]',
            'tipe_mesin' => 'required|string',
            'tenaga' => 'required|string',
            'torsi' => 'required|string',
            'sistem_penggerak' => 'required|string',
            'fitur_keamanan' => 'required|string',
            'sistem_hiburan'=> 'required|string',
            'konektivitas' => 'required|string',
            'ac'  => 'required|string',
        ];

        $errors = [
            'merek_id' => [
                'required' => 'Kolom ini harus diisi!',
            ],
            'model' => [
                'required' => 'Kolom ini harus diisi!',
            ],
            'tahun' => [
                'required' => 'Kolom ini harus diisi dengan 4 angka!',
            ],
            'harga' => [
                'required' => 'Kolom ini harus diisi dengan angka decimal!',
            ],
            'stock' => [
                'required' => 'Kolom ini harus diisi dengan angka!',
            ],
            'gambar' => [
                'required' => 'Kolom ini harus diisi!',
                'mime_in'  => 'File harus berupa gambar (JPG, JPEG, PNG)!',
                'max_size' => 'Ukuran file gambar maksimal 2MB!'
            ],
            'tipe_mesin' => [
                    'required' => 'Kolom ini harus diisi!',
            ],
            'tenaga' => [
                    'required' => 'Kolom ini harus diisi!',
            ],
            'torsi' => [
                    'required' => 'Kolom ini harus diisi!',
            ],
            'sistem_penggerak' => [
                    'required' => 'Kolom ini harus diisi!',
            ],
            'fitur_keamanan' => [
                    'required' => 'Kolom ini harus diisi!',
            ],
            'sistem_hiburan'=> [
                    'required' => 'Kolom ini harus diisi!',
            ],
            'konektivitas' => [
                    'required' => 'Kolom ini harus diisi!',
            ],
            'ac'  => [
                    'required' => 'Kolom ini harus diisi!',
            ],
        ];

        if (!$this->validate($rules, $errors)) {
            return $this->response->setStatusCode(400)->setJSON([
                'status'  => 400,
                'message' => 'Validasi Gagal',
                'errors'  => $this->validator->getErrors(),
            ]);
        }

        // Simpan data ke tabel mobil
        $mobilData = [
            'merek_id' => $this->request->getPost('merek_id'),
            'model'    => $this->request->getPost('model'),
            'tahun'    => $this->request->getPost('tahun'),
            'harga'    => $this->request->getPost('harga'),
            'stock'    => $this->request->getPost('stock'),
        ];


        $filefoto = $this->request->getFile('gambar');
        if ($filefoto->isValid() && ! $filefoto->hasMoved()) {
            $namafile = $filefoto->getRandomname();
            $filefoto->move('uploads', $namafile);
            $mobildata['gambar'] = $namafile;
        }else{
            unset($mobildata['gambar']);
        }


        try {
            $mobilId = $this->modelmobil->update($mobilData);

            $spekData = [
                'mobil_id' => $mobilId,
                'tipe_mesin' => $this->request->getPost('tipe_mesin'),
                'tenaga' => $this->request->getPost('tenaga'),
                'torsi' => $this->request->getPost('torsi'),
                'sistem_penggerak' => $this->request->getPost('sistem_penggerak'),
                'fitur_keamanan' => $this->request->getPost('fitur_keamanan'),
                'sistem_hiburan' => $this->request->getPost('sistem_hiburan'),
                'konektivitas' => $this->request->getPost('konektivitas'),
                'ac' => $this->request->getPost('ac'),
            ];

            $this->spekModel->update($spekData);

            return $this->response->setStatusCode(201)->setJSON([
                'status'  => 201,
                'message' => 'Data Berhasil Ditambahkan',
                'data'    => $mobilData,
            ]);
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setJSON([
                'status'  => 500,
                'message' => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage(),
                'data'    => [],
            ]);
        }
    }
    public function delete($id)
    {
        $mobilmodel = new MobilModel();
        $spekmodel = new SpekMobil();

        $mobil = $this->modelmobil->find($id);
        if(!$mobil){
            return $this->response->setStatusCode(404)->setJSON([
                'status'  => 404,
                'message' => 'Data Tidak Ditemukan',
                'data'    => [],
            ]);
        }

        if (! empty($mobil->gambar)) {
            $file = 'uploads/' . $mobil->gambar;
        }
        if (is_file($file)) {
            unlink($file);
        }

        $spekmodel->where('id_mobil', $id)->delete();

        $mobilmodel->delete($id);
        return $this->response->setStatusCode(200)->setJSON([
            'Status'  => 200,
            'message' => "Data Berhasil Di hapus",
            'Data'    => [],
        ]);
    }

    public function filter($nama = '')
    {
        $nama = urlencode($nama);
        $data = $this->modelmobil->getmobilwithmereklike($nama);
        if ($data) {
            echo json_encode($data);
        } else {
            $this->response->setStatusCode(404);
            echo json_encode(["message => Data Tidak Ditemukan "]);
        }
    }
}
