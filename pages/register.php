<?php
// Database includes
require_once(__DIR__ . '../../database/db.php');

// Initialize variables
$success_message = '';
$error_message = '';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get user input
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $full_name = $_POST['full_name'];
    $address = $_POST['address'];

    // Check if the username or email is already taken
    $check_query = "SELECT * FROM users WHERE username = '$username' OR email = '$email'";
    $check_result = $conn->query($check_query);

    if ($check_result->num_rows > 0) {
        // Username or email is already taken, display an error message
        $error_message = "Username or email is already taken. Please choose a different one.";
    } else {
        // Insert the new user into the database
        $insert_query = "INSERT INTO users (username, password, email, full_name, address) VALUES ('$username', '$password', '$email', '$full_name', '$address')";
        if ($conn->query($insert_query) === TRUE) {
            // User registered successfully
            $success_message = "Registration successful. You can now login.";
        } else {
            // Error in registration, display an error message
            $error_message = "Error in registration. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <!-- Additional CSS links or meta tags -->
</head>

<body>
    <h2>Register</h2>

    <?php
    // Display success message if exists
    if ($success_message) {
        echo "<p style='color: green;'>$success_message</p>";
    }

    // Display error message if exists
    if ($error_message) {
        echo "<p style='color: red;'>$error_message</p>";
    }
    ?>

    <form action="/online-store/pages/register.php" method="post">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>

        <label for="full_name">Full Name:</label>
        <input type="text" id="full_name" name="full_name" required>

        <label for="address">Address:</label>
        <input type="text" id="address" name="address">

        <button type="submit">Register</button>
    </form>

    <p>Already have an account? <a href="/online-store/pages/login.php">Login here</a></p>
</body>

</html>