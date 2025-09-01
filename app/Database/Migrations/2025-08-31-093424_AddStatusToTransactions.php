<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddStatusToTransactions extends Migration
{
    public function up()
    {
        $this->forge->addColumn('transactions', [
            'status' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'default' => 'pending',
                'null' => false,
                'after' => 'total_amount'
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('transactions', 'status');
    }
}