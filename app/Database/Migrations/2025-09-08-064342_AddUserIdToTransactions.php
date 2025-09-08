<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddUserIdToTransactions extends Migration
{
    public function up()
    {
        $this->forge->addColumn('transactions', [
            'user_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'after' => 'id',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('transactions', 'user_id');
    }
}