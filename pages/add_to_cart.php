<!-- Database includes -->
<?php require_once(__DIR__ . '../../database/db.php'); ?>
<?php include_once(__DIR__ . '/../includes/header.php'); ?>

<?php
if (!isset($_SESSION)) {
    session_start();
}

// Check if the product ID and quantity are provided
if (isset($_POST['product_id'], $_POST['quantity'])) {
    $productId = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    // Fetch product details from the database
    $productQuery = "SELECT * FROM products WHERE product_id = $productId";
    $productResult = $conn->query($productQuery);

    if ($productResult->num_rows > 0) {
        $product = $productResult->fetch_assoc();

        // Create or update the cart in the session
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        // Check if the product is already in the cart
        if (isset($_SESSION['cart'][$productId])) {
            // Update quantity if the product is already in the cart
            $_SESSION['cart'][$productId]['quantity'] += $quantity;
        } else {
            // Add the product to the cart
            $_SESSION['cart'][$productId] = [
                'product_id' => $productId,
                'name' => $product['product_name'],
                'price' => $product['price'],
                'quantity' => $quantity,
            ];
        }

        // Redirect to the product page or cart page
        header("Location: product.php?product=$productId");
        exit();
    }
}

// Redirect if something goes wrong
header("Location: index.php");
exit();
?>