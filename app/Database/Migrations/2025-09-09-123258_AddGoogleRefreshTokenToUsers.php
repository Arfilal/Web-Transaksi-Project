<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddGoogleRefreshTokenToUsers extends Migration
{
    public function up()
    {
        $fields = [
            'google_refresh_token' => [
                'type'       => 'VARCHAR',
                'constraint' => 255, // Sesuaikan panjang string jika diperlukan
                'null'       => true,
                'after'      => 'password', // Opsional: letakkan kolom setelah 'password'
            ],
        ];

        $this->forge->addColumn('users', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('users', 'google_refresh_token');
    }
}