<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMobilTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'       => [
                'type' => 'INT', 
                'constraint' => 11, 
                'unsigned' => true, 
                'auto_increment' => true
            ],
            'merek_id' => [
                'type' => 'INT', 
                'constraint' => 11, 
                'unsigned' => true
            ],
            'model'    => [
                'type' => 'VARCHAR', 
                'constraint' => 100
            ],
            'tahun'    => [
                'type' => 'YEAR'
            ],
            'harga'    => [
                'type' => 'DECIMAL', 
                'constraint' => '15,2'
            ],
            'stok'     => [
                'type' => 'INT',
                'constraint' => 11
                ],
            'gambar'   => [
                'type' => 'VARCHAR', 
                'constraint' => 255
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('merek_id', 'merek', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('mobil');
    }

    public function down()
    {
        $this->forge->dropTable('mobil');
    }
}
