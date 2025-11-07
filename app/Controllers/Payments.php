<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\BookModel;
use App\Models\OrderModel;
use CodeIgniter\HTTP\ResponseInterface;
use Razorpay\Api\Api;

class Payments extends BaseController
{
    protected $helpers = ['url', 'form'];
    protected Api $razor;

    public function __construct()
    {
        $keyId     = env('razorpay.key_id');
        $keySecret = env('razorpay.key_secret');
        $this->razor = new Api($keyId, $keySecret);
    }

    public function buy($bookId)
    {
        $userId = session('user_id');
        if (!$userId) {
            return redirect()->to('/login')->with('errors', ['Please login first.']);
        }

        $book = (new BookModel())->find($bookId);
        if (!$book) {
            return redirect()->back()->with('errors', ['Book not found.']);
        }

        $amount = (int) round(((float)$book['price']) * 100);

        $orderModel = new OrderModel();
        $orderId = $orderModel->insert([
            'user_id' => $userId,
            'book_id' => $bookId,
            'amount'  => $amount,
            'currency'=> 'INR',
            'status'  => 'created',
        ], true);

        $rzpOrder = $this->razor->order->create([
            'amount' => $amount,
            'currency' => 'INR',
            'receipt' => 'order_'.$orderId,
            'payment_capture' => 1,
        ]);

        $orderModel->update($orderId, [
            'razorpay_order_id' => $rzpOrder['id']
        ]);

        return view('payments/checkout', [
            'book' => $book,
            'order'=> $orderModel->find($orderId),
            'keyId'=> env('razorpay.key_id'),
            'user' => [
                'name' => session('name'),
                'email'=> session('email')
            ],
            'baseUrl' => base_url()
        ]);
    }

    public function callback()
    {
        $request = $this->request;

        $razorpayPaymentId = $request->getPost('razorpay_payment_id');
        $razorpayOrderId   = $request->getPost('razorpay_order_id');
        $razorpaySignature = $request->getPost('razorpay_signature');

        if (!$razorpayPaymentId || !$razorpayOrderId || !$razorpaySignature) {
            return redirect()->to('/dashboard')->with('errors', ['Payment details missing.']);
        }

        $orderModel = new OrderModel();
        $order = $orderModel->where('razorpay_order_id', $razorpayOrderId)->first();
        if (!$order) {
            return redirect()->to('/dashboard')->with('errors', ['Order not found.']);
        }

        try {
            $attributes = [
                'razorpay_order_id' => $razorpayOrderId,
                'razorpay_payment_id' => $razorpayPaymentId,
                'razorpay_signature' => $razorpaySignature
            ];
            $this->razor->utility->verifyPaymentSignature($attributes);

            $orderModel->update($order['id'], [
                'razorpay_payment_id' => $razorpayPaymentId,
                'razorpay_signature'  => $razorpaySignature,
                'status' => 'paid'
            ]);

            return redirect()->to('/dashboard')->with('message', 'Payment successful! Enjoy your book.');

        } catch (\Razorpay\Api\Errors\SignatureVerificationError $e) {
            $orderModel->update($order['id'], [
                'status' => 'failed'
            ]);
            return redirect()->to('/dashboard')->with('errors', ['Payment verification failed: '.$e->getMessage()]);
        } catch (\Throwable $t) {
            $orderModel->update($order['id'], ['status' => 'failed']);
            return redirect()->to('/dashboard')->with('errors', ['Payment error: '.$t->getMessage()]);
        }
    }

    public function failed()
    {
        return redirect()->to('/dashboard')->with('errors', ['Payment cancelled or failed.']);
    }
}
