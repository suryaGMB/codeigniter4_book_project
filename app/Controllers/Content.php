<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\BookModel;
use App\Models\OrderModel;
use CodeIgniter\HTTP\ResponseInterface;

class Content extends BaseController
{
    public function download($bookId)
    {
        if (! session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('errors', ['Please login first.']);
        }

        $book = (new \App\Models\BookModel())->find((int)$bookId);
        if (! $book || empty($book['pdf_file'])) {
            return redirect()->back()->with('errors', ['PDF not found for this book.']);
        }

        if (session('role') !== 'admin') {
            $userId = (int) session('user_id');
            $purchased = (new \App\Models\OrderModel())->where([
                'user_id' => $userId,
                'book_id' => (int) $bookId,
                'status'  => 'paid'
            ])->first();

            if (! $purchased) {
                return redirect()->back()->with('errors', ['You need to purchase this book to access the PDF.']);
            }
        }

        $path = WRITEPATH . 'uploads/books/' . $book['pdf_file'];
        if (! is_file($path)) {
            return redirect()->back()->with('errors', ['PDF file missing on server.']);
        }

        return $this->response
            ->setHeader('Content-Type', 'application/pdf')
            ->setHeader('Content-Disposition', 'inline; filename="' . basename($book['pdf_file']) . '"')
            ->download($path, null);
    }

}
