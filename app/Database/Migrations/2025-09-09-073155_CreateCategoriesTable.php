<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddCategoryIdToItems extends Migration
{
    public function up()
    {
        $fields = [
            'category_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'nama_item',
            ],
        ];
        $this->forge->addColumn('items', $fields);
        $this->forge->addForeignKey('category_id', 'categories', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->forge->dropForeignKey('items', 'items_category_id_foreign');
        $this->forge->dropColumn('items', 'category_id');
    }
}