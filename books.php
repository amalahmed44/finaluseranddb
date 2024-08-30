<?php
session_start();
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
    $quantity = 1; // Default quantity to 1

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

include 'header.php'; 

// Fetch all categories from the books table
$category_sql = "SELECT DISTINCT category FROM books";
$category_result = $conn->query($category_sql);

// Fetch books based on selected category or all books if no category is selected
$books_sql = "SELECT * FROM books";
if (isset($_POST['category']) && $_POST['category'] != 'all') {
    $selected_category = $conn->real_escape_string($_POST['category']);
    $books_sql .= " WHERE category='$selected_category'";
}
$books_result = $conn->query($books_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Books</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .container {
            display: flex;
        }
        .categories {
            width: 20%;
            padding: 20px;
        }
        .books {
            width: 80%;
            padding: 20px;
        }
        .book-item {
            margin-bottom: 20px;
        }
        .book-card {
            cursor: pointer;
            transition: transform 0.2s;
        }
        .book-card:hover {
            transform: scale(1.05);
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="categories">
            <h4>Categories</h4>
            <form action="books.php" method="post">
                <div class="form-group">
                    <input type="radio" name="category" value="all" id="all" checked>
                    <label for="all">All</label>
                </div>
                <?php while ($row = $category_result->fetch_assoc()): ?>
                    <div class="form-group">
                        <input type="radio" name="category" value="<?php echo htmlspecialchars($row['category']); ?>" id="<?php echo htmlspecialchars($row['category']); ?>">
                        <label for="<?php echo htmlspecialchars($row['category']); ?>"><?php echo htmlspecialchars($row['category']); ?></label>
                    </div>
                <?php endwhile; ?>
                <button type="submit" class="btn btn-primary">Filter</button>
            </form>
        </div>
        <div class="books">
            <h4>Books</h4>
            <div class="row">
                <?php while ($row = $books_result->fetch_assoc()): ?>
                    <div class="col-md-4 book-item">
                        <div class="card book-card">
                            <a href="book-details.php?book_id=<?php echo $row['book_id']; ?>" class="text-decoration-none text-dark">
                                <img src="<?php echo htmlspecialchars($row['image']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($row['title']); ?>">
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo htmlspecialchars($row['title']); ?></h5>
                                    <p class="card-text">Author: <?php echo htmlspecialchars($row['author']); ?></p>
                                    <p class="card-text">Price: $<?php echo htmlspecialchars($row['price']); ?></p>
                                </div>
                            </a>
                            <!-- Add to Cart Form -->
                            <form action="books.php" method="post">
                                <input type="hidden" name="action" value="add">
                                <input type="hidden" name="book_id" value="<?php echo $row['book_id']; ?>">
                                <button type="submit" class="btn btn-primary">Add to Cart</button>
                            </form>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </div>
</body>
</html>

<?php
include 'footer.php';
$conn->close();
?>
