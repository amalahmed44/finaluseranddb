<?php
session_start();
include 'header.php';

// Calculate total price from cart
$total_price = 0;
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $total_price += $item['price'] * $item['quantity'];
    }
}
?>

<!-- Main content of the checkout page -->
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg p-4 rounded">
                <h2 class="text-center mb-4">Checkout</h2>
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger"><?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></div>
                <?php endif; ?>
                <form action="proceed_to_checkout.php" method="post" onsubmit="return validateForm()" class="needs-validation" novalidate>
                    <div class="form-group mb-3">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="region">Region</label>
                        <input type="text" class="form-control" id="region" name="region" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="phone">Phone Number</label>
                        <input type="text" class="form-control" id="phone" name="phone" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="address">Address</label>
                        <textarea class="form-control" id="address" name="address" rows="3" required></textarea>
                    </div>
                    <div class="form-group mb-3">
                        <label for="payment_number">Payment Number</label>
                        <input type="text" class="form-control" id="payment_number" name="payment_number" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="amount">Amount</label>
                        <input type="number" step="0.01" class="form-control" id="amount" name="amount" value="<?php echo $total_price; ?>" readonly>
                    </div>
                    <div class="form-group mb-3">
                        <label for="payment_method">Payment Method</label>
                        <select class="form-control" id="payment_method" name="payment_method" required>
                            <option value="Zaad">Zaad</option>
                            <option value="Edahab">Edahab</option>
                        </select>
                    </div>
                    <div class="form-group mb-4">
                        <label for="reference_number">Reference Number</label>
                        <input type="text" class="form-control" id="reference_number" name="reference_number" required>
                    </div>
                    <button type="submit" class="btn btn-success btn-block">Submit Order</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>

<!-- Additional Styles -->
<style>
    .card {
        background-color: #f8f9fa;
        border-radius: 8px;
        border: none;
    }
    .btn-success {
        background-color: #28a745;
        border-color: #28a745;
        transition: background-color 0.3s ease;
    }
    .btn-success:hover {
        background-color: #218838;
        border-color: #1e7e34;
    }
</style>

<!-- Additional Scripts -->
<script>
    function validateForm() {
        var name = document.forms["billing-form"]["name"].value;
        var phone = document.forms["billing-form"]["phone"].value;
        var paymentNumber = document.forms["billing-form"]["payment_number"].value;

        var namePattern = /^[A-Za-z\s]+$/;
        var numberPattern = /^\d+$/;

        if (!namePattern.test(name)) {
            alert("Please enter text only for the name.");
            return false;
        }
        if (!numberPattern.test(phone)) {
            alert("Please enter numbers only for the phone.");
            return false;
        }
        if (!numberPattern.test(paymentNumber)) {
            alert("Please enter numbers only for the payment number.");
            return false;
        }
        return true;
    }

    document.addEventListener("DOMContentLoaded", function() {
        // Automatically fill the amount field
        var totalAmount = <?php echo $total_price; ?>;
        document.getElementById('amount').value = totalAmount;
    });
</script>
