        <link rel="stylesheet" href="/online-store/assets/css/footer.css">
        
        <!-- Footer -->
        <footer>
            <p>&copy; 2023 Your Online Store</p>
            <?php
            // Check if the user is an admin
            if (isset($_SESSION['user']['isAdmin']) && $_SESSION['user']['isAdmin']) {
                echo '<p><a href="/online-store/pages/admin_orders.php">Admin Orders</a></p>';
            }
            ?>
        </footer>
    </body>
</html>