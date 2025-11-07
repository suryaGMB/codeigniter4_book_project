<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderModel extends Model
{
    protected $table = 'orders';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'user_id','book_id','razorpay_order_id','razorpay_payment_id','razorpay_signature',
        'amount','currency','status'
    ];
    protected $useTimestamps = true;

    public function getUserPurchases(int $userId): array
    {
        return $this->select('orders.*, books.title, books.author, books.price AS book_price')
            ->join('books', 'books.id = orders.book_id', 'inner')
            ->where('orders.user_id', $userId)
            ->where('orders.status', 'paid')
            ->orderBy('orders.id', 'DESC')
            ->findAll();
    }


    public function alreadyPurchased(int $userId, int $bookId): bool
    {
        return (bool) $this->where([
                'user_id' => $userId,
                'book_id' => $bookId,
                'status'  => 'paid'
            ])->first();
    }

    public function getAllWithJoins(): array
    {
        return $this->select('orders.*, users.name AS user_name, users.email AS user_email, books.title AS book_title, books.author AS book_author')
            ->join('users', 'users.id = orders.user_id', 'inner')
            ->join('books', 'books.id = orders.book_id', 'inner')
            ->where('orders.status', 'paid')
            ->orderBy('orders.id', 'DESC')
            ->findAll();
    }


    public function getByBookId(int $bookId): array
    {
        return $this->select('orders.*, users.name AS user_name, users.email AS user_email, books.title AS book_title')
            ->join('users', 'users.id = orders.user_id', 'inner')
            ->join('books', 'books.id = orders.book_id', 'inner')
            ->where('orders.book_id', $bookId)
            ->where('orders.status', 'paid') 
            ->orderBy('orders.id', 'DESC')
            ->findAll();
    }

    public function getByUserId(int $userId): array
    {
        return $this->select('orders.*, users.name AS user_name, users.email AS user_email, books.title AS book_title')
            ->join('users', 'users.id = orders.user_id', 'inner')
            ->join('books', 'books.id = orders.book_id', 'inner')
            ->where('orders.user_id', $userId)
            ->where('orders.status', 'paid') 
            ->orderBy('orders.id', 'DESC')
            ->findAll();
    }


    public function getStats(): array
    {
        // quick topline stats for admin header
        $paidTotal = $this->where('status','paid')->countAllResults(false);
        $rev = $this->selectSum('amount')->where('status','paid')->first();
        $revenuePaise = (int) ($rev['amount'] ?? 0);
        return [
            'orders_total' => $this->countAllResults(), // resets builder
            'orders_paid'  => $paidTotal,
            'revenue_inr'  => $revenuePaise / 100.0,
        ];
    }
}
