<!-- Database includes -->
<?php require_once(__DIR__ . '../../database/db.php'); ?>
<?php include_once(__DIR__ . '/../includes/header.php'); ?>

<style>
    body {
        font-family: 'Arial', sans-serif;
        background-color: #f5f5f5;
        margin: 0;
        padding: 0;
    }

    main {
        max-width: 800px;
        margin: 20px auto;
        background-color: white;
        padding: 20px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .order-confirmation {
        text-align: center;
    }

    h2 {
        color: #333;
    }

    h3 {
        color: #4caf50;
        margin-top: 20px;
    }

    p {
        color: #555;
        line-height: 1.5;
    }

    button {
        background-color: #4caf50;
        color: white;
        padding: 10px 15px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 16px;
        text-decoration: none;
        display: inline-block;
        margin-top: 10px;
    }

    button:hover {
        background-color: #45a049;
    }
</style>

<?php
// Check if the order ID is provided in the URL
if (isset($_GET['order_id']) && is_numeric($_GET['order_id'])) {
    $orderId = $_GET['order_id'];

    // Fetch order details
    $orderQuery = "SELECT * FROM orders WHERE order_id = $orderId";
    $orderResult = $conn->query($orderQuery);

    if ($orderResult->num_rows > 0) {
        $order = $orderResult->fetch_assoc();

        // Fetch order items
        $orderItemsQuery = "SELECT products.product_name, order_items.quantity, order_items.item_price
                            FROM order_items
                            INNER JOIN products ON order_items.product_id = products.product_id
                            WHERE order_items.order_id = $orderId";
        $orderItemsResult = $conn->query($orderItemsQuery);
    }
}
?>

<main>
    <section class="order-confirmation">
        <h2>Order Confirmation</h2>
        <?php if (isset($order)): ?>
            <p>Thank you for your order! Here are the details:</p>

            <h3>Order Details</h3>
            <p>Order ID:
                <?php echo $orderId; ?>
            </p>
            <p>Total Amount:
                <?php echo $order['total_amount']; ?>
            </p>
            <p>Order Status:
                <?php echo $order['order_status']; ?>
            </p>
            <p>Order Date:
                <?php echo $order['order_date']; ?>
            </p>

            <h3>Ordered Items</h3>
            <?php
            if ($orderItemsResult->num_rows > 0) {
                while ($item = $orderItemsResult->fetch_assoc()) {
                    echo "<p>{$item['product_name']} - Quantity: {$item['quantity']} - Price: {$item['item_price']}</p>";
                }
            } else {
                echo "<p>No items found in the order.</p>";
            }
            ?>
        <?php else: ?>
            <p>Invalid order ID. Please try again or contact customer support. <button><a href="../index.php">Go back to
                        Home</a></button></p>
        <?php endif; ?>
    </section>
</main>

<!-- Footer -->
<?php include_once(__DIR__ . '/../includes/footer.php'); ?>