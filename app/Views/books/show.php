<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<h1 class="h3 mb-3">Book Details</h1>

<div class="card">
    <div class="card-body">
        <p><strong>ID:</strong> <?= esc($book['id']) ?></p>
        <p><strong>Title:</strong> <?= esc($book['title']) ?></p>
        <p><strong>Author:</strong> <?= esc($book['author']) ?></p>
        <p><strong>Price:</strong> <?= esc($book['price']) ?></p>
        <a href="<?= site_url('books') ?>" class="btn btn-secondary">Back</a>
    </div>
</div>

<?= $this->endSection() ?>
