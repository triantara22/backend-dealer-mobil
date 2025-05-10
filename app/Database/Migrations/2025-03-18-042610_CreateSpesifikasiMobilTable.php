<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSpesifikasiMobilTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'id_mobil' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'tipe_mesin' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'tenaga' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'torsi' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'sistem_penggerak' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'fitur_keamanan' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'sistem_hiburan' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'konektivitas' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'ac' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('id_mobil', 'mobil', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('spesifikasi');
    }

    public function down()
    {
        $this->forge->dropTable('spesifikasi');
    }
}
