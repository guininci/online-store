<!-- Database includes -->
<?php require_once(__DIR__ . '../../database/db.php'); ?>
<?php include_once(__DIR__ . '/../includes/header.php'); ?>

<?php
// Get the product ID from the URL parameter
if (isset($_GET['product']) && is_numeric($_GET['product'])) {
    $productId = $_GET['product'];

    // Fetch product details from the database
    $productQuery = "SELECT * FROM products WHERE product_id = $productId";
    $productResult = $conn->query($productQuery);

    if ($productResult->num_rows > 0) {
        $product = $productResult->fetch_assoc();
    } else {
        // Redirect or handle the case where no product ID is found
        header("Location: index.php");
        exit();
    }
} else {
    // Redirect or handle the case where no product ID is provided
    header("Location: index.php");
    exit();
}
?>
<main>
    <section class="product-details">
        <h2>
            <?php echo $product['product_name']; ?>
        </h2>
        <p>
            <?php echo $product['description']; ?>
        </p>
        <p>Price:
            <?php echo $product['price']; ?>
        </p>
        <!-- Add more product details as needed -->

        <!-- Option to add the product to the shopping cart -->
        <?php
        // Rest of your existing product.php code
        
        // Option to add the product to the shopping cart
        echo "<form action='add_to_cart.php' method='post'>";
        echo "<input type='hidden' name='product_id' value='{$product['product_id']}'>";
        echo "<label for='quantity'>Quantity:</label>";
        echo "<input type='number' name='quantity' id='quantity' value='1' min='1'>";
        echo "<button type='submit'>Add to Cart</button> <a href='../index.php'><button>Continue shopping</a></button> ";
        echo "</form>";
        ?>
    </section>
</main>

<!-- Footer -->
<?php include_once(__DIR__ . '/../includes/footer.php'); ?>