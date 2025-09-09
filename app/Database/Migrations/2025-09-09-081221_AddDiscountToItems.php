<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDiscountToItems extends Migration
{
    public function up()
    {
        $fields = [
            'diskon' => [
                'type'       => 'DECIMAL',
                'constraint' => '5,2',
                'default'    => 0.00,
                'after'      => 'harga',
            ],
        ];

        $this->forge->addColumn('items', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('items', 'diskon');
    }
}
