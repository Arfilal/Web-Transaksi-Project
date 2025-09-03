<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateRestokersTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_restoker'   => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'nama_restoker' => ['type' => 'VARCHAR', 'constraint' => 100],
            'kontak'        => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'alamat'        => ['type' => 'TEXT', 'null' => true],
            'created_at'    => ['type' => 'DATETIME', 'null' => true],
            'updated_at'    => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addKey('id_restoker', true);
        $this->forge->createTable('restokers');
    }

    public function down()
    {
        $this->forge->dropTable('restokers');
    }
}
