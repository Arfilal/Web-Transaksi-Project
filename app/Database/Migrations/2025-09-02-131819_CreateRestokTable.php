<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateRestokTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_restok' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'id_restoker' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'id_item' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'stok_dipesan' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'stok_sampai' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
            ],
            'tanggal_pesan' => [
                'type' => 'DATETIME',
                'null' => true,   // ✅ biar bisa diisi manual
            ],
            'tanggal_sampai' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id_restok', true);
        $this->forge->addForeignKey('id_restoker', 'restokers', 'id_restoker', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('id_item', 'items', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('restok');
    }

    public function down()
    {
        $this->forge->dropTable('restok');
    }
}
