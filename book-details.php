<?php
ob_start(); // Start output buffering
session_start();
include 'header.php'; 
include 'connection.php'; 

// Check if the user is logged in
$is_logged_in = isset($_SESSION['user_id']);
$user_id = $is_logged_in ? $_SESSION['user_id'] : 0;

// Handle add to cart
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'add') {
    if (!$is_logged_in) {
        // Redirect to login page with a message
        header("Location: login.php?message=Please log in to add items to your cart");
        exit(); // Ensure script stops after redirect
    }

    $book_id = intval($_POST['book_id']);
    $quantity = intval($_POST['quantity']);

    // Add to cart logic
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Check if book already in cart
    if (isset($_SESSION['cart'][$book_id])) {
        $_SESSION['cart'][$book_id]['quantity'] += $quantity;
    } else {
        // Fetch book details
        $sql = "SELECT * FROM books WHERE book_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $book_id);
        $stmt->execute();
        $result = $stmt->get_result();
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

    // Redirect to cart page
    header("Location: cart.php");
    exit(); // Ensure script stops after redirect
}
?>

<!-- Main content of the book details page -->
<div class="container mt-5">
    <?php
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Get the book ID from the URL
    $book_id = isset($_GET['book_id']) ? intval($_GET['book_id']) : 0;

    // Query to get book details
    $sql = "SELECT * FROM books WHERE book_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $book_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $book = $result->fetch_assoc();
        $title = htmlspecialchars($book['title']);
        $author = htmlspecialchars($book['author']);
        $category = htmlspecialchars($book['category']);
        $description = htmlspecialchars($book['description']);
        $price = htmlspecialchars($book['price']);
        $image = htmlspecialchars($book['image']);
        ?>

        <div class="row">
            <div class="col-md-6">
                <img src="<?php echo $image; ?>" class="img-fluid rounded" alt="<?php echo $title; ?>">
            </div>
            <div class="col-md-6">
                <h1><?php echo $title; ?></h1>
                <h4>Author: <?php echo $author; ?></h4>
                <h4>Category: <?php echo $category; ?></h4>
                <p><?php echo $description; ?></p>
                <p><strong>Price: $<?php echo $price; ?></strong></p>
                <form action="book-details.php?book_id=<?php echo $book_id; ?>" method="post">
                    <input type="hidden" name="action" value="add">
                    <input type="hidden" name="book_id" value="<?php echo $book_id; ?>">
                    <div class="form-group">
                        <label for="quantity">Quantity:</label>
                        <input type="number" id="quantity" name="quantity" class="form-control" min="1" value="1">
                    </div>
                    <button type="submit" class="btn btn-primary">Add to Cart</button>
                </form>
            </div>
        </div>

        <?php
    } else {
        echo "<p class='text-center'>Book not found.</p>";
    }

    $conn->close();
    ?>
</div>

<?php include 'footer.php'; ?>

<?php
ob_end_flush(); // End output buffering and flush output
?>
