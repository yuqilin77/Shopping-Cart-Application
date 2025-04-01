-- Create a new database named cs602termprojectdb
CREATE DATABASE cs602termprojectdb;
-- Switch to the newly created database
USE cs602termprojectdb;
-- Create a new user 'cs602_user' with privileges to access the database
CREATE USER 'cs602_user' @'localhost' IDENTIFIED BY 'cs602_secret';
-- Grant all privileges on the database to the user 'cs602_user'
GRANT ALL PRIVILEGES ON cs602termprojectdb.* TO 'cs602_user' @'localhost';
-- Reload privilege settings
FLUSH PRIVILEGES;
-- Create a table to store user information
CREATE TABLE users (
  id INT(11) NOT NULL AUTO_INCREMENT,
  name VARCHAR(50) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  isAdmin TINYINT(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (id)
);
-- Insert sample user data into the users table
INSERT INTO users (name, password, isAdmin)
VALUES ('yuki', 'yuki', 0),
  ('yuqi', 'yuqi', 1),
  ('yjzhu', 'yjzhu', 0);
-- Create a table to store product information
CREATE TABLE products (
  id INT(11) NOT NULL AUTO_INCREMENT,
  name VARCHAR(255) NOT NULL UNIQUE,
  description TEXT,
  price DECIMAL(10, 2) NOT NULL,
  quantity INT NOT NULL,
  isDeleted TINYINT DEFAULT 0,
  PRIMARY KEY (id)
);
-- Insert sample product data into the products table
INSERT INTO products (name, description, price, quantity)
VALUES (
    'Product A',
    'This is a product sample.',
    0.99,
    100
  ),
  (
    'Product B',
    'This is a product sample.',
    9.99,
    20
  ),
  (
    'Product BB',
    'This is a product sample, similar to Product B.',
    10.99,
    10
  ),
  (
    'Product C',
    'This is a product sample.',
    99.99,
    5
  );
-- Create a table to store order information
CREATE TABLE orders (
  id INT(11) NOT NULL AUTO_INCREMENT,
  userId INT(11) NOT NULL,
  orderList TEXT,
  comment TEXT,
  isDeleted TINYINT DEFAULT 0,
  PRIMARY KEY (id)
);