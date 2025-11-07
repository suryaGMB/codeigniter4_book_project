<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>CI4 CRUD - Books</title>
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.2/css/bootstrap.min.css">
</head>
<body>
<div class="container py-4">

    <!-- Role-aware header -->
    <div class="d-flex justify-content-between align-items-center mb-3">
      <div class="fw-bold d-flex gap-2 align-items-center">
        <?php if (session()->get('isLoggedIn')): ?>
          <?php if (session('role') === 'admin'): ?>
            <a href="<?= site_url('books') ?>" class="btn btn-sm btn-outline-primary">Manage Books</a>
            <a href="<?= site_url('admin/purchases') ?>" class="btn btn-sm btn-primary">View Purchases</a>
          <?php else: ?>
            <a href="<?= site_url('dashboard') ?>" class="btn btn-sm btn-outline-primary">Dashboard</a>
            <a href="<?= site_url('catalog') ?>" class="btn btn-sm btn-primary">Catalog</a>
          <?php endif; ?>
        <?php else: ?>
        <?php endif; ?>
      </div>

      <div>
        <?php if (session()->get('isLoggedIn')): ?>
          <span class="me-2">Hi, <?= esc(session('name')) ?> (<?= esc(session('role')) ?>)</span>
          <a href="<?= site_url('logout') ?>" class="btn btn-sm btn-outline-danger">Logout</a>
        <?php else: ?>
          <a href="<?= site_url('login') ?>" class="btn btn-sm btn-outline-primary me-2">Login</a>
          <a href="<?= site_url('register') ?>" class="btn btn-sm btn-primary">Register</a>
        <?php endif; ?>
      </div>
    </div>

    <?php if (session()->getFlashdata('message')): ?>
        <div class="alert alert-success"><?= esc(session('message')) ?></div>
    <?php endif; ?>

    <?php if ($errors = session()->getFlashdata('errors')): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php foreach ($errors as $e): ?>
                    <li><?= esc($e) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?= $this->renderSection('content') ?>
</div>
</body>
</html>
