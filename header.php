<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book-Line</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@200;400&display=swap');
        
        body, .navbar-brand, .nav-link {
            font-family: 'Poppins', sans-serif;
        }
        
        .navbar {
            background-color: transparent;
            padding: 0.5rem 0;
        }
        .navbar .container {
            padding: 0;
        }
        .navbar-brand {
            font-size: 2rem;
            color: #000000;
        }
        .nav-link {
            color: #000000 !important;
        }
        .nav-link:hover {
            color: #555555 !important;
        }
        .nav-item.active .nav-link {
            color: #333333 !important;
        }
        .cart-icon {
            position: relative;
        }
        .cart-icon::after {
            position: absolute;
            top: -10px;
            right: -10px;
            background: red;
            color: white;
            border-radius: 50%;
            padding: 2px 6px;
            font-size: 12px;
        }
        .search-form {
            margin: 0;
        }
        .search-input {
            border-radius: 50px;
        }
        .search-button {
            border-radius: 50px;
        }
        .dropdown-menu {
            min-width: 200px;
        }
        .dropdown-menu-right {
            right: 0;
            left: auto;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <a class="navbar-brand" href="home.php">
                <img src="logo.png" alt="Logo" style="width:40px;"> Book-Line
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item active">
                        <a class="nav-link" href="home.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="books.php">Books</a>
                    </li>
                    <?php if (isset($_SESSION['username'])): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="cart.php"><i class="fas fa-shopping-cart cart-icon"></i> Cart</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="profileDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-user"></i> Hi, <?php echo htmlspecialchars($_SESSION['username']); ?>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="profileDropdown">
                                <a class="dropdown-item" href="history.php">History</a>
                                
                                <?php 
                                // Database connection
                                include 'connection.php';

                                // Fetch user ID from session
                                $user_id = $_SESSION['user_id'];

                                // Check if user has any orders
                                $order_check_sql = "SELECT COUNT(*) AS total_orders FROM orders WHERE user_id = ?";
                                $stmt = $conn->prepare($order_check_sql);
                                $stmt->bind_param("i", $user_id);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                $row = $result->fetch_assoc();
                                $total_orders = $row['total_orders'];

                                // If user has orders, show "Your Order Status" in dropdown
                                if ($total_orders > 0): ?>
                                    <a class="dropdown-item" href="order_status.php">Your Order Status</a>
                                <?php endif; ?>

                                <a class="dropdown-item" href="logout.php">Logout</a>
                            </div>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="login.php">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="register.php">Register</a>
                        </li>
                    <?php endif; ?>
                </ul>
                <form class="form-inline ml-3 search-form" action="search.php" method="GET">
                    <input class="form-control search-input mr-sm-2" type="search" name="query" placeholder="Search by title/author" aria-label="Search">
                    <button class="btn btn-outline-dark search-button my-2 my-sm-0" type="submit">Search</button>
                </form>
            </div>
        </div>
    </nav>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
