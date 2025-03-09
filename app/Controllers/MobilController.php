<?php

namespace App\Controllers;

use App\Controllers\BaseController;
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
        $data = [ 'mobil' => $this->modelmobil->getmobilwithmerek() ];
        if(!$data){
            return $this->response->setJSON([
                'status' => 404,
                'message' => 'Data Tidak Ditemukan',
                'data' => []
            ]);
        }
        return $this->response->setJSON([
            'status' => 200,
            'message' => 'Data Ditemukan',
            'data' => $data
        ]);
    }

    public function create(){
        $modelmobil = new MobilModel();
        $data['mobil'] = $modelmobil->findAll();
        return $this->response->setJSON($data);
    }
    public function Simpan(){
        // 
    }

    public function edit($id){
        //
    }

    public function update($id){
        //
    }

    public function Delete($id){
        //
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