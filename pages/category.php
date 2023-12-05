<!-- Database includes -->
<?php require_once(__DIR__ . '../../database/db.php'); ?>
<?php include_once(__DIR__ . '/../includes/header.php'); ?>

<style>
  .category-products {
    text-align: center; /* Center the content within the section */
  }

  .product {
    display: inline-block; /* Make each product item inline to appear side by side */
    text-align: left; /* Align the content of each product item to the left */
    margin: 10px; /* Add some margin around each product item */
    padding: 10px; /* Add some padding to each product item */
    border: 1px solid #ccc; /* Add a border for better visibility */
  }
</style>

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