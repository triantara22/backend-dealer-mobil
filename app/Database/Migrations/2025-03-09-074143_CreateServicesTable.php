<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateServicesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'              => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'pelanggan_id'     => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'mobil_id'          => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'tanggal_layanan' => ['type' => 'DATETIME'],
            'deskripsi'       => ['type' => 'TEXT'],
            'biaya'           => ['type' => 'DECIMAL', 'constraint' => '15,2'],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('pelanggan_id', 'pelanggan', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('mobil_id', 'mobil', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('services');
    }

    public function down()
    {
        $this->forge->dropTable('services');
    }
}
