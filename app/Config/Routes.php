<?php

use App\Controllers\Admin;
use App\Controllers\Auth;
use App\Controllers\Books;
use App\Controllers\Content;
use App\Controllers\User;
use App\Controllers\Payments; // ✅ add this
use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', function () {
    if (session()->get('isLoggedIn')) {
        return session('role') === 'admin'
            ? redirect()->to('/books')
            : redirect()->to('/dashboard');
    }
    return redirect()->to('/login');
});

$routes->get('register', [Auth::class, 'register']);
$routes->post('register', [Auth::class, 'storeRegister']);
$routes->get('login', [Auth::class, 'login']);
$routes->post('login', [Auth::class, 'doLogin']);
$routes->get('logout', [Auth::class, 'logout']);

$routes->group('books', ['filter' => 'role:admin'], static function ($routes) {
    $routes->get('/',              [Books::class, 'index']);
    $routes->get('create',         [Books::class, 'create']);
    $routes->post('store',         [Books::class, 'store']);
    $routes->get('edit/(:any)',    [Books::class, 'edit/$1']);
    $routes->post('update/(:any)', [Books::class, 'update/$1']);
    $routes->get('show/(:any)',    [Books::class, 'show/$1']);
    $routes->get('delete/(:any)',  [Books::class, 'delete/$1']);
});

$routes->group('', ['filter' => 'role:user'], static function ($routes) {
    $routes->get('dashboard',  [User::class, 'dashboard']);
    $routes->get('catalog',    [User::class, 'catalog']);
    $routes->get('my-courses', [User::class, 'myCourses']);
});

$routes->group('pay', ['filter' => 'role:user'], static function ($routes) {
    $routes->get('book/(:num)', [Payments::class, 'buy/$1']);   // create Razorpay order + checkout
    $routes->post('callback',   [Payments::class, 'callback']); // success POST handler
    $routes->get('failed',      [Payments::class, 'failed']);   // optional cancel/fail
});

$routes->get('books/(:num)/download', [Content::class, 'download/$1'], ['filter' => 'role:admin,user']);

$routes->group('admin', ['filter' => 'role:admin'], static function ($routes) {
    $routes->get('purchases',               [Admin::class, 'purchases']);
    $routes->get('purchases/book/(:num)',   [Admin::class, 'purchasesByBook/$1']);
    $routes->get('purchases/user/(:num)',   [Admin::class, 'purchasesByUser/$1']);
});