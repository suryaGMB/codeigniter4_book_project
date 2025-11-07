<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<h1 class="h3 mb-3">Add Book</h1>

<form action="<?= site_url('books/store') ?>" method="post"
      enctype="multipart/form-data"  
      class="card card-body">
  <?= csrf_field() ?>

  <div class="mb-3">
    <label class="form-label">Title</label>
    <input name="title" class="form-control" required>
  </div>

  <div class="mb-3">
    <label class="form-label">Author</label>
    <input name="author" class="form-control" required>
  </div>

  <div class="mb-3">
    <label class="form-label">Price (₹)</label>
    <input name="price" type="number" step="0.01" class="form-control" required>
  </div>

  <div class="mb-3">
    <label class="form-label">PDF (Max 20MB)</label>
    <input type="file" name="pdf" accept="application/pdf" class="form-control" required>
  </div>

  <button class="btn btn-primary">Save</button>
</form>

<?= $this->endSection() ?>
