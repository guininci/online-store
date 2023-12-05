<!-- Database includes -->
<?php require_once(__DIR__ . '../../database/db.php'); ?>
<?php include_once(__DIR__ . '/../includes/header.php'); ?>
<style>
    .container {
        margin: auto;
        display: table;
    }

    .categories {
        width: 100% !important;
    }

    .logo {
        display: inline-block;
        /* Make the logo an inline block */
        vertical-align: middle;
        /* Align the logo vertically in the middle */
    }

    a {
        display: inline-block;
        /* Make the link an inline block */
        vertical-align: middle;
        /* Align the link vertically in the middle */
        margin-left: 10px;
        /* Add some space between the logo and the link */
    }

    .logo {
        display: inline-block;
        /* Make the logo an inline block */
        vertical-align: middle;
        /* Align the logo vertically in the middle */
    }

    a {
        display: inline-block;
        /* Make the link an inline block */
        vertical-align: middle;
        /* Align the link vertically in the middle */
        margin-left: 10px;
        /* Add some space between the logo and the link */
    }
</style>
<main>
    <section class="categories">
        <div class="container">
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
                        echo "<li>
  <span class='logo'><img src='https://dummyimage.com/220x125/000/fff' alt='Logo'></span>
  <a href='pages/category.php?category=$categoryId'>$categoryName</a>
</li>";
                    }
                } else {
                    echo "<li>No categories available</li>";
                }
                ?>
            </ul>
        </div>
    </section>
</main>

<!-- Footer -->
<?php include_once(__DIR__ . '/../includes/footer.php'); ?>