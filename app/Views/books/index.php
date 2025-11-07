<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">Books</h1>
    <a href="<?= site_url('books/create') ?>" class="btn btn-primary">Add Book</a>
</div>

<?php if (empty($books)): ?>
    <div class="alert alert-info">No books found.</div>
<?php else: ?>
<table class="table table-bordered table-striped align-middle">
    <thead>
        <tr>
            <th style="width:70px">ID</th>
            <th>Title</th>
            <th>Author</th>
            <th style="width:120px">Price</th>
            <th style="width:220px">Actions</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($books as $index => $b): ?>
        <tr>
            <td><?= $index + 1 ?></td>
            <td><?= esc($b['title']) ?></td>
            <td><?= esc($b['author']) ?></td>
            <td><?= esc($b['price']) ?></td>
            <td>
                <a class="btn btn-sm btn-outline-secondary" href="<?= site_url('books/show/'.id_to_url($b['id'])) ?>">View</a>
                <a class="btn btn-sm btn-warning" href="<?= base_url('books/edit/' . id_to_url($b['id'])) ?>">Edit</a>
                <a class="btn btn-sm btn-danger" href="<?= site_url('books/delete/'.id_to_url($b['id'])) ?>"
                   onclick="return confirm('Delete this book?')">Delete</a>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<?php endif; ?>

<?= $this->endSection() ?>
