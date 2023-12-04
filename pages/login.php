<?php
// Database includes
require_once(__DIR__ . '../../database/db.php');

// Start of the session
if (!isset($_SESSION)) {
    session_start();
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get user input
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (!$password) {
        $error_message = "La contraseña es obligatoria";
    } else {
        // Validate user credentials
        $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
        $result = $conn->query($query);

        if ($result->num_rows > 0) {
            // User found, set session and redirect
            $user = $result->fetch_assoc();
            $_SESSION['user'] = $user;
            header("Location: /online-store/index.php"); // Redirect to the homepage or desired page
            exit();
        } else {
            // Invalid credentials, display an error message
            $error_message = "Invalid username or password.";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- Additional CSS links or meta tags -->
</head>

<body>
    <h2>Login</h2>

    <?php
    // Display error message if exists
    if (isset($error_message)) {
        echo "<p style='color: red;'>$error_message</p>";
    }
    ?>

    <form action="/online-store/pages/login.php" method="post">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>

        <button type="submit">Login</button>
    </form>

    <p>Don't have an account? <a href="/online-store/pages/register.php">Register here</a></p>
</body>

</html>