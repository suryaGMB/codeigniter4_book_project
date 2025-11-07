<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<h1 class="h3 mb-3">Login</h1>

<form action="<?= site_url('login') ?>" method="post" class="card card-body">
    <?= csrf_field() ?>

    <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" value="<?= old('email') ?>" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Password</label>
        <input type="password" name="password" class="form-control" required>
    </div>

    <div class="d-flex gap-2">
        <button class="btn btn-primary">Login</button>
    </div>
</form>

<?= $this->endSection() ?>
