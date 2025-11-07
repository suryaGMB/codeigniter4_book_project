<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<h1 class="h3 mb-3">User Dashboard</h1>

<div class="mb-3">
  <a href="<?= site_url('catalog') ?>" class="btn btn-sm btn-outline-primary">Browse Catalog</a>
</div>

<h2 class="h5 mt-4">Purchased Courses</h2>

<?php if (empty($orders)): ?>
  <div class="alert alert-info">You haven’t purchased any courses yet.</div>
<?php else: ?>
  <table class="table table-bordered table-striped align-middle">
    <thead>
      <tr>
        <th style="width:70px">#</th>
        <th>Title</th>
        <th>Author</th>
        <th style="width:120px">Price (₹)</th>
        <th style="width:180px">Purchased At</th>
        <th style="width:140px">Access</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($orders as $index => $o): ?>
        <tr>
          <td><?= $index + 1 ?></td>
          <td><?= esc($o['title']) ?></td>
          <td><?= esc($o['author']) ?></td>
          <td><?= number_format((float)$o['book_price'], 2) ?></td>
          <td><?= esc($o['created_at']) ?></td>
          <td>
            <a class="btn btn-sm btn-success" href="<?= site_url('books/'.$o['book_id'].'/download') ?>">
              Open PDF
            </a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
<?php endif; ?>

<?= $this->endSection() ?>
