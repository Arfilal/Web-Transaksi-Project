<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddCustomerDetailsToTransactions extends Migration
{
    public function up()
    {
        $this->forge->addColumn('transactions', [
            'customer_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
                'after'      => 'user_id',
            ],
            'customer_phone' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'null'       => true,
                'after'      => 'customer_name',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('transactions', ['customer_name', 'customer_phone']);
    }
}

