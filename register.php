<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $conn = new mysqli('localhost', 'root', '', 'bookstore');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check if the username already exists
    $check_sql = "SELECT * FROM users WHERE username='$username'";
    $check_result = $conn->query($check_sql);

    if ($check_result->num_rows > 0) {
        $error = "Username already taken. Please choose a different username.";
    } else {
        $sql = "INSERT INTO users (username, password) VALUES ('$username', '$password')";

        if ($conn->query($sql) === TRUE) {
            echo "<div class='alert alert-success text-center'>New record created successfully</div>";
        } else {
            echo "<div class='alert alert-danger text-center'>Error: " . $sql . "<br>" . $conn->error . "</div>";
        }
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: url('book5.jpg') no-repeat center center fixed;
            background-size: cover;
            color: #333;
            font-family: 'Arial', sans-serif;
        }
        .container {
            max-width: 600px;
            margin-top: 100px;
            animation: fadeIn 1s ease-in;
        }
        .card {
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            background-color: #ffffff;
            position: relative;
            overflow: hidden;
        }
        .card-header {
            background-color: #343a40; /* Dark color matching the button */
            color: white;
            border-radius: 15px 15px 0 0;
            text-align: center;
            padding: 1.5rem;
            position: relative;
            z-index: 1;
        }
        .card-body {
            padding: 2rem;
            position: relative;
            z-index: 1;
        }
        .form-control {
            border-radius: 30px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }
        .form-control:focus {
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
            transform: scale(1.02);
        }
        .btn {
            border-radius: 30px;
            padding: 0.75rem 1.5rem;
            font-size: 1.1rem;
            transition: all 0.3s ease;
        }
        .btn-dark {
            background-color: #343a40; /* Dark color */
            border: none;
        }
        .btn-dark:hover {
            background-color: #23272b; /* Slightly darker for hover effect */
            transform: scale(1.05);
        }
        .alert {
            border-radius: 30px;
            font-size: 1.1rem;
            animation: fadeIn 1s ease-in;
        }
        .footer {
            text-align: center;
            margin-top: 2rem;
        }
        .footer a {
            color: #343a40; /* Dark color for consistency */
            text-decoration: none;
            font-weight: bold;
        }
        .footer a:hover {
            text-decoration: underline;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideUp {
            from { transform: translateY(20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h3>Create New Account</h3>
            </div>
            <div class="card-body">
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger text-center"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>
                <form action="registration.php" method="POST">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-dark btn-block">Register</button>
                </form>
                <div class="footer">
                    <p>Already have an account? <a href="login.php">Login here</a></p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
