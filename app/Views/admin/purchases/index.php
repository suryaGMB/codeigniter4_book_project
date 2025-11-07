<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<h1 class="h3 mb-3">Purchases (All)</h1>

<div class="row g-3 mb-3">
  <div class="col-auto">
    <div class="card card-body py-2">
      <strong>Total Orders:</strong> <?= esc($stats['orders_total'] ?? 0) ?>
    </div>
  </div>
  <div class="col-auto">
    <div class="card card-body py-2">
      <strong>Paid Orders:</strong> <?= esc($stats['orders_paid'] ?? 0) ?>
    </div>
  </div>
  <div class="col-auto">
    <div class="card card-body py-2">
      <strong>Revenue (₹):</strong> <?= number_format((float)($stats['revenue_inr'] ?? 0), 2) ?>
    </div>
  </div>
</div>

<?php if (empty($orders)): ?>
  <div class="alert alert-info">No orders yet.</div>
<?php else: ?>
<table class="table table-bordered table-striped align-middle">
  <thead>
    <tr>
      <th style="width:70px">Order</th>
      <th>User</th>
      <th>Book</th>
      <th style="width:110px">Amount (₹)</th>
      <th style="width:100px">Status</th>
      <th style="width:210px">Created At</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($orders as $index => $o): ?>
    <tr>
        <td><?= $index + 1 ?></td>
        <td>
          <?= esc($o['user_name']) ?><br>
          <small class="text-muted"><?= esc($o['user_email']) ?></small>
        </td>
        <td>
          <?= esc($o['book_title']) ?><br>
          <small class="text-muted"><?= esc($o['book_author']) ?></small>
        </td>
        <td><?= number_format(((int)$o['amount'])/100, 2) ?></td>
        <td>
          <?php if ($o['status']==='paid'): ?>
            <span class="badge bg-success">Paid</span>
          <?php elseif ($o['status']==='failed'): ?>
            <span class="badge bg-danger">Failed</span>
          <?php else: ?>
            <span class="badge bg-secondary">Created</span>
          <?php endif; ?>
        </td>
        <td><?= esc($o['created_at']) ?></td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<?php endif; ?>

<?= $this->endSection() ?>
