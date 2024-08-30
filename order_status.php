<?php

session_start();
// Include header and database connection
include 'header.php';
include 'connection.php';


// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

// Fetch user ID from session
$user_id = $_SESSION['user_id'];

// Fetch the user's orders with their statuses
$sql = "SELECT orders.id AS order_id, orders.amount, orders.payment_method, 
        GROUP_CONCAT(order_items.status SEPARATOR ', ') AS status, orders.created_at 
        FROM orders 
        JOIN order_items ON orders.id = order_items.order_id 
        WHERE orders.user_id = ?
        GROUP BY orders.id";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="container mt-5">
    <h2 class="text-center">Your Order Status</h2>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="card mb-4 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">Order ID: <?php echo htmlspecialchars($row['order_id']); ?></h5>
                            <p class="card-text">
                                <strong>Amount:</strong> $<?php echo htmlspecialchars($row['amount']); ?><br>
                                <strong>Payment Method:</strong> <?php echo htmlspecialchars($row['payment_method']); ?><br>
                                <strong>Status:</strong> <?php echo htmlspecialchars($row['status']); ?><br>
                                <strong>Order Date:</strong> <?php echo htmlspecialchars($row['created_at']); ?><br>
                            </p>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="alert alert-info text-center" role="alert">
                    You have no orders.
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
$stmt->close();
$conn->close();
include 'footer.php';
?>
