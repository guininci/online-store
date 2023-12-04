<?php
if (!isset($_SESSION)) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the form is submitted to update quantity
    if (isset($_POST['product_id'], $_POST['quantity'])) {
        $productId = $_POST['product_id'];
        $quantity = $_POST['quantity'];

        // Validate and update the quantity
        if ($quantity > 0) {
            $_SESSION['cart'][$productId]['quantity'] = $quantity;
        } else {
            // Remove the item if the quantity is 0 or negative
            unset($_SESSION['cart'][$productId]);
        }
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Check if the request is to remove an item
    if (isset($_GET['action'], $_GET['product_id']) && $_GET['action'] === 'remove') {
        $productId = $_GET['product_id'];

        // Remove the item from the cart
        unset($_SESSION['cart'][$productId]);
    }
}

// Redirect back to the cart page
header("Location: view_cart.php");
exit();
?>