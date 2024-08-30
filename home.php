<?php 
session_start();
include 'header.php'; ?>

<!-- Main content of the home page -->
<div class="container mt-5">
    <!-- Carousel -->
    <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel" data-interval="3000">
        <ol class="carousel-indicators">
            <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
            <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
            <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
        </ol>
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="book1.jpg" class="d-block w-100 carousel-image" alt="Slide 1">
                <div class="carousel-caption d-none d-md-block">
                    <h1>Welcome to Book-Line</h1>
                    <p>Your one-stop online bookstore.</p>
                    <a href="#about" class="btn btn-dark">Discover the Book Store</a>
                </div>
            </div>
            <div class="carousel-item">
                <img src="book2.jpg" class="d-block w-100 carousel-image" alt="Slide 2">
                <div class="carousel-caption d-none d-md-block">
                    <h1>Welcome to Book-Line</h1>
                    <p>Your one-stop online bookstore.</p>
                    <a href="#about" class="btn btn-dark">Discover the Book Store</a>
                </div>
            </div>
            <div class="carousel-item">
                <img src="book6.jpg" class="d-block w-100 carousel-image" alt="Slide 3">
                <div class="carousel-caption d-none d-md-block">
                    <h1>Welcome to Book-Line</h1>
                    <p>Your one-stop online bookstore.</p>
                    <a href="#about" class="btn btn-dark">Discover the Book Store</a>
                </div>
            </div>
        </div>
        <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>

    <!-- About Section -->
    <div id="about" class="row mt-5">
        <div class="col-md-6">
            <div class="p-4 bg-light rounded">
                <h2>About Book-Line</h2>
                <p>Welcome to Book-Line, the first online bookstore in Somaliland! Established with a passion for literature and a vision to revolutionize the way people in Somaliland access and enjoy books, Book-Line is your go-to destination for an extensive range of titles across various genres.
                At Book-Line, we believe that books are more than just words on a page—they are gateways to new worlds, ideas, and perspectives. Our mission is to make these gateways easily accessible to everyone in Somaliland, fostering a culture of reading and learning.
                Join us on this literary journey and discover the joy of reading with Book-Line. We are excited to be a part of your reading adventure and look forward to serving you with the best books and service in Somaliland.
                </p>
            </div>
        </div>
        <div class="col-md-6">
            <img src="book6.jpg" class="img-fluid rounded" alt="About Book-Line">
        </div>
    </div>
</div>

<!-- New Arrivals Section -->
<div class="container mt-5">
    <h2 class="text-center">New Arrivals</h2>
    <div class="row">
        <?php
        // Database connection
        include 'connection.php';
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Query to get featured books
        $sql = "SELECT * FROM books WHERE is_featured = 'yes'";
        $result = $conn->query($sql);

        if ($result === FALSE) {
            echo "<p class='text-center'>Error executing query: " . $conn->error . "</p>";
        } elseif ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $book_id = htmlspecialchars($row['book_id']);
                $title = htmlspecialchars($row['title']);
                $price = htmlspecialchars($row['price']);
                $image = htmlspecialchars($row['image']);
                
                echo '<div class="col-md-3 mb-4">';
                echo '<div class="card">';
                echo '<a href="book-details.php?book_id=' . $book_id . '">';
                echo '<img src="' . $image . '" class="card-img-top" alt="' . $title . '">';
                echo '</a>';
                echo '<div class="card-body">';
                echo '<h5 class="card-title">' . $title . '</h5>';
                echo '<p class="card-text">$' . $price . '</p>';
                echo '<form action="cart.php" method="post">';
                echo '<input type="hidden" name="action" value="add">';
                echo '<input type="hidden" name="book_id" value="' . $book_id . '">';
                echo '<input type="hidden" name="quantity" value="1">'; // Default quantity as 1
                echo '<button type="submit" class="btn btn-primary">Add to Cart</button>';
                echo '</form>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
            }
        } else {
            echo '<p class="text-center">No new arrivals at the moment.</p>';
        }

        $conn->close();
        ?>
    </div>
</div>

<?php include 'footer.php'; ?>

<!-- Custom CSS -->
<style>
    .carousel-image {
        height: 400px; /* Adjust the height as needed */
        object-fit: cover;
    }
    .carousel-caption {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        text-align: center;
    }

    /* Profile icon dropdown menu */
    .nav-item.dropdown:hover .dropdown-menu {
        display: block;
        margin-top: 0; /* Optional: Removes margin for smoother dropdown */
    }
</style>

<!-- Bootstrap and jQuery Scripts -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    $('.carousel').carousel({
        interval: 3000 // 3 seconds
    });
</script>
