<?php
    // Define a class named Model
    class Model {
        // Private property to hold the database connection instance
        private $db;

        // Constructor that accepts a database connection instance and assigns it to the private property
        public function __construct($db) {
            $this->db = $db;
        }

        // Method to get products by name
        public function getProductsByName($product_name) {
            $sql_query = "SELECT * FROM products WHERE isDeleted = 0 AND name LIKE BINARY \"$product_name%\" ORDER BY id;";
            return $this->db->query($sql_query);
        }

        // Method to get product details by ID
        public function getProductById($product_id) {
            $sql_query = "SELECT * FROM products WHERE id = \"$product_id\";";
            return $this->db->query($sql_query);
        }

        // Method to get products by description
        public function getProductsByDescription($description) {
            $sql_query = "SELECT * FROM products WHERE isDeleted = 0 AND description LIKE BINARY \"%$description%\" ORDER BY id;";
            return $this->db->query($sql_query);
        }

        // Method to get all non-deleted products
        public function getAllProducts() {
            $sql_query = "SELECT * FROM products WHERE isDeleted = 0 ORDER BY id;";
            return $this->db->query($sql_query);
        }

        // Method to get all non-admin customers
        public function getAllCustomers() {
            $sql_query = "SELECT * FROM users WHERE isAdmin = 0 ORDER BY id;";
            return $this->db->query($sql_query);
        }

        // Method to get orders by customer
        public function getOrdersByCustomer($user_id) {
            $sql_query = "SELECT * FROM orders WHERE userId = \"$user_id\" ORDER BY isDeleted, id DESC;";
            return $this->db->query($sql_query);
        }

        // Method to get order details by ID
        public function getOrderById($order_id) {
            $sql_query = "SELECT * FROM orders WHERE id = \"$order_id\" ORDER BY id;";
            return $this->db->query($sql_query);
        }
    }
?>