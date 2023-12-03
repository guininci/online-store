<!-- Database includes -->
<?php require_once(__DIR__ . '../../database/db.php'); ?>
<?php include_once(__DIR__ . '/../includes/header.php'); ?>

<?php
session_start();

// Check if the cart is not empty
if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    echo "<h2>Shopping Cart</h2>";

    foreach ($_SESSION['cart'] as $key => $item) {
        echo "<p>{$item['name']} - Quantity: {$item['quantity']} - Price: {$item['price']}";

        // Add a form to update quantity
        echo "<form action='update_cart.php' method='post'>";
        echo "<input type='hidden' name='product_id' value='{$item['product_id']}'>";
        echo "<label for='quantity'>Update Quantity:</label>";
        echo "<input type='number' name='quantity' value='{$item['quantity']}' min='1'>";
        echo "<button type='submit'>Update</button>";
        echo "</form>";

        // Add a link to remove the item
        echo "<a href='update_cart.php?action=remove&product_id={$item['product_id']}'>Remove</a>";

        echo "</p>";
    }

    echo "<button><a href='checkout.php'>Proceed to Checkout</a></button>";
} else {
    echo "<p>Your shopping cart is empty. <a href='../index.php'>Continue shopping</a>.</p>";
}
?>

<!-- Footer -->
<?php include_once(__DIR__ . '/../includes/footer.php'); ?>