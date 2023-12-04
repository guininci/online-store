<!-- Database includes -->
<?php require_once(__DIR__ . '../../database/db.php'); ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Additional CSS links or meta tags -->
    <title>Your Online Store</title>
</head>

<body>
    <header>
        <link rel="stylesheet" href="/online-store/assets/css/navbar.css">
        <span class="logo"><img src="https://dummyimage.com/220x50/000/fff" alt="Logo"></span>
        <nav>
            <ul>
                <li><a href="/online-store/index.php">Home</a></li>

                <?php
                // Fetch categories from the database
                $sql = "SELECT * FROM categories";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $categoryId = $row['category_id'];
                        $categoryName = $row['category_name'];
                        echo "<li><a href='/online-store/pages/category.php?category=$categoryId'>$categoryName</a></li>";
                    }
                } else {
                    echo "<li>No categories available</li>";
                }
                ?>

                <!-- Add more navigation links as needed -->
            </ul>
        </nav>
        <div>
            <a class="cart-icon" href="/online-store/pages/view_cart.php">Cart <span class="cart-icon">🛒</span></a>&nbsp;&nbsp;
            <?php
            // Assuming you've already started the session
            if (!isset($_SESSION)) {
                session_start();
            }

            // Check if the session variable is set to determine if the user is logged in
            if (isset($_SESSION['user']['user_id'])) {
                // User is logged in
                echo "<a class='cart-icon' href='/online-store/pages/logout.php'>Logout</a>";
            } else {
                // User is not logged in
                echo "<a class='cart-icon' href='/online-store/pages/login.php'>Login</a>";
            }
            ?>
        </div>
    </header>