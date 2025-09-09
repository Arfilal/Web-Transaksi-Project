<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddCustomerIdToTransactions extends Migration
{
    public function up()
    {
        $fields = [
            'customer_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'user_id', // Menambahkan kolom setelah user_id
            ],
        ];

        $this->forge->addColumn('transactions', $fields);
        
        // Tambahkan foreign key
        $this->forge->addForeignKey('customer_id', 'customers', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->forge->dropColumn('transactions', 'customer_id');
    }
}
