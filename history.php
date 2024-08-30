<?php 
session_start();
include 'header.php';
include 'connection.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<p class='text-center mt-5'>Please <a href='login.php'>login</a> to view your purchase history.</p>";
    include 'footer.php';
    exit;
}

// Fetch user ID from session
$user_id = $_SESSION['user_id'];

// Fetch the user's past purchases from the orders table
$sql = "SELECT o.id AS order_id, o.amount, o.payment_method, o.reference_number, o.created_at, oi.book_id, oi.quantity, b.title, b.price 
        FROM orders o
        JOIN order_items oi ON o.id = oi.order_id
        JOIN books b ON oi.book_id = b.book_id
        WHERE o.user_id = ? 
        ORDER BY o.created_at DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!-- Main Content -->
<div class="container mt-5">
    <h2 class="text-center">Your Purchase History</h2>
    <?php if ($result->num_rows > 0): ?>
        <div class="table-responsive mt-4">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Book Title</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Total</th>
                        <th>Payment Method</th>
                        <th>Reference Number</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['order_id']); ?></td>
                            <td><?php echo htmlspecialchars($row['title']); ?></td>
                            <td><?php echo htmlspecialchars($row['quantity']); ?></td>
                            <td>$<?php echo htmlspecialchars(number_format($row['price'], 2)); ?></td>
                            <td>$<?php echo htmlspecialchars(number_format($row['price'] * $row['quantity'], 2)); ?></td>
                            <td><?php echo htmlspecialchars($row['payment_method']); ?></td>
                            <td><?php echo htmlspecialchars($row['reference_number']); ?></td>
                            <td><?php echo htmlspecialchars(date('d M Y, H:i', strtotime($row['created_at']))); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p class="text-center mt-4">You have no past purchases.</p>
    <?php endif; ?>
</div>

<?php 
$stmt->close();
$conn->close();
include 'footer.php'; 
?>
