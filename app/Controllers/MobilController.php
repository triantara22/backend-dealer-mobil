<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\MerekModel;
use App\Models\MobilModel;
use CodeIgniter\HTTP\ResponseInterface;

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
        
        $data = $this->modelmobil->getmobilwithmerek();
        if(empty($data)){
            return $this->response->setStatusCode(200)->setJSON([
                'status' => 200,
                'message' => 'Data Mobil Tidak Tersedia',
                'data' => []
            ]);
        }if($data){
            return $this->response->setStatusCode(200)->setJSON([
                'status' => 200,
                'message' => 'Data Ditemukan',
                'data' => $data
            ]);
        }
    }

    public function create(){
        $modelmobil = new MerekModel();
        $data = $this->$modelmobil->findAll();
        if(empty($data)){
            return $this->response->setStatusCode(200)->setJSON([
                'status' => '200',
                'message' => 'data tidak mobil ditemukan',
                'data' => []
            ]);
        }
        if($data){
            return $this->response->setStatusCode(200)->setJSON([
                'status' => '200',
                'message' =>'data berhasil ditemukan',
                'data'=>$data
            ]);
        }
    }
    public function Simpan(){

        $rules = [
            'merek_id'=> 'required',
            'model'=> 'required|string',
            'tahun'=> 'required|numeric|max_length[4]|min_length [4]',
            'harga'=> 'required|decimal',
            'stock'=> 'required|integer|max_length[11]',
            'gambar'=> 'required'
        ];

        $error = [
            'merek_id' => [
                'required' => 'kolom ini harus di isi!'
            ],
            'model' => [
                'required' => 'kolom ini harus di isi!'
            ],
            'tahun' => [
                'required' => 'kolom ini harus di isi dengan 4 angka!'
            ],
            'harga' => [
                'required' => 'kolom ini harus di isi dengan angka decimal!'
            ],
            'stock' => [
                'required' => 'kolom ini harus di isi harus di isi dengan decimal!'
            ],
            'gambar' => [
                'required' => 'kolom ini harus di isi!'
            ],
        ];

        if(!$this->validate($rules,$error)){
            return $this->response->setStatusCode(400)->setJSON([
                'status' => 400,
                'Message' => 'Validasi Gagal',
                'error' => $this->validator->getErrors()
            ]);
        };

        $data=[
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
        $this->modelmobil->save($data);

        return $this->response->setStatusCode(201)->setJSON([
            'status' => 201,
            'Message' => 'Data Berhasil Ditambahkan',
            'data' => $data
        ]);
    }

    public function edit($id){
      $merekmodel = new MerekModel();
      $mobilmodel = new MobilModel();

      $mobil = $mobilmodel->find($id);
        if(!$mobil){
            return $this->response->setStatusCode(404)->setJSON([
                'status' =>404,
                'message' => 'Data Tidak Ditemukan',
                'data' =>[]
            ]);

        $merek=$merekmodel->findAll();
        return $this->response->setStatusCode(200)->setJSON([
             'status' => 200,
             'message' => 'Data Berhasil Ditemukan',
             'Data' => [
                'mobil' => $mobil,
                'merek' => $merek
             ]
        ]);
        }
    }

    public function update($id){
        $data= $this->modelmobil->find($id);
        if(!$data){
            return $this->response->setJSON([
                'status' => 400,
                'message' => 'Data Tidak Ditemukan',
                'data' =>[]
            ]);
        }

        $rulles = [
            'merek_id'=> 'permit_empty',
            'model'=> 'permit_empty|',
            'tahun'=> 'permit_empty|numeric|max_length[4]|min_length [4]',
            'harga'=> 'permit_empty|decimal',
            'stock'=> 'permit_empty|integer|max_length[11]',
            'gambar'=> 'permit_empty'
        ];

        $errors = [
            'merek_id' => [
                'required' => 'kolom ini harus di isi!'
            ],
            'model' => [
                'required' => 'kolom ini harus di isi!'
            ],
            'tahun' => [
                'required' => 'kolom ini harus di isi dengan 4 angka!'
            ],
            'harga' => [
                'required' => 'kolom ini harus di isi dengan angka decimal!'
            ],
            'stock' => [
                'required' => 'kolom ini harus di isi harus di isi dengan decimal!'
            ],
        ];

        if(!$this->validate($rulles,$errors)){
            return $this->response->setJSON([
                'Status' => 400,
                'message' => "Validasi Gagal",
                'errors' =>  $this->validator->getErrors()
            ]);
        }

        $data=[
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
        
        $this->modelmobil->update($data);
        return $this->response->setJSON([
            'status'=>200,
            'message'=> "Data Berhasil disimpan",
            'data' => $data
        ]);
    }

    public function Delete($id){
        $mobil = $this->modelmobil->find($id);
        if(!empty($mobil->gambar)){
            $file='uploads/'. $mobil->gambar;
        }
        if(is_file($file)){
            unlink($file);
        }
        $this->modelmobil->delete($id);
        return $this->response->setStatusCode(200)->setJSON([
            'Status' => 200,
            'message' => "Data Berhasil Di hapus",
            'Data' => []
        ]);
    }

    public function filter($nama = '')
    {
        $nama=urlencode($nama);
        $data = $this->modelmobil->getmobilwithmerekwhere($nama);
        if($data){
            echo json_encode($data);
        }else
        {
        $this->response->setStatusCode(404);
            echo json_encode(["message => Data Tidak Ditemukan "]);
        }
    }
}