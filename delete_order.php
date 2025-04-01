<?php
    // Include the database configuration file
    require_once('database.php');

    // Check if a session is already active, if not, start a new session
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    // Check if the user is authenticated (logged in)
    if (!isset($_SESSION['user_id'])) {
        // If not authenticated, set an error message and include an error page, then exit
        $error = "Please login first.";
        include('error.php');
        exit();
    }

    // Check if the delete button was clicked and the necessary data is present
    if (isset($_POST['delete']) && isset($_POST['order_id'])) {
        $sql_product_list = []; // Initialize an array to store SQL queries

        // Retrieve the order ID from the POST data
        $order_id = $_POST['order_id'];

        // Query to fetch the orderList for the specified order ID
        $sql_query = "SELECT orderList FROM orders WHERE id = \"$order_id\";";
        try {
            // Execute the query and retrieve the result
            $result = $db->query($sql_query);
        } catch (PDOException $e) {
            // If an exception occurs (e.g., database error), handle it
            $error_message = $e->getMessage();
            include('database_error.php');
            exit();
        }

        // Fetch the order data
        $orders = $result->fetch(PDO::FETCH_ASSOC);
        
        // Split the orderList into individual product entries
        $parts = explode(';', $orders['orderList']);
        
        // Iterate over each product entry
        foreach ($parts as $order) {
            list($key, $value) = explode('*', $order);
            $product_id = $key;
            $order_quantity = intval($value);
            // Create an SQL query to update the product quantity
            $sql_product_list[] = "UPDATE products SET quantity = quantity + $order_quantity WHERE id=\"$product_id\"";
        }

        try {
            // Start a transaction
            $db->beginTransaction();

            // Execute each product quantity update query
            foreach ($sql_product_list as $sql_query) {
                $db->exec($sql_query);
            }

            // Create an SQL query to mark the order as deleted
            $sql_query = "UPDATE orders SET isDeleted = 1 WHERE id = \"$order_id\";";
            $db->exec($sql_query);
            
            // Commit the transaction if all queries executed successfully
            $db->commit();
        } catch (PDOException $e) {
            // If an error occurred, rollback the transaction
            $db->rollback();
            $error_message = "Transaction failed: " . $e->getMessage();
            include('database_error.php');
            exit();
        }
    }
    
    // Redirect back to the admin interface after processing
    header("Location: admin_interface.php");
    exit;
?>
