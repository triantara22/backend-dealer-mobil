<?php
namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\MerekModel;
use App\Models\MobilModel;
use Exception;

class MobilController extends BaseController
{
    protected $modelmobil;

    public function __construct()
    {
        $this->modelmobil = new MobilModel();

        // $this->response->setContentType('application/json');
    }
    public function index()
    {
        try{
            $data = $this->modelmobil->getmobilwithmerek();
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
        $modelmobil = new MerekModel();
        $data       = $modelmobil->findAll();
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

        // validasi form
        $rules = [
            'merek_id' => 'required',
            'model'    => 'required|string',
            'tahun'    => 'required|numeric|max_length[4]|min_length [4]',
            'harga'    => 'required|decimal',
            'stock'    => 'required|integer|max_length[11]',
            'gambar'   => 'required|uploaded[gambar]|mime_in[gambar,image/jpg,image/jpeg,image/png]|max_size[gambar,2048]',
        ];

        $error = [
            'merek_id' => [
                'required' => 'kolom ini harus di isi!',
            ],
            'model'    => [
                'required' => 'kolom ini harus di isi!',
            ],
            'tahun'    => [
                'required' => 'kolom ini harus di isi dengan 4 angka!',
            ],
            'harga'    => [
                'required' => 'kolom ini harus di isi dengan angka decimal!',
            ],
            'stock'    => [
                'required' => 'kolom ini harus di isi harus di isi dengan decimal!',
            ],
            'gambar'   => [
                'required' => 'kolom ini harus di isi!',
                'mime_in'  => 'File harus berupa gambar (JPG, JPEG, PNG)!',
                'max_size' => 'Ukuran file gambar maksimal 2MB!'
            ],
        ];

        if (!$this->validate($rules, $error)) {
            return $this->response->setStatusCode(400)->setJSON([
                'status'  => 400,
                'Message' => 'Validasi Gagal',
                'error'   => $this->validator->getErrors(),
            ]);
        }

        $data = [
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
            $data['gambar'] = $namafile;
        }
        try{
            $this->modelmobil->save($data);
            return $this->response->setStatusCode(201)->setJSON([
            'status'  => 201,
            'Message' => 'Data Berhasil Ditambahkan',
            'data'    => $data,
        ]);
        }catch(Exception $e){
            return $this->response->setStatusCode(500)->setJSON([
                'Status' => 500,
                'message' => "Terjadi kesalahan Saat Menyimpan data" . $e->getMessage(),
                'data'  => []
            ]);
        }
    }

    public function edit($id)
    {
        $merekmodel = new MerekModel();
        $mobilmodel = new MobilModel();

        $mobil = $mobilmodel->find($id);
        if (! $mobil) {
            return $this->response->setStatusCode(404)->setJSON([
                'status'  => 404,
                'message' => 'Data Tidak Ditemukan',
                'data'    => [],
            ]);
        }
        $merek = $merekmodel->findAll();
        return $this->response->setStatusCode(200)->setJSON([
            'status'  => 200,
            'message' => 'Data Berhasil Ditemukan',
            'data'    => [
                'merek' => $merek,
                'mobil' => $mobil,
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
        $rulles = [
            'merek_id' => 'required',
            'model'    => 'required|string',
            'tahun'    => 'required|numeric|max_length[4]|min_length [4]',
            'harga'    => 'required|decimal',
            'stock'    => 'required|integer|max_length[11]',
            'gambar'   => 'permit_empty|uploaded[gambar]|mime_in[gambar,image/jpg,image/jpeg,image/png]|max_size[gambar,2048]',
        ];

        $errors = [
            'merek_id' => [
                'required' => 'kolom ini harus di isi!',
            ],
            'model'    => [
                'required' => 'kolom ini harus di isi!',
            ],
            'tahun'    => [
                'required' => 'kolom ini harus di isi dengan 4 angka!',
            ],
            'harga'    => [
                'required' => 'kolom ini harus di isi dengan angka decimal!',
            ],
            'stock'    => [
                'required' => 'kolom ini harus di isi harus di isi dengan decimal!',
            ],
            'gambar' => [
                'uploaded' => 'File gambar harus diupload!',
                'mime_in'  => 'File harus berupa gambar (JPG, JPEG, PNG)!',
                'max_size' => 'Ukuran file gambar maksimal 2MB!',
            ],
        ];

        if (! $this->validate($rulles, $errors)) {
            return $this->response->setJSON([
                'Status'  => 400,
                'message' => "Validasi Gagal",
                'errors'  => $this->validator->getErrors(),
            ]);
        }

        $data = [
            'merek_id' => $this->request->getPost('merek_id'),
            'model'    => $this->request->getPost('model'),
            'tahun'    => $this->request->getPost('tahun'),
            'harga'    => $this->request->getPost('harga'),
            'stock'    => $this->request->getPost('stock'),
        ];

        // Handle file gambar
        $filefoto = $this->request->getFile('gambar');
        if ($filefoto->isValid() && ! $filefoto->hasMoved()) {
            $namafile = $filefoto->getRandomname();
            $filefoto->move('uploads', $namafile);
            $data['gambar'] = $namafile;
        }else{
            unset($data['gambar']);
        }

        // mengupdate data
        try{
            $this->modelmobil->update($data);
            return $this->response->setJSON([
                'status'  => 200,
                'message' => "Data Berhasil disimpan",
                'data'    => $data,
            ]);
        }catch(Exception $e){
            return $this->response->setStatusCode(500)->setJSON([
                'Status' => 500,
                'message' => "Terjadi kesalahan Saat mengupdate data" . $e->getMessage(),
                'data'  => []
            ]);
        }
    }

    public function delete($id)
    {
        $mobil = $this->modelmobil->find($id);
        if (! empty($mobil->gambar)) {
            $file = 'uploads/' . $mobil->gambar;
        }
        if (is_file($file)) {
            unlink($file);
        }
        $this->modelmobil->delete($id);
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
