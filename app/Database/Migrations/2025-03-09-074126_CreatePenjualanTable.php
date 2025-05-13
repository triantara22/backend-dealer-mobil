<?php
namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePenjualanTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'                => [
                'type'       => 'varchar',
                'constraint' => 20,
            ],
            'pelanggan_id'      => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'mobil_id'          => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'user_id'           => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'tanggal_transaksi' => [
                'type' => 'DATETIME',
            ],
            'total_harga'       => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
            ],
            'status_pembayaran' => [
                'type'       => 'ENUM',
                'constraint' => ['pending', 'dibayar', 'dibatalkan'],
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('pelanggan_id', 'pelanggan', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('mobil_id', 'mobil', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('sales');
    }

    public function down()
    {
        $this->forge->dropTable('sales');
    }
}
