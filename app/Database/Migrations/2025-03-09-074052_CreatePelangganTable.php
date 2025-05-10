<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePelangganTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'      => [
                'type' => 'INT', 
                'constraint' => 11, 
                'unsigned' => true, 
                'auto_increment' => true
            ],
            'nama'    => [
                'type' => 'VARCHAR', 
                'constraint' => 100
            ],
            'email'   => [
                'type' => 'VARCHAR',
                'constraint' => 100, 
                'unique' => true
            ],
            'telepon' => [
                'type' => 'VARCHAR', 
                'constraint' => 15
            ],
            'alamat'  => [
                'type' => 'TEXT'
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('pelanggan');
    }

    public function down()
    {
        $this->forge->dropTable('pelanggan');
    }
}
