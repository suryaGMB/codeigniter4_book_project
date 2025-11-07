<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateOrdersTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'                   => ['type' => 'INT','constraint'=>11,'unsigned'=>true,'auto_increment'=>true],
            'user_id'              => ['type' => 'INT','constraint'=>11,'unsigned'=>true],
            'book_id'              => ['type' => 'INT','constraint'=>11,'unsigned'=>true],
            'razorpay_order_id'    => ['type' => 'VARCHAR','constraint'=>191,'null'=>true],
            'razorpay_payment_id'  => ['type' => 'VARCHAR','constraint'=>191,'null'=>true],
            'razorpay_signature'   => ['type' => 'VARCHAR','constraint'=>191,'null'=>true],
            'amount'               => ['type' => 'INT','null'=>false],
            'currency'             => ['type' => 'VARCHAR','constraint'=>10,'default'=>'INR'],
            'status'               => ['type' => 'ENUM("created","paid","failed")','default'=>'created'],
            'created_at'           => ['type' => 'DATETIME','null'=>true],
            'updated_at'           => ['type' => 'DATETIME','null'=>true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['user_id','book_id']);
        $this->forge->createTable('orders');
    }

    public function down()
    {
        $this->forge->dropTable('orders');
    }
}
