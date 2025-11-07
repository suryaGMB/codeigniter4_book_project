<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\OrderModel;
use CodeIgniter\HTTP\ResponseInterface;

class Admin extends BaseController
{
    public function purchases()
    {
        $orders = (new OrderModel())->getAllWithJoins();
        $stats  = (new OrderModel())->getStats();

        return view('admin/purchases/index', [
            'orders' => $orders,
            'stats'  => $stats,
        ]);
    }

    public function purchasesByBook($bookId)
    {
        $orders = (new OrderModel())->getByBookId((int)$bookId);
        return view('admin/purchases/by_book', ['orders' => $orders, 'bookId' => (int)$bookId]);
    }

    public function purchasesByUser($userId)
    {
        $orders = (new OrderModel())->getByUserId((int)$userId);
        return view('admin/purchases/by_user', ['orders' => $orders, 'userId' => (int)$userId]);
    }
}
