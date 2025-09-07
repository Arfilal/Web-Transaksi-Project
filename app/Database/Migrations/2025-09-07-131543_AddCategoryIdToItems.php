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

        // Menambahkan foreign key constraint
        // Pastikan Anda telah membuat tabel 'categories' sebelumnya
        $this->forge->addForeignKey('category_id', 'categories', 'id', 'SET NULL', 'CASCADE');
        $this->forge->processIndexes('items');
    }

    public function down()
    {
        // Menghapus foreign key terlebih dahulu
        $this->forge->dropForeignKey('items', 'items_category_id_foreign');
        $this->forge->dropColumn('items', 'category_id');
    }
}