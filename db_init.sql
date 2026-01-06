-- Set character encoding
SET NAMES utf8mb4;
SET CHARACTER SET utf8mb4;

-- Create a database if not exists: sportshop
CREATE DATABASE IF NOT EXISTS SportShop;
CREATE SCHEMA IF NOT EXISTS SportShop;

-- Set schema character set and collation
ALTER DATABASE SportShop CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci;

USE SportShop;

-- Create table Category
CREATE TABLE IF NOT EXISTS category (
    id VARCHAR(36) PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT
);
-- Create table User
CREATE TABLE IF NOT EXISTS user (
    id VARCHAR(36) PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    surname VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    phone_number VARCHAR(20),
    hashed_password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') DEFAULT 'user',
    is_active BOOLEAN DEFAULT FALSE
);
-- Create table Product
CREATE TABLE IF NOT EXISTS product (
    id VARCHAR(36) PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    category_id VARCHAR(36) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    image_url VARCHAR(500),
    rating DECIMAL(3, 2) DEFAULT 0.00,
    stock INT DEFAULT 0,
    badge ENUM('New', 'Popular', 'Best Seller','Premium','') DEFAULT '',
    discount DECIMAL(5, 2) DEFAULT 0.00,
    description TEXT,
    is_active BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (category_id) REFERENCES category(id) ON DELETE CASCADE
);
-- Create table Order
CREATE TABLE IF NOT EXISTS `order` (
    id VARCHAR(36) PRIMARY KEY,
    user_id VARCHAR(36) NOT NULL,
    order_number INT NOT NULL AUTO_INCREMENT UNIQUE,
    total DECIMAL(10, 2) NOT NULL,
    status ENUM('pending', 'processing', 'shipped', 'delivered', 'cancelled','completed') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES user(id) ON DELETE CASCADE
);
-- Create table Order_Products 
CREATE TABLE IF NOT EXISTS order_products (
    order_id VARCHAR(36) NOT NULL,
    product_id VARCHAR(36) NOT NULL,
    quantity INT DEFAULT 1,
    price_at_purchase DECIMAL(10, 2) NOT NULL,
    PRIMARY KEY (order_id, product_id),
    FOREIGN KEY (order_id) REFERENCES `order`(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES product(id) ON DELETE CASCADE
);
-- Create table Cart
CREATE TABLE IF NOT EXISTS cart (
    id VARCHAR(36) PRIMARY KEY,
    user_id VARCHAR(36) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES user(id) ON DELETE CASCADE
);
-- Create table Cart_Products
CREATE TABLE IF NOT EXISTS cart_products (
    cart_id VARCHAR(36) NOT NULL,
    product_id VARCHAR(36) NOT NULL,
    quantity INT DEFAULT 1,
    added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (cart_id, product_id),
    FOREIGN KEY (cart_id) REFERENCES cart(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES product(id) ON DELETE CASCADE
);
-- Create Indexes for optimization
CREATE INDEX idx_product_category ON product(category_id);
CREATE INDEX idx_order_user ON `order`(user_id);
CREATE INDEX idx_cart_user ON cart(user_id);
CREATE INDEX idx_order_status ON `order`(status);
CREATE INDEX idx_product_is_active ON product(is_active);
CREATE INDEX idx_user_is_active ON user(is_active);
CREATE INDEX idx_category_name ON category(name);
