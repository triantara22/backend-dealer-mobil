<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateGaransiTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'                => [
                'type' => 'varchar', 
                'constraint' => 20, 
            ],
            'mobil_id'            => [
                'type' => 'INT', 
                'constraint' => 11, 
                'unsigned' => true
            ],
            'pelanggan_id'       => [
                'type' => 'INT', 
                'constraint' => 11, 
                'unsigned' => true
            ],
            'tanggal_mulai'     => [
                'type' => 'DATE'
            ],
            'tanggal_berakhir'  =>
            [
                'type' => 'DATE'
            ],
            'detail_garansi'    => [
                'type' => 'TEXT'
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('mobil_id', 'mobil', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('pelanggan_id', 'pelanggan', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('garansi');
    }

    public function down()
    {
        $this->forge->dropTable('garansi');
    }
}
