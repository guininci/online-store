<!-- Database includes -->
<?php require_once(__DIR__ . '../../database/db.php'); ?>
<?php include_once(__DIR__ . '/../includes/header.php'); ?>

<?php
session_start();

// Check if the cart is not empty
if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    // Display the cart summary
    echo "<h2>Cart Summary</h2>";

    foreach ($_SESSION['cart'] as $item) {
        echo "<p>{$item['name']} - Quantity: {$item['quantity']} - Price: {$item['price']}</p>";
    }

    // Calculate the total price
    $totalPrice = array_sum(array_column($_SESSION['cart'], 'price'));

    echo "<p>Total Price: $totalPrice</p>";

    // Check if the form is submitted (checkout button clicked)
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Collect user data based on the chosen type (registered or guest)
        $userData = [];
        if (isset($_POST['user_type']) && $_POST['user_type'] === 'registered') {
            // Collect user data for registered users
            $userData = [
                'full_name' => $_POST['full_name'],
                'password' => $_POST['password'], // Note: You should hash the password before storing it
                'email' => $_POST['email'],
                'address' => $_POST['address'],
            ];
        } elseif (isset($_POST['user_type']) && $_POST['user_type'] === 'guest') {
            // Collect user data for guest users
            $userData = [
                'email' => $_POST['email'],
                'address' => $_POST['address'],
            ];
        }

        // Save order details in the database
        $userId = null;

        // Check if the user is registered
        if (isset($userData['email'])) {
            // Check if the user already exists based on the email
            $checkUserQuery = "SELECT user_id FROM users WHERE email = '{$userData['email']}' LIMIT 1";
            $checkUserResult = $conn->query($checkUserQuery);

            if ($checkUserResult->num_rows > 0) {
                // User already exists, get the user ID
                $userRow = $checkUserResult->fetch_assoc();
                $userId = $userRow['user_id'];
            } else {
                // User does not exist, insert a new user
                $insertUserQuery = "INSERT INTO users (username, password, email, full_name, address)
                                    VALUES ('{$userData['email']}', '{$userData['password']}', '{$userData['email']}', '{$userData['full_name']}', '{$userData['address']}')";
                $conn->query($insertUserQuery);

                // Get the newly inserted user ID
                $userId = $conn->insert_id;
            }
        }

        // Save the order details
        $insertOrderQuery = "INSERT INTO orders (user_id, total_amount) VALUES ($userId, $totalPrice)";
        $conn->query($insertOrderQuery);

        // Get the newly inserted order ID
        $orderId = $conn->insert_id;

        // Save order items
        foreach ($_SESSION['cart'] as $item) {
            $productId = $item['product_id'];
            $quantity = $item['quantity'];
            $itemPrice = $item['price'];

            $insertOrderItemQuery = "INSERT INTO order_items (order_id, product_id, quantity, item_price)
                                     VALUES ($orderId, $productId, $quantity, $itemPrice)";
            $conn->query($insertOrderItemQuery);
        }

        // After storing the order, you may clear the cart or perform other necessary actions
        unset($_SESSION['cart']);

        // Redirect to the order confirmation page
        header("Location: order_confirmation.php?order_id=$orderId");
        exit();
    }

    // Display the checkout form
    echo "<h2>Checkout</h2>";
    echo "<form action='checkout.php' method='post'>";
    echo "<label for='user_type'>Select User Type:</label>";
    echo "<select name='user_type' id='user_type'>";
    echo "<option value='registered'>Registered User</option>";
    echo "<option value='guest'>Guest User</option>";
    echo "</select>";

    // Additional fields for registered users
    echo "<div id='registered_fields' style='display:none;'>";
    echo "<label for='full_name'>Full Name:</label>";
    echo "<input type='text' name='full_name' required><br>";
    echo "<label for='password'>Password:</label>";
    echo "<input type='password' name='password' required><br>";
    echo "</div>";

    // Common fields for both user types
    echo "<label for='email'>Email:</label>";
    echo "<input type='email' name='email' required><br>";
    echo "<label for='address'>Shipping Address:</label>";
    echo "<textarea name='address' required></textarea><br>";

    echo "<button type='submit'>Proceed to Confirm Order</button>";
    echo "</form>";

    // JavaScript to toggle visibility of registered fields based on user type selection
    echo "<script>
            document.getElementById('user_type').addEventListener('change', function() {
                var registeredFields = document.getElementById('registered_fields');
                registeredFields.style.display = this.value === 'registered' ? 'block' : 'none';
            });
          </script>";
} else {
    echo "<p>Your shopping cart is empty. <a href='index.php'>Continue shopping</a>.</p>";
}
?>

<!-- Footer -->
<?php include_once(__DIR__ . '/../includes/footer.php'); ?>