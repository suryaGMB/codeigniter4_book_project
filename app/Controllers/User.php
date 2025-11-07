<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\OrderModel;
use CodeIgniter\HTTP\ResponseInterface;

class User extends BaseController
{
    public function dashboard()
    {
        $userId = (int) session('user_id');
        if (!$userId) {
            return redirect()->to('/login')->with('errors', ['Please login first.']);
        }

        $orders = (new \App\Models\OrderModel())->getUserPurchases($userId);
        return view('user/dashboard', ['orders' => $orders]);
    }

    public function myCourses()
    {
        $userId = (int) session('user_id');
        $orders = (new OrderModel())->getUserPurchases($userId);
        return view('user/my_courses', ['orders' => $orders]);
    }

    public function catalog()
    {
        $userId = (int) session('user_id');
        $orders = (new \App\Models\OrderModel())->where([
            'user_id' => $userId,
            'status'  => 'paid'
        ])->findAll();

        $owned = array_column($orders, 'book_id');

        $books = (new \App\Models\BookModel())->orderBy('id','DESC')->findAll();

        return view('user/catalog', [
            'books' => $books,
            'owned' => $owned
        ]);
    }

}
