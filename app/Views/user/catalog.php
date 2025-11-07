<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<h1 class="h3 mb-3">Books Catalog</h1>

<?php if (empty($books)): ?>
    <div class="alert alert-info">No books available yet.</div>
<?php else: ?>
<table class="table table-bordered table-striped align-middle">
    <thead>
        <tr>
            <th style="width:70px">ID</th>
            <th>Title</th>
            <th>Author</th>
            <th style="width:120px">Price (₹)</th>
            <th style="width:160px">Action</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($books as $index => $b): ?>
        <tr>
            <td><?= $index + 1 ?></td>
            <td><?= esc($b['title']) ?></td>
            <td><?= esc($b['author']) ?></td>
            <td><?= number_format((float)$b['price'],2) ?></td>
            <td>
                <?php if (!empty($owned) && in_array($b['id'], $owned, true)): ?>
                    <span class="badge bg-success">Purchased</span>
                <?php else: ?>
                    <a class="btn btn-sm btn-primary" href="<?= site_url('pay/book/'.$b['id']) ?>">Buy</a>
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<?php endif; ?>

<?= $this->endSection() ?>
