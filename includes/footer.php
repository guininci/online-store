        <link rel="stylesheet" href="/online-store/assets/css/footer.css">
        
        <!-- Footer -->
        <footer>
            <?php
            // Check if the user is an admin
            if (isset($_SESSION['user']['isAdmin']) && $_SESSION['user']['isAdmin']) {
                echo '<p>&copy; 2023 Your Online Store &nbsp;<a href="/online-store/pages/admin_orders.php">Admin Orders</a></p>';
            } else {
                echo '<p>&copy; 2023 Your Online Store</p>';
            }
            ?>
        </footer>
    </body>
</html>