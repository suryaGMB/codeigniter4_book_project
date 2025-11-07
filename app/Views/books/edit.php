<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<h1 class="h3 mb-3">Edit Book #<?= esc($book['id']) ?></h1>

<form action="<?= base_url('books/update/' . id_to_url($book['id'])) ?>" method="post">
    <?= csrf_field() ?>

    <div class="mb-3">
        <label class="form-label">Title</label>
        <input name="title" value="<?= old('title', $book['title']) ?>" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Author</label>
        <input name="author" value="<?= old('author', $book['author']) ?>" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Price</label>
        <input name="price" value="<?= old('price', $book['price']) ?>" type="number" step="0.01" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Replace PDF (optional)</label>
        <input type="file" name="pdf" accept="application/pdf" class="form-control">
        <?php if (!empty($book['pdf_file'])): ?>
        <small class="text-muted">Current: <?= esc($book['pdf_file']) ?></small>
        <?php endif; ?>
    </div>
    
    <div class="d-flex gap-2">
        <button class="btn btn-primary">Update</button>
        <a href="<?= site_url('books') ?>" class="btn btn-secondary">Back</a>
    </div>
</form>

<?= $this->endSection() ?>
