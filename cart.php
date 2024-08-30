<?php
session_start(); // Start the session

// Database connection
$conn = new mysqli('localhost', 'root', '', 'bookstore');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize cart if not already set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page with a message
    header("Location: login.php?message=Please log in to add items to your cart");
    exit();
}

$user_id = intval($_SESSION['user_id']);

// Handle add to cart
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    $book_id = intval($_POST['book_id']);
    
    if ($action == 'add') {
        $quantity = intval($_POST['quantity']);

        // Check if book already in cart
        if (isset($_SESSION['cart'][$book_id])) {
            // Update quantity and total
            $_SESSION['cart'][$book_id]['quantity'] += $quantity;
            $_SESSION['cart'][$book_id]['total'] = $_SESSION['cart'][$book_id]['price'] * $_SESSION['cart'][$book_id]['quantity'];
        } else {
            // Fetch book details
            $sql = "SELECT * FROM books WHERE book_id = $book_id";
            $result = $conn->query($sql);
            if ($result && $result->num_rows > 0) {
                $book = $result->fetch_assoc();
                $_SESSION['cart'][$book_id] = [
                    'title' => $book['title'],
                    'price' => $book['price'],
                    'image' => $book['image'],
                    'quantity' => $quantity,
                    'total' => $book['price'] * $quantity
                ];
            }
        }
    } elseif ($action == 'remove') {
        // Handle remove from cart
        unset($_SESSION['cart'][$book_id]);
    }

    // Redirect to cart page
    header('Location: cart.php');
    exit();
}

// Fetch cart items
$cart_items = [];
$total_price = 0;

foreach ($_SESSION['cart'] as $book_id => $item) {
    $sql = "SELECT * FROM books WHERE book_id = $book_id";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $book = $result->fetch_assoc();
        $book['quantity'] = $item['quantity'];
        $book['total'] = $item['price'] * $item['quantity']; // Calculate the total dynamically
        $cart_items[] = $book;
        $total_price += $book['total']; // Add to total price
    }
}

$conn->close();
?>

<?php include 'header.php'; ?>

<!-- Main content of the cart page -->
<div class="container mt-5">
    <h2 class="text-center">Shopping Cart</h2>
    <?php if (empty($cart_items)): ?>
        <p class="text-center">Your cart is empty.</p>
    <?php else: ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Title</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Total</th>
                    <th>Action</th> <!-- Action Column -->
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cart_items as $item): ?>
                    <tr>
                        <td><img src="<?php echo htmlspecialchars($item['image']); ?>" class="img-thumbnail" style="width: 100px;" alt="<?php echo htmlspecialchars($item['title']); ?>"></td>
                        <td><?php echo htmlspecialchars($item['title']); ?></td>
                        <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                        <td>$<?php echo htmlspecialchars($item['price']); ?></td>
                        <td>$<?php echo htmlspecialchars($item['total']); ?></td>
                        <td>
                            <form action="cart.php" method="post">
                                <input type="hidden" name="action" value="remove">
                                <input type="hidden" name="book_id" value="<?php echo $item['book_id']; ?>">
                                <button type="submit" class="btn btn-danger">Remove</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <tr>
                    <td colspan="5" class="text-right"><strong>Total:</strong></td>
                    <td>$<?php echo htmlspecialchars($total_price); ?></td>
                </tr>
            </tbody>
        </table>
        <a href="checkout.php" class="btn btn-success">Proceed to Checkout</a>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>
