<!-- Database includes -->
<?php require_once(__DIR__ . '../../database/db.php'); ?>
<?php include_once(__DIR__ . '/../includes/header.php'); ?>

<style>
    .cart-container {
        text-align: center;
        margin: auto;
        max-width: 600px;
        border-radius: 16px;
        border: 1px #333 solid;
        /* Adjust the maximum width as needed */
    }

    h2 {
        color: #333;
        font-size: 24px;
        margin-bottom: 15px;
    }

    p {
        color: #666;
        font-size: 16px;
        margin-bottom: 10px;
    }

    label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
        color: #333;
    }

    input,
    textarea,
    select {
        width: 100%;
        padding: 8px;
        margin-bottom: 15px;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
    }

    button {
        background-color: #4caf50;
        color: white;
        padding: 10px 15px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 16px;
    }

    button:hover {
        background-color: #45a049;
    }

    .form-container {
        border: 1px #000 solid;
        border-radius: 16px;
        margin: 2%;
    }

    form {
        top: 0;
        margin: 2%;
        width: 45%;
        padding: 2%;
        /* height: 100%; Remove the fixed height */
        display: inline-block;
        box-sizing: border-box;
        /* Ensure that padding and borders are included in the width */
    }

    /* Add a class to the second form to adjust its margin */
    .guest-form {
        display: inline-block;
        margin-left: 2%;
        /* Adjust the margin as needed */
        width: 48%;
        /* Set width to 48% to allow space for borders and margins */
        box-sizing: border-box;
        /* Ensure that padding and borders are included in the width */
    }

    /* Clearfix to prevent forms from collapsing into each other */
    .clearfix::after {
        content: "";
        clear: both;
        display: table;
    }
</style>

<?php
if (!isset($_SESSION)) {
    session_start();
}

// Check if the cart is not empty
if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    echo "<div class='cart-container'>";
    echo "<h2>Cart Summary</h2>";

    foreach ($_SESSION['cart'] as $item) {
        echo "<p>{$item['name']} - Quantity: {$item['quantity']} - Price: {$item['price']}</p>";
    }

    // Calculate the total price by multiplying price with quantity for each item
    $totalPrice = array_sum(array_map(function ($item) {
        return $item['price'] * $item['quantity'];
    }, $_SESSION['cart']));


    echo "<p>Total Price: $totalPrice</p>";

    // Check if the form is submitted (checkout button clicked)
    // Collect user data for users (logged, guests, and registered)
    $userData = [];

    // The rest of your code...

    echo "</div>"; // Close the cart-container div

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // If the user is logged in
        if (
            isset($_SESSION['user']['user_id']) &&
            $_SERVER['REQUEST_METHOD'] === 'POST'
        ) {
            $userData = [
                'full_name' => $_SESSION['user']['full_name'],
                'password' => $_SESSION['user']['password'], // Note: You should hash the password before storing it
                'email' => $_SESSION['user']['email'],
                'address' => $_SESSION['user']['address'],
            ];
        } else {
            // Collect user data based on the chosen type (registered or guest)
            $userData = [];
            if ($_POST['user_type'] === 'registered') {
                // Collect user data for registered users
                $userData = [
                    'full_name' => $_POST['full_name'],
                    'password' => $_POST['password'], // Note: You should hash the password before storing it
                    'email' => $_POST['email'],
                    'address' => $_POST['address'],
                ];
            } elseif ($_POST['user_type'] === 'guest') {
                // Collect user data for guest users
                $userData = [
                    'email' => $_POST['email'],
                    'address' => $_POST['address'],
                ];
            }
        }

        // Save order details in the database
        $userId = null;

        // Check if the user is registered
        if (isset($userData['full_name'])) {
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

        if ($userId !== null) {
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
        } else {
            // User does not exist, insert a new user
            $insertUserQuery = "INSERT INTO guests (username, email, address)
                                    VALUES ('{$userData['email']}', '{$userData['email']}', '{$userData['address']}')";
            $conn->query($insertUserQuery);

            // Get the newly inserted user ID
            $guestId = $conn->insert_id;

            // Save the order details
            $insertOrderQuery = "INSERT INTO orders (guest_id, total_amount) VALUES ($guestId, $totalPrice)";
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
        }

        // After storing the order, you may clear the cart or perform other necessary actions
        unset($_SESSION['cart']);

        // Redirect to the order confirmation page
        header("Location: order_confirmation.php?order_id=$orderId");
        exit();
    }

    if (isset($_SESSION['user']['user_id'])) {
        // Display the checkout form for registered users
        echo "<form action='checkout.php' method='post'>";
        echo "<button type='submit'>Proceed to Confirm Order</button>";
        echo "</form>";
    }

    if (!isset($_SESSION['user']['user_id'])) {
        echo "<div class='form-container'>";

        // Display the checkout form for registered users
        echo "<form action='checkout.php' method='post'>";
        echo "<input type='hidden' name='user_type' value='registered'>";
        echo "<h2>Register Checkout</h2>";
        echo "<label for='full_name'>Full Name:</label>";
        echo "<input type='text' name='full_name' required><br>";
        echo "<label for='password'>Password:</label>";
        echo "<input type='password' name='password' required><br>";
        echo "<label for='email'>Email:</label>";
        echo "<input type='email' name='email' required><br>";
        echo "<label for='address'>Shipping Address:</label>";
        echo "<textarea name='address' required></textarea><br>";
        echo "<button type='submit'>Proceed to Confirm Order</button>";
        echo "</form>";

        // Display the checkout form for guest users
        echo "<form action='checkout.php' method='post'>";
        echo "<input type='hidden' name='user_type' value='guest'>";
        echo "<h2>Guest Checkout</h2>";
        echo "<label for='email'>Email:</label>";
        echo "<input type='email' name='email' required><br>";
        echo "<label for='address'>Shipping Address:</label>";
        echo "<textarea name='address' required></textarea><br>";
        echo "<button type='submit'>Proceed to Confirm Order</button>";
        echo "</form>";

        echo "</div>";
        // JavaScript to toggle visibility of registered fields based on user type selection
        echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                var registeredForm = document.forms[0];
                var guestForm = document.forms[1];
                
                document.getElementById('user_type').addEventListener('change', function() {
                    if (this.value === 'registered') {
                        registeredForm.style.display = 'block';
                        guestForm.style.display = 'none';
                    } else {
                        registeredForm.style.display = 'none';
                        guestForm.style.display = 'block';
                    }
                });
            });
          </script>";
    }

} else {
    echo "<p>Your shopping cart is empty. <a href='../index.php'>Continue shopping</a>.</p>";
}
?>

<!-- Footer -->
<?php include_once(__DIR__ . '/../includes/footer.php'); ?>