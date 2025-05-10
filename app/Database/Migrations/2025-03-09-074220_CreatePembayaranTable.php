<?php
namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePembayaranTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'                 => [
                'type'       => 'varchar',
                'constraint' => 20,
            ],
            'sales_id'           => [
                'type'       => 'varchar',
                'constraint' => 20,
                'unsigned'   => true,
            ],
            'tanggal_pembayaran' => [
                'type' => 'DATETIME',
            ],
            'metode_pembayaran'  => [
                'type'       => 'ENUM',
                'constraint' => ['cash', 'credit', 'transfer'],
            ],
            'jumlah_bayar'       => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('sales_id', 'sales', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('pembayaran');
    }

    public function down()
    {
        $this->forge->dropTable('pembayaran');
    }
}
