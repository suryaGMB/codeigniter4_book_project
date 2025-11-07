<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<h1 class="h3 mb-3">Register</h1>

<form action="<?= site_url('register') ?>" method="post" class="card card-body">
    <?= csrf_field() ?>

    <div class="mb-3">
        <label class="form-label">Name</label>
        <input name="name" value="<?= old('name') ?>" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" value="<?= old('email') ?>" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Password</label>
        <input type="password" name="password" class="form-control" minlength="6" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Confirm Password</label>
        <input type="password" name="password_confirmation" class="form-control" minlength="6" required>
    </div>

    <div class="d-flex gap-2">
        <button class="btn btn-primary">Register</button>
        <a href="<?= site_url('login') ?>" class="btn btn-outline-secondary">Back to Login</a>
    </div>
</form>

<?= $this->endSection() ?>
