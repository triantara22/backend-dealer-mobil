<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTestdriveTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'                => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'customer_id'       => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'car_id'            => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'tanggal_jadwal'    => ['type' => 'DATETIME'],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('test_drives');
    }

    public function down()
    {
        $this->forge->dropTable('test_drives');
    }
}
