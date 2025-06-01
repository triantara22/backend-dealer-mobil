<?php
namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateKlaimGaransi extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'              => ['type' => 'varchar', 'constraint' => 20],
            'garansi_id'      => ['type' => 'VARCHAR', 'constraint' => 20],
            'klaim_deskripsi' => ['type' => 'TEXT'],
            'klaim_tanggal'   => ['type' => 'DATETIME'],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('garansi_id', 'garansi', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('klaim');
    }

    public function down()
    {
        $this->forge->dropTable('klaim', true);
    }
}
