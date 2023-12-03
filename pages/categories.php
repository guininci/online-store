<!-- Database includes -->
<?php require_once(__DIR__ . '../../database/db.php'); ?>
<?php include_once(__DIR__ . '/../includes/header.php'); ?>

<main>
    <section class="categories">
        <h2>Categories</h2>
        <ul>
            <?php

            // Fetch categories from the database
            $sql = "SELECT * FROM categories";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $categoryId = $row['category_id'];
                    $categoryName = $row['category_name'];
                    echo "<li><a href='pages/category.php?category=$categoryId'>$categoryName</a></li>";
                }
            } else {
                echo "<li>No categories available</li>";
            }
            ?>
        </ul>
    </section>
</main>

<!-- Footer -->
<?php include_once(__DIR__ . '/../includes/footer.php'); ?>