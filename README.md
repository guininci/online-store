# Run the project

This project was made using xampp, the only thing you need to do for running the project is:

1. Copy the project folder to your htcdocs
2. Import the database copying the below points of #Script for database creation and #Script for database population (or using the "online_store.sql" script present on this repository)
3. Configure the file /config/mysql.config.php with your database credentials

# Folder structure for the project:

```
project-root/
│
├── assets/
│   └── css/
│      ├── styles.css
│      └── (other CSS files)
│
├── config/
│   ├── config.php
│   └── mysql.config.php
│
├── includes/
│   ├── header.php
│   └── footer.php
│
├── pages/
│   ├── index.php
│   ├── product.php
│   ├── cart.php
│   ├── checkout.php
│   └── admin.php (if applicable)
│
├── .gitignore
├── index.php  (entry point)
└── README.md
```

It is Recommended to use the "online_store.sql" since the following scripts could be not updated.

# Script for database creation:
```
-- Create Categories Table
CREATE TABLE categories (
    category_id INT PRIMARY KEY AUTO_INCREMENT,
    category_name VARCHAR(255) NOT NULL
);

-- Create Products Table
CREATE TABLE products (
    product_id INT PRIMARY KEY AUTO_INCREMENT,
    category_id INT,
    product_name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    stock_quantity INT NOT NULL,
    FOREIGN KEY (category_id) REFERENCES categories(category_id)
);

-- Create Users Table with isAdmin Property
CREATE TABLE users (
    user_id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    address VARCHAR(255),
    isAdmin BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY (username),
    UNIQUE KEY (email)
);

--- Create guests table
CREATE TABLE guests (
    guest_id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL,
    email VARCHAR(255) NOT NULL,
    address VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY (guest_id)
);

-- Create Orders Table
CREATE TABLE orders (
    order_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    guest_id INT,
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    total_amount DECIMAL(10, 2) NOT NULL,
    order_status ENUM('Pending', 'Processing', 'Shipped', 'Delivered') DEFAULT 'Pending',
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (guest_id) REFERENCES guests(guest_id)
);

-- Create Order Items Table
CREATE TABLE order_items (
    item_id INT PRIMARY KEY AUTO_INCREMENT,
    order_id INT,
    product_id INT,
    quantity INT NOT NULL,
    item_price DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(order_id),
    FOREIGN KEY (product_id) REFERENCES products(product_id)
);
```

# Script for Database data population:

```
-- Insert Categories
INSERT INTO categories (category_name) VALUES
('Electronics'),
('Clothing'),
('Books');

-- Insert Products
INSERT INTO products (category_id, product_name, description, price, stock_quantity) VALUES
(1, 'Smartphone', 'High-end smartphone with advanced features.', 599.99, 50),
(2, 'T-shirt', 'Cotton T-shirt with a cool design.', 19.99, 100),
(3, 'Programming Book', 'Comprehensive guide to programming.', 39.99, 30);

-- Insert Users
INSERT INTO users (username, password, email, full_name, address, isAdmin) VALUES
('john_doe', 'password123', 'john@example.com', 'John Doe', '123 Main St', FALSE),
('diegoromer', '1234', 'diegoromer@example.com', 'diegoromer', 'Filtered address', TRUE),
('admin_user', 'admin123', 'admin@example.com', 'Admin Smith', '456 Admin St', TRUE);
```