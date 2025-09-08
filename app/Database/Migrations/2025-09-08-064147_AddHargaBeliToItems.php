<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddHargaBeliToItems extends Migration
{
    public function up()
    {
        $this->forge->addColumn('items', [
            'harga_beli' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'default' => 0.00,
                'after' => 'harga',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('items', 'harga_beli');
    }
}