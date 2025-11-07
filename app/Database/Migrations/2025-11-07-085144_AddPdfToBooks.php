<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPdfToBooks extends Migration
{
    public function up()
    {
        $this->forge->addColumn('books', [
            'pdf_file' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'after'      => 'price'
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('books', 'pdf_file');
    }
}
