<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<h1 class="h4 mb-3">Checkout</h1>

<div class="card">
  <div class="card-body">
    <p><strong>Book:</strong> <?= esc($book['title']) ?> by <?= esc($book['author']) ?></p>
    <p><strong>Amount:</strong> ₹ <?= number_format($order['amount']/100, 2) ?></p>
    <button id="rzp-button1" class="btn btn-primary">Pay Now</button>
    <a href="<?= site_url('catalog') ?>" class="btn btn-secondary ms-2">Back</a>
  </div>
</div>

<form id="rzp-success-form" action="<?= site_url('pay/callback') ?>" method="post" class="d-none">
  <?= csrf_field() ?>
  <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id">
  <input type="hidden" name="razorpay_order_id" id="razorpay_order_id" value="<?= esc($order['razorpay_order_id']) ?>">
  <input type="hidden" name="razorpay_signature" id="razorpay_signature">
</form>

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
  var options = {
      "key": "<?= esc($keyId) ?>",
      "amount": "<?= (int)$order['amount'] ?>", // in paise
      "currency": "INR",
      "name": "Book Store",
      "description": "Purchase - <?= esc($book['title']) ?>",
      "order_id": "<?= esc($order['razorpay_order_id']) ?>",
      "prefill": {
          "name": "<?= esc($user['name']) ?>",
          "email": "<?= esc($user['email']) ?>"
      },
      "theme": { "color": "#3399cc" },
      "handler": function (response){
          document.getElementById('razorpay_payment_id').value = response.razorpay_payment_id;
          document.getElementById('razorpay_signature').value = response.razorpay_signature;
          document.getElementById('rzp-success-form').submit();
      },
      "modal": { "ondismiss": function(){ window.location.href = "<?= site_url('pay/failed') ?>"; } }
  };
  var rzp1 = new Razorpay(options);
  document.getElementById('rzp-button1').onclick = function(e){
      rzp1.open();
      e.preventDefault();
  }
</script>

<?= $this->endSection() ?>
