<!-- Database includes -->
<?php require_once(__DIR__ . '../../database/db.php'); ?>
<?php include_once(__DIR__ . '/../includes/header.php'); ?>

<style>
    .cart-container {
        text-align: center;
        margin: auto;
        max-width: 600px;
        /* Adjust the maximum width as needed */
    }

    .checkout-button {
        margin-top: 20px;
        display: inline-block;
        /* Ensures the button doesn't stretch to 100% width */
    }

    .total-section {
        margin-top: 10px;
        font-weight: bold;
    }

    table {
        width: 100%;
        margin-top: 10px;
        /* Add some space above the table */
    }
</style>
<?php
if (!isset($_SESSION)) {
    session_start();
}

// Check if the cart is not empty
if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    echo "<div class='cart-container'>";
    echo "<h2>Shopping Cart</h2>";

    echo "<table border='1'>";
    echo "<thead><tr><th>Products</th><th>Quantity</th><th>Actions</th></tr></thead>";
    echo "<tbody>";

    $totalPrice = 0;

    foreach ($_SESSION['cart'] as $key => $item) {
        echo "<tr>";
        echo "<td>{$item['name']} - Price: {$item['price']}</td>";
        echo "<td>";
        // Add a form to update quantity
        echo "<form action='update_cart.php' method='post'>";
        echo "<input type='hidden' name='product_id' value='{$item['product_id']}'>";
        echo "<input type='number' name='quantity' value='{$item['quantity']}' min='1'>";
        echo "<button type='submit'>Update</button>";
        echo "</form>";
        echo "</td>";
        echo "<td><a href='update_cart.php?action=remove&product_id={$item['product_id']}'>Remove</a></td>";
        echo "</tr>";

        // Calculate the total price
        $totalPrice += ($item['price'] * $item['quantity']);
    }

    echo "</tbody>";
    echo "</table>";

    // Display the total
    echo "<div class='total-section'>Total Price: $totalPrice</div>";

    echo "<button class='checkout-button'><a href='checkout.php'>Proceed to Checkout</a></button>";
    echo "</div>"; // Close the cart-container div
} else {
    echo "<p>Your shopping cart is empty. <a href='../index.php'>Continue shopping</a>.</p>";
}
?>

<!-- Footer -->
<?php include_once(__DIR__ . '/../includes/footer.php'); ?>