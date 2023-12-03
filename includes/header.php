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
        <link rel="stylesheet" href="/practice-one/assets/css/navbar.css">
        <span class="logo"><img src="https://dummyimage.com/220x50/000/fff" alt="Logo"></span>
        <nav>
            <ul>
                <li><a href="/practice-one/index.php">Home</a></li>

                <?php
                // Fetch categories from the database
                $sql = "SELECT * FROM categories";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $categoryId = $row['category_id'];
                        $categoryName = $row['category_name'];
                        echo "<li><a href='/practice-one/pages/category.php?category=$categoryId'>$categoryName</a></li>";
                    }
                } else {
                    echo "<li>No categories available</li>";
                }
                ?>

                <!-- Add more navigation links as needed -->
            </ul>
        </nav>
        <a class="cart-icon" href="/practice-one/pages/view_cart.php">Cart <span class="cart-icon">🛒</span></a>
    </header>