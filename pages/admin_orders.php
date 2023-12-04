<!-- Database includes -->
<?php require_once(__DIR__ . '../../database/db.php'); ?>
<?php include_once(__DIR__ . '/../includes/header.php'); ?>

<?php
// Check if the user is logged in and is an admin
if (!isset($_SESSION['user']) || !isset($_SESSION['user']['isAdmin']) || !$_SESSION['user']['isAdmin']) {
    // Redirect to the home page or login page if not an admin
    header("Location: /online-store/index.php");
    exit();
}
?>

<main>
    <section class="admin-orders">
        <h2>Admin Orders</h2>

        <?php
        // Fetch all orders with user information and items
        $query = "SELECT orders.*, 
            users.full_name AS user_name, users.address AS user_address, users.full_name AS user_full_name,
            guests.username AS guest_user_name, guests.email AS guest_email, guests.address AS guest_address
          FROM orders
          LEFT JOIN users ON orders.user_id = users.user_id
          LEFT JOIN guests ON orders.guest_id = guests.guest_id
          ORDER BY orders.order_date DESC";

        $result = $conn->query($query);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $orderId = $row['order_id'];
                $orderDate = $row['order_date'];
                $totalAmount = $row['total_amount'];
                $orderStatus = $row['order_status'];
                $userName = !$row['user_name'] ? $row['guest_user_name'] . "<strong> (Guest)</strong>" : $row['user_name'] . "<strong> (Registered User)</strong> ";
                $address = !$row['user_address'] ? $row['guest_address'] : $row['user_address'];

                echo "<div class='order'>";
                echo "<h3>Order ID: $orderId</h3>";
                echo "<p>Order Date: $orderDate</p>";
                echo "<p>Total Amount: $totalAmount</p>";
                echo "<p>Order Status: $orderStatus</p>";
                echo "<p>User: $userName</p>";
                echo "<p>Address: $address</p>";

                // Fetch items in the order
                $itemsQuery = "SELECT order_items.quantity, order_items.item_price, products.product_name
                               FROM order_items
                               JOIN products ON order_items.product_id = products.product_id
                               WHERE order_items.order_id = $orderId";

                $itemsResult = $conn->query($itemsQuery);

                if ($itemsResult->num_rows > 0) {
                    echo "<h4>Order Items:</h4>";
                    echo "<ul>";
                    while ($item = $itemsResult->fetch_assoc()) {
                        echo "<li>{$item['quantity']} x {$item['product_name']} - {$item['item_price']}</li>";
                    }
                    echo "</ul>";
                } else {
                    echo "<p>No items in this order.</p>";
                }

                echo "</div>";
            }
        } else {
            echo "<p>No orders available.</p>";
        }

        // Close the database connection
        $conn->close();
        ?>
    </section>
</main>

<!-- Footer -->
<?php include_once(__DIR__ . '/../includes/footer.php'); ?>