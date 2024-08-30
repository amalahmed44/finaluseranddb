<?php
session_start();
require 'connection.php';
include 'header.php';

// Get the search query from the GET parameters
$query = isset($_GET['query']) ? $conn->real_escape_string($_GET['query']) : '';

if ($query) {
    $sql = "SELECT * FROM books WHERE title LIKE '%$query%' OR author LIKE '%$query%'";
    $result = $conn->query($sql);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
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
        <h4>Search Results for "<?php echo htmlspecialchars($query); ?>"</h4>
        <div class="row">
            <?php if ($query): ?>
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <div class="col-md-4 book-item">
                            <a href="book-details.php?book_id=<?php echo $row['book_id']; ?>" class="text-decoration-none text-dark">
                                <div class="card book-card">
                                    <img src="<?php echo htmlspecialchars($row['image']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($row['title']); ?>">
                                    <div class="card-body">
                                        <h5 class="card-title"><?php echo htmlspecialchars($row['title']); ?></h5>
                                        <p class="card-text">Author: <?php echo htmlspecialchars($row['author']); ?></p>
                                        <p class="card-text">Price: $<?php echo htmlspecialchars($row['price']); ?></p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>No results found for "<?php echo htmlspecialchars($query); ?>"</p>
                <?php endif; ?>
            <?php else: ?>
                <p>Please enter a search query.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

<?php
include 'footer.php';
$conn->close();
?>
