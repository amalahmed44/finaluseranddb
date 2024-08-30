<?php
session_start();

// Database connection
$conn = new mysqli('localhost', 'root', '', 'bookstore');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve and sanitize form inputs
    $name = $conn->real_escape_string($_POST['name']);
    $region = $conn->real_escape_string($_POST['region']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $address = $conn->real_escape_string($_POST['address']);
    $payment_number = $conn->real_escape_string($_POST['payment_number']);
    $amount = floatval($_POST['amount']);
    $payment_method = $conn->real_escape_string($_POST['payment_method']);
    $reference_number = $conn->real_escape_string($_POST['reference_number']);

    // Check if user is logged in
    if (!isset($_SESSION['user_id'])) {
        $error = "You need to log in to proceed to checkout.";
        header('Location: login.php');
        exit();
    }

    $user_id = intval($_SESSION['user_id']);

    // Validate input
    if (empty($name) || empty($region) || empty($phone) || empty($address) || empty($payment_number) || empty($amount) || empty($payment_method) || empty($reference_number)) {
        $error = "All fields are required.";
    } else {
        // Insert order into the orders table
        $order_sql = "INSERT INTO orders (user_id, name, region, phone, address, payment_method, amount, reference_number) 
                      VALUES ($user_id, '$name', '$region', '$phone', '$address', '$payment_method', $amount, '$reference_number')";

        if ($conn->query($order_sql) === TRUE) {
            $order_id = $conn->insert_id;

            // Insert order items into the order_items table
            foreach ($_SESSION['cart'] as $book_id => $item) {
                $title = $conn->real_escape_string($item['title']);
                $price = floatval($item['price']);  // Ensure price is a float
                $quantity = intval($item['quantity']);  // Ensure quantity is an integer
                $total = floatval($item['total']);  // Ensure total is a float

                $order_item_sql = "INSERT INTO order_items (order_id, book_id, price, quantity, total) 
                                   VALUES ($order_id, $book_id, $price, $quantity, $total)";
                if (!$conn->query($order_item_sql)) {
                    $error = "Error inserting order item: " . $conn->error;
                    break;
                }
            }

            // Clear the cart
            unset($_SESSION['cart']);

            // Redirect to a thank you page or order summary
            if (!isset($error)) {
                header('Location: home.php');
                exit();
            }
        } else {
            $error = "Error: " . $conn->error;
        }
    }
}

$conn->close();
?>
