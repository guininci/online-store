<!-- Database includes -->
<?php require_once(__DIR__ . '../../database/db.php'); ?>
<?php include_once(__DIR__ . '/../includes/header.php'); ?>

<?php
// Get the category ID from the URL parameter
if (isset($_GET['category']) && is_numeric($_GET['category'])) {
    $categoryId = $_GET['category'];

    // Fetch category name from the database
    $categoryQuery = "SELECT category_name FROM categories WHERE category_id = $categoryId";
    $categoryResult = $conn->query($categoryQuery);

    if ($categoryResult->num_rows > 0) {
        $categoryRow = $categoryResult->fetch_assoc();
        $categoryName = $categoryRow['category_name'];
    } else {
        $categoryName = "Unknown Category";
    }

    // Fetch products for the specified category
    $productsQuery = "SELECT * FROM products WHERE category_id = $categoryId";
    $productsResult = $conn->query($productsQuery);
} else {
    // Redirect or handle the case where no category ID is provided
    header("Location: index.php");
    exit();
}
?>

<main>
    <section class="category-products">
        <h2>
            <?php echo $categoryName; ?>
        </h2>
        <?php
        if ($productsResult->num_rows > 0) {
            while ($product = $productsResult->fetch_assoc()) {
                echo "<div class='product'>";
                echo "<h3>{$product['product_name']}</h3>";
                echo "<p>{$product['description']}</p>";
                echo "<p>Price: {$product['price']}</p>";
                // Link each product to its own page (product.php) with the product ID as a parameter
                echo "<a href='product.php?product={$product['product_id']}'>View Details</a>";
                echo "</div>";
            }
        } else {
            echo "<p>No products available in this category.</p>";
        }
        ?>
    </section>
</main>

<!-- Footer -->
<?php include_once(__DIR__ . '/../includes/footer.php'); ?>